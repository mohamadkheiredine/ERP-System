<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\models\FnB\FnbPosShift;
use Illuminate\Http\Request;
use DB;
use App\models\Inventory\Stocks;
use App\models\System\Currency;
use App\models\Users\Users;
use App\library\AccountingManager;
use App\models\Inventory\Customers;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\models\Billing\PaymentTypes;
use App\models\System\Companies;
use App\Models\FnB\FnbIngredients;
use App\models\FnB\FnbMenuItem;
use App\models\FnB\FnbOrderItemModifiers;
use App\models\FnB\FnbOrderItems;
use App\models\FnB\FnbOrders;
use App\models\FnB\FnbOrderTables;
use App\models\FnB\Modifier;
use App\library\OrdersExport;
use App\Models\FnB\FnbMenuItemModifier;
use App\models\FnB\FnbPrintJobs;
use App\models\System\SystemStatus;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

use App\Models\Fnb\FnbWasteStock;
use App\models\Inventory\Products;

class FnbOrderController extends Controller
{


    public function GenerateOrdereCodeFNB()
    {
        return DB::transaction(function () {

            $yearSuffix = date('y'); // last 2 digits of year (e.g. 26)

            $last = FnbOrders::lockForUpdate()
                ->where('fo_order_code', 'like', 'ORD' . $yearSuffix . '%')
                ->orderBy('fo_id', 'desc')
                ->first();

            // If no orders for this year yet
            if (!$last || empty($last->fo_order_code)) {
                return 'ORD' . $yearSuffix . '0001';
            }

            // Extract numeric part after ORDyy
            // Example: ORD260045 → 45
            $lastNumber = (int) substr($last->fo_order_code, 5);

            $nextNumber = $lastNumber + 1;

            return 'ORD' . $yearSuffix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }



    private function reduceStock($product_id, $warehouse_id, $qty_to_reduce)
    {
        if ($qty_to_reduce <= 0) {
            return;
        }

        DB::transaction(function () use ($product_id, $warehouse_id, &$qty_to_reduce) {

            $available_stock = Stocks::where('fk_product_id', $product_id)
                ->where('fk_warehouse_id', $warehouse_id)
                ->where('is_quanity', '>', 0)
                ->orderBy('is_id', 'asc')
                ->lockForUpdate()
                ->get();

            $total_stock = $available_stock->sum('is_quanity');
            if ($total_stock < $qty_to_reduce) {
                throw new \Exception("Not enough stock for product ID $product_id. Need $qty_to_reduce but only $total_stock available.");
            }

            foreach ($available_stock as $batch) {
                if ($qty_to_reduce <= 0) {
                    break;
                }

                $take = min($batch->is_quanity, $qty_to_reduce);

                $batch->decrement('is_quanity', $take);
                $qty_to_reduce -= $take;
            }
        });
    }

    private function restoreStock($product_id, $warehouse_id, $qty)
    {
        Stocks::where('fk_product_id', $product_id)
            ->where('fk_warehouse_id', $warehouse_id)
            ->increment('is_quanity', $qty);
    }

    private function resolvePendingKitchenStatusId(): ?int
    {
        return SystemStatus::where('ss_status_type', 'kitchen_order_statuses')
            ->orderBy('ss_id')
            ->value('ss_id');
    }

    private function resolveOrderStatusId(string $statusTitle): int
    {
        $statusId = SystemStatus::where('ss_status_type', 'like', '%pos%')
            ->whereRaw('LOWER(ss_status_title) = ?', [strtolower($statusTitle)])
            ->where('ss_is_deleted', 0)
            ->value('ss_id');

        if (!$statusId) {
            throw new \Exception("POS order status '{$statusTitle}' is not configured");
        }

        return (int) $statusId;
    }

    /**
     * @author Mohammed kheiredine
     * @access public
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function SaveOrder(Request $request)
    {
        /**
         * - ifrequest contains "updated_items" then run editOrder logic
         * - else => run createOrder logic
         */
        $has_updated_items = $request->has('updated_items');

        if ($has_updated_items) {
            $g_hash = $request->input("g_hash");
            $user_id = $request->input("user_id");
            $store_id = $request->input("store_id");
            $warehouse_id = $request->input("warehouse_id");
            $order_id = $request->input("order_id");
            $updated_items = $request->input("updated_items");
            $customer_id = $request->input('customer_id');
            $delcustomername = $request->input('delcustomername');
            $delcustomerphone = $request->input('delcustomerphone');
            $delcustomeraddress = $request->input('delcustomeraddress');
            $customer_type = $request->input('customer_type');


            $user_info    = Users::find($user_id);

            $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";

            $c_hash = hash('sha256', $c_hash);
            $result_array = array();

            if ($c_hash != $g_hash) {
                $result_array['is_error']      = 1;
                $result_array['error_message'] = 'hash sequence is not valid !!';
                return Response()->json($result_array);
            }

            if (is_string($updated_items)) {
                $new_items = json_decode($updated_items, true);
            } else {
                $new_items = $updated_items;
            }

            $order = FnbOrders::whereFoIsDeleted(0)
                ->where('fo_id', $order_id)
                ->where('fk_warehouse_id', $warehouse_id)
                ->where('fo_store_id', $store_id)
                ->first();

            // update customer
            if ($order) {

                if (!empty($customer_id) && $customer_id > 0) {
                    $customer = Customers::find($customer_id);

                    if ($customer) {
                        if (!empty($delcustomername)) {
                            $customer->ic_customer_name = $delcustomername;
                        }
                        if (!empty($delcustomerphone)) {
                            $customer->ic_customer_phone  = $delcustomerphone;
                            $customer->ic_customer_mobile = $delcustomerphone;
                        }
                        if (!empty($delcustomeraddress)) {
                            $customer->ic_customer_address = $delcustomeraddress;
                        }
                        if (!empty($customer_type)) {
                            $customer->ic_customer_type = $customer_type;
                        }
                        $customer->save();

                        $order->fo_customer_id = $customer->ic_id;
                    }
                } else if (!empty(trim($delcustomername))) {
                    $customer = new Customers();
                    $customer->ic_customer_name    = $delcustomername;
                    $customer->ic_customer_phone   = $delcustomerphone;
                    $customer->ic_customer_mobile  = $delcustomerphone;
                    $customer->ic_customer_address = $delcustomeraddress;
                    $customer->ic_customer_code    = rand(10000, 99999);
                    $customer->ic_customer_type    = $customer_type;
                    $customer->save();

                    $order->fo_customer_id = $customer->ic_id;
                } else {
                    $order->fo_customer_id = null;
                }

                $order->save();
            }


            $old_items = FnbOrderItems::where('oi_order_id', $order_id)->where('oi_is_deleted', 0)->get();

            // [
            //     items_id -> quantity,
            //     ...
            // ]
            $old_items_quantities = $old_items
                ->groupBy('oi_item_id')
                ->map(fn($rows) => $rows->sum('oi_quantity'))
                ->toArray();

            $old_order_item_ids = $old_items->pluck('oi_item_id')->toArray();

            $old_modifiers = [];
            if (!empty($old_order_item_ids)) {
                $old_modifiers = FnbOrderItemModifiers::whereIn('im_item_id', $old_order_item_ids)
                    ->where('im_order_id', $order_id)
                    ->where('im_is_deleted', 0)
                    ->get();
            }

            //item_id -> modifier_id -> qty
            $old_modifier_quantity = [];

            foreach ($old_modifiers as $mod) {

                $modifier_id = $mod->im_modifier_id;
                $modifier = Modifier::find($modifier_id);
                $menu_item_id = $mod->im_item_id;

                if (!isset($old_modifier_quantity[$menu_item_id])) {
                    $old_modifier_quantity[$menu_item_id] = [];
                }

                if (!isset($old_modifier_quantity[$menu_item_id][$modifier_id])) {
                    $old_modifier_quantity[$menu_item_id][$modifier_id] = 0;
                }

                $old_modifier_quantity[$menu_item_id][$modifier_id] += $mod->Modifier->m_quantity;
            }

            $new_items_quantity = [];

            foreach ($new_items as $item) {
                $item_id = $item['item_id'];
                $qty = $item['quantity'];

                if (!isset($new_items_quantity[$item_id])) {
                    $new_items_quantity[$item_id] = 0;
                }

                $new_items_quantity[$item_id] += $qty;
            }

            $new_modifier_quantity = [];

            foreach ($new_items as $item) {
                $item_id = $item['item_id'];
                $item_qty = $item['quantity'];

                if (empty($item['modifiers'])) {
                    continue;
                }

                foreach ($item['modifiers'] as $mod) {
                    $modifier_id = $mod['modifier_id'] ?? $mod['id'] ?? null;

                    $mod_qty = ($mod['quantity'] ?? 1);

                    $final_qty = $item_qty * $mod_qty;

                    if (!isset($new_modifier_quantity[$item_id])) {
                        $new_modifier_quantity[$item_id] = [];
                    }

                    if (!isset($new_modifier_quantity[$item_id][$modifier_id])) {
                        $new_modifier_quantity[$item_id][$modifier_id] = 0;
                    }

                    $new_modifier_quantity[$item_id][$modifier_id] += $final_qty;
                }
            }

            $all_item_ids = array_unique(array_merge(
                array_keys($old_items_quantities),
                array_keys($new_items_quantity)
            ));

            foreach ($all_item_ids as $item_id) {
                $old_qty = $old_items_quantities[$item_id] ?? 0;
                $new_qty = $new_items_quantity[$item_id] ?? 0;
                $delta = $new_qty - $old_qty;
                if ($delta > 0) {

                    $ingredients = FnbIngredients::where('in_item_id', $item_id)
                        ->where('in_is_deleted', 0)
                        ->where('in_is_active', 1)
                        ->get(['in_product_id', 'in_stock_quantity']);

                    $pending_kitchen_id = SystemStatus::where('ss_status_type', 'kitchen_order_statuses')
                        ->whereRaw('LOWER(ss_status_title) = ?', ['pending'])
                        ->value('ss_id');

                    foreach ($ingredients as $ing) {
                        $quantity_to_reduce = $ing->in_stock_quantity * $delta;
                        if ($quantity_to_reduce > 0) {
                            $this->reduceStock(
                                $ing->in_product_id,
                                $warehouse_id,
                                $quantity_to_reduce
                            );
                        }
                    }

                    $menu_item = FnbMenuItem::find($item_id);

                    $station_id = ($menu_item->mi_kitchen_station_id ?? 0);

                    if ($old_qty > 0) {

                        // ghayer l quantity
                        FnbOrderItems::where('oi_order_id', operator: $order_id)
                            ->where('oi_item_id', $item_id)
                            ->where('oi_is_deleted', 0)
                            ->update([
                                'oi_quantity' => $new_qty
                            ]);
                    } else {
                        // iza kenet kena 3amlin item jdide
                        FnbOrderItems::create([
                            'oi_order_id'       => $order_id,
                            'oi_item_id'        => $item_id,
                            'oi_quantity'       => $new_qty,
                            'oi_unit_price'     => $menu_item->mi_base_price ?? 0,
                            'oi_item_discount'  => 0,
                            'oi_notes'          => '',
                            'oi_station_id'     => $menu_item->mi_kitchen_station_id ?? 1,
                            'oi_kitchen_status' => $pending_kitchen_id,
                            'oi_currency_id'    => $order->fo_currency_id,
                            'oi_is_deleted'     => 0,
                            'oi_created_by'     => $user_id,
                        ]);
                    }


                    FnbPrintJobs::create([
                        'order_id' => $order_id,
                        'kitchen_station_id' => $station_id,
                        'payload' => json_encode([
                            'order' => [
                                'id'       => $order_id,
                                'code'     => FnbOrders::find($order_id)->fo_order_code,
                                'type'     => FnbOrders::find($order_id)->fo_order_type,
                                'datetime' => now(),
                            ],
                            'items' => [[
                                'qty'   => $delta,
                                'name'  => $menu_item->mi_item_name,
                                'notes' => '',
                            ]],
                        ]),
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } elseif ($delta < 0) {
                    $waste_qty = abs($delta);
                    $ingredients = FnbIngredients::where('in_item_id', $item_id)
                        ->where('in_is_deleted', 0)
                        ->where('in_is_active', 1)
                        ->get(['in_product_id', 'in_stock_quantity']);

                    $product_quantities = [];

                    foreach ($ingredients as $ing) {
                        $product_id = $ing->in_product_id;
                        $product_qty = ($ing->in_stock_quantity ?? 0);

                        if (!isset($product_quantities[$product_id])) {
                            $product_quantities[$product_id] = 0;
                        }
                        $product_quantities[$product_id] += $product_qty;
                    }

                    foreach ($product_quantities as $product_id => $qty_per_item) {

                        $final_waste_qty = $qty_per_item * $waste_qty;
                        $stock_id = Stocks::where('fk_product_id', $product_id)
                            ->where('fk_warehouse_id', $warehouse_id)
                            ->value('is_id');

                        FnbWasteStock::create([
                            'fk_product_id'   => $product_id,
                            'fk_stock_id'     => $stock_id,
                            'fk_warehouse_id' => $warehouse_id,
                            'ws_quantity'     => $final_waste_qty,
                            'ws_date'         => now()->toDateString(),
                            'ws_created_by'   => $user_id,
                            'ws_created_at'   => now(),
                        ]);


                        if ($new_qty > 0) {
                            FnbOrderItems::where('oi_order_id', $order_id)
                                ->where('oi_item_id', $item_id)
                                ->where('oi_is_deleted', 0)
                                ->update([
                                    'oi_quantity' => $new_qty
                                ]);
                        } else {
                            FnbOrderItems::where('oi_order_id', $order_id)
                                ->where('oi_item_id', $item_id)
                                ->where('oi_is_deleted', 0)
                                ->update([
                                    'oi_is_deleted' => 1,
                                    'oi_deleted_by' => $user_id
                                ]);
                        }
                    }
                } else {
                }
            }


            $all_modifiers_qty = array_unique(array_merge(
                array_keys($old_modifier_quantity),
                array_keys($new_modifier_quantity)
            ));

            foreach ($all_modifiers_qty as $item_id) {

                $orderItem = FnbOrderItems::where('oi_order_id', $order_id)
                    ->where('oi_item_id', $item_id)
                    ->where('oi_is_deleted', 0)
                    ->first();


                if (!$orderItem) {
                    continue;
                }

                $menu_item = FnbMenuItem::find($orderItem->oi_item_id);
                $station_id = $menu_item->mi_kitchen_station_id ?? 0;


                $old_mods = $old_modifier_quantity[$item_id] ?? [];
                $new_mods = $new_modifier_quantity[$item_id] ?? [];

                $all_modifier_ids = array_unique(array_merge(
                    array_keys($old_mods),
                    array_keys($new_mods)
                ));

                foreach ($all_modifier_ids as $modifier_id) {
                    $oldQty = $old_mods[$modifier_id] ?? 0;
                    $newQty = $new_mods[$modifier_id] ?? 0;
                    $delta  = $newQty - $oldQty;

                    $modifier = Modifier::find($modifier_id);
                    if ($delta > 0) {

                        FnbOrderItemModifiers::create([
                            'im_item_id' => $item_id,
                            'im_order_id' => $order_id,
                            'im_modifier_id' => $modifier->m_id,
                            'im_modifier_name' => $modifier->m_modifier_name,
                            'im_modifier_cost' => $modifier->m_cost_modifier,
                            'im_quantity' => $modifier->m_quantity * $delta
                        ]);

                        $qty_to_reduce = $modifier->m_quantity;

                        if ($qty_to_reduce > 0) {
                            $this->reduceStock(
                                $modifier->m_item_id,
                                $warehouse_id,
                                $qty_to_reduce
                            );
                        }

                        FnbPrintJobs::create([
                            'order_id' => $order_id,
                            'kitchen_station_id' => $station_id,
                            'payload' => json_encode([
                                'order' => [
                                    'id'   => $order_id,
                                    'code' => $order->fo_order_code,
                                ],
                                'items' => [[
                                    'qty'  => $delta,
                                    'name' => '+ ' . ($modifier->m_modifier_name ?? 'Modifier'),
                                    'notes' => '',
                                ]],
                            ]),
                            'status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } elseif ($delta < 0) {
                        if ($newQty > 0) {
                            $removeCount = $oldQty - $newQty;
                        } else {
                            $removeCount = $oldQty;
                        }
                        if ($removeCount > 0) {
                            FnbOrderItemModifiers::where('im_item_id', $item_id)
                                ->where('im_modifier_id', $modifier_id)
                                ->where('im_order_id', $order_id)
                                ->where('im_is_deleted', 0)
                                ->update([
                                    'im_quantity' => $newQty,
                                    'im_is_deleted' => ($newQty <= 0 ? 1 : 0),
                                ]);
                        }

                        if ($modifier && $modifier->m_item_id > 0) {
                            $productId = (int) $modifier->m_item_id;

                            if ($removeCount > 0) {
                                $stockId = Stocks::where('fk_product_id', $modifier->m_item_id)
                                    ->where('fk_warehouse_id', $warehouse_id)
                                    ->value('is_id');


                                FnbWasteStock::create([
                                    'fk_product_id'   => $productId,
                                    'fk_stock_id'     => $stockId,
                                    'fk_warehouse_id' => $warehouse_id,
                                    'ws_quantity'     => $removeCount,
                                    'ws_date'         => now()->toDateString(),
                                    'ws_created_by'   => $user_id,
                                    'ws_created_at'   => now(),
                                ]);
                            }
                        }
                    } else {
                    }
                }
            }

            return response()->json([
                'is_error' => 0,
                'message' => 'Order edited successfully',
            ]);
        } else {

            // create order
            $g_hash   = $request->input('g_hash');
            $order_items = $request->input('order_items');
            $order_code = null;

            if (is_array($order_items)) {
            } else if (is_string($order_items)) {
                $order_items = json_decode($order_items, true);
            }




            $store_id = $request->input('store_id');
            $company_id = $request->input('company_id');
            $warehouse_id = $request->input('warehouse_id');
            $user_id = $request->input('user_id');
            $sub_total = $request->input('sub_total');
            $total = $request->input('total');

            $sub_total_display = $request->input('sub_total_display', $sub_total);
            $total_display     = $request->input('total_display', $total);

            $discount = $request->input('discount');

            $rate = (float) $request->input('currency_display_rate', 1);


            $order_type = $request->input('order_type');
            $customer_id = $request->input('customer_id');
            $display_currency_id = (int) $request->input('currency_display_id');

            if (!$display_currency_id || !Currency::find($display_currency_id)) {
                return response()->json([
                    'is_error' => 1,
                    'error_msg' => 'Invalid or missing currency_id'
                ]);
            }

            $delcustomername          = $request->input('delcustomername');
            $delcustomerphone          = $request->input('delcustomerphone');
            $delcustomeraddress          = $request->input('delcustomeraddress');
            $customer_type         = $request->input('customer_type');
            $delivery_id          = strlen($delcustomername) > 0 ? 1 : 0;
            $customer_info = null;
            $table_ids = $request->input('table_id');
            $table_ids = array_filter(explode(",", $table_ids));
            $order_id   = $request->input('order_id');
            $isDineIn   = ($order_type === 'dine_in');


            $user_info = Users::find($user_id);

            $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
            $c_hash = hash('sha256', $c_hash);
            $result_array = array();

            if ($c_hash != $g_hash) {
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = 'hash sequence is not valid !!';
                return Response()->json($result_array);
            }


            if ($order_type === 'takeaway') {

                if ($customer_id > 0) {
                    $customer_info = Customers::find($customer_id);
                    if ($customer_info) {
                        $customer_info->ic_customer_name    = $delcustomername;
                        $customer_info->ic_customer_address = $delcustomeraddress;
                        $customer_info->ic_customer_phone   = $delcustomerphone;
                        $customer_info->ic_customer_mobile  = $delcustomerphone;
                        $customer_info->ic_customer_type    = $customer_type;
                        $customer_info->save();
                    }
                } else if (!empty(trim($delcustomername))) {
                    $customer_info = new Customers();
                    $customer_info->ic_customer_name    = $delcustomername;
                    $customer_info->ic_customer_address = $delcustomeraddress;
                    $customer_info->ic_customer_phone   = $delcustomerphone;
                    $customer_info->ic_customer_mobile  = $delcustomerphone;
                    $customer_info->ic_customer_code    = rand(10000, 99999);
                    $customer_info->ic_customer_type    = $customer_type;
                    $customer_info->save();
                    $customer_id = $customer_info->ic_id;
                } else {
                    $customer_id   = null;
                    $customer_info = null;
                }
            }


            $company_info   = Companies::find($company_id);

            $creation_date = date("Y-m-d H:i:s");


            $pendingKitchenStatusId = $this->resolvePendingKitchenStatusId();

            if (!$pendingKitchenStatusId) {
                return response()->json([
                    'is_error' => 1,
                    'error_msg' => 'Kitchen Pending status is not configured'
                ]);
            }

            if ($isDineIn && $order_id > 0) {

                $order_info = FnbOrders::where('fo_id', $order_id)
                    ->where('fo_order_type', 'dine_in')
                    ->where('fo_payment_status', 'unpaid')
                    ->lockForUpdate()
                    ->first();

                if (!$order_info) {
                    return response()->json([
                        'is_error' => 1,
                        'error_msg' => 'Dine-in order not found or already finalized'
                    ]);
                }

                $order_code = $order_info->fo_order_code;
            } else {

                $order_info = new FnbOrders();
                if (empty($order_info->fo_order_code)) {
                    $order_info->fo_order_code = $this->GenerateOrdereCodeFNB();
                }

                $order_code = $order_info->fo_order_code;

                $order_info->fo_order_type = $order_type;
                $order_info->fo_store_id   = $store_id;
                $order_info->fo_branch_id  = $company_id;
            }


            $order_info->fo_customer_id = $customer_id;
            $order_info->fo_order_status = $this->resolveOrderStatusId('paid');
            $order_info->fo_kitchen_status = $pendingKitchenStatusId;
            $order_info->fo_order_datetime = $creation_date;
            $order_info->fo_subtotal = $sub_total_display;
            $order_info->fo_discount = $discount;
            $order_info->fo_created_by = $user_id;
            $order_info->fo_currency_id = $display_currency_id;
            $order_info->fo_total_amount = $total_display;
            $order_info->fo_payment_status = 'paid';
            $order_info->fo_paid_amount  = (float) $total_display;
            $order_info->fk_warehouse_id  = $warehouse_id;
            $order_info->save();

            $fo_id = $order_info->fo_id;

            // delete empty orders after merge
            if (!empty($table_ids)) {

                // find empty orders that do not have items (the orders that we should delete them)
                $orphanOrders = FnbOrders::where('fo_id', '!=', $fo_id)
                    ->where('fo_order_type', 'dine_in')
                    ->where('fo_payment_status', 'unpaid')
                    ->where('fo_is_deleted', 0)
                    ->where('fo_store_id', $store_id)
                    ->whereDoesntHave('Items', function ($q) {
                        $q->where('oi_is_deleted', 0);
                    })
                    ->orderByDesc('fo_id')
                    ->get();


                foreach ($orphanOrders as $orphan) {
                    $orphan->fo_is_deleted = 1;
                    $orphan->fo_deleted_by = $user_id;
                    $orphan->save();
                }
            }


            FnbOrderTables::where('ot_order_id', $fo_id)->delete(); // remove old links

            if (!empty($table_ids)) {
                foreach ($table_ids as $tid) {
                    FnbOrderTables::create([
                        'ot_order_id' => $fo_id,
                        'ot_table_id' => intval($tid)
                    ]);
                }
            }

            $display_currency_code = $request->input('currency_display_code');
            $display_rate          = floatval($request->input('currency_display_rate', 1));

            $currency = null;
            if ($display_currency_id) {
                $currency = Currency::find($display_currency_id);
            }
            if (!$currency && $display_currency_code) {
                $currency = Currency::where('cc_currency_code', $display_currency_code)->first();
            }
            if (!$currency) {
                $currency = Currency::find($order_info->fo_currency_id); // fallback
            }

            $final_items = [];

            foreach ($order_items as $key => $item_order) {
                $item = new FnbOrderItems();
                $item->oi_order_id = $fo_id;
                $item->oi_item_id = $item_order['item_id'];
                $item->oi_quantity = $item_order['quantity'];
                $item->oi_unit_price = $item_order['unit_price'] ?? $item_order['price'];
                $item->oi_item_discount = isset($item_order['discount']) ? $item_order['discount'] : 0;
                $item->oi_kitchen_status = $pendingKitchenStatusId;
                $menuItem = FnbMenuItem::find($item_order['item_id']);

                if (!$menuItem || !$menuItem->mi_kitchen_station_id) {
                    $fallbackStationId = 1;

                    \Log::warning(
                        'Menu item missing kitchen station, using fallback',
                        [
                            'menu_item_id' => $item_order['item_id'],
                            'fallback_station_id' => $fallbackStationId,
                        ]
                    );

                    $item->oi_station_id = $fallbackStationId;
                } else {
                    $item->oi_station_id = (int) $menuItem->mi_kitchen_station_id;
                }

                $item->oi_notes = isset($item_order['notes']) ? $item_order['notes'] : "";
                $item->oi_currency_id = $display_currency_id;
                $item->save();

                $item_db = FnbMenuItem::find($item_order['item_id']);

                $mods = [];

                if (!empty($item_order['modifiers'])) {
                    foreach ($item_order['modifiers'] as $m) {

                        if (!isset($m['id'])) {
                            continue;
                        }

                        $modifier = Modifier::find($m['id']);
                        if (!$modifier) {
                            continue;
                        }

                        FnbOrderItemModifiers::create([
                            'im_order_id' => $fo_id,
                            'im_item_id' => $item->oi_item_id,
                            'im_modifier_id' => $modifier->m_id,
                            'im_modifier_name' => $modifier->m_modifier_name,
                            'im_modifier_cost' => $modifier->m_cost_modifier,
                            'im_quantity' => $modifier->m_quantity * $item->oi_quantity
                        ]);


                        $mods[] = [
                            "id"    => $modifier->m_id,
                            "name"  => $modifier->m_modifier_name,
                            "price" => $modifier->m_price_modifier  * $display_rate,
                            "qty"   => $modifier->m_quantity,
                        ];
                    }
                }


                $unit_price_display = floatval($item_order['unit_price'] ?? $item_order['price']);
                $line_total_display = $unit_price_display * $item_order['quantity'];

                $final_items[] = [
                    'item_id'   => $item_order['item_id'],
                    'item_name' => $item_db ? $item_db->mi_item_name : '',
                    'quantity'  => $item_order['quantity'],
                    'price'     => $line_total_display,
                    'total'      => $line_total_display,
                    'unit_price' => $unit_price_display,
                    'modifiers' => $mods,
                ];
            }


            $structure = array(
                "fo_id" => $fo_id,
                "fo_code" => $order_info->fo_order_code,
                "items" => $order_items
            );

            $order_info->fo_order_structure = json_encode($structure);
            $order_info->save();



            $customer_info  = Customers::find($order_info->fo_customer_id);
            $AccountingManager = new AccountingManager();


            $lst_order_items = FnbOrderItems::where('oi_order_id', $fo_id)->get();



            $payment_type_info      = PaymentTypes::find(2);
            $pt_payment_account     = $payment_type_info->pt_payment_account;

            $AccTransaction = new Transactions();
            $AccTransaction->at_transaction_date    = date("Y-m-d");
            $AccTransaction->at_creation_date       = date("Y-m-d");
            $AccTransaction->at_accounting_doc      = $order_code;
            $AccTransaction->fk_acc_journal_id      = 3;
            $AccTransaction->save();
            $at_id = $AccTransaction->at_id;

            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $customer_info->ic_account_number ?? 0;
            $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number ?? 0;
            $TransactionMovement->tm_ledger_label       = $order_code;
            $TransactionMovement->tm_debit              = $total;
            $TransactionMovement->tm_credit             = 0;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            // $TransactionMovement->tm_currency_id        = $invoice_info->bi_invoice_currency;
            $TransactionMovement->save();


            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $customer_info->ic_account_number ?? 0;
            $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number ?? 0;
            // $TransactionMovement->tm_ledger_label       = $invoice_info->bi_invoice_code;
            $TransactionMovement->tm_debit              = 0;
            // $TransactionMovement->tm_credit             = $invoice_info->bi_total_price;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            // $TransactionMovement->tm_currency_id        = $invoice_info->bi_invoice_currency;
            $TransactionMovement->save();


            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $pt_payment_account;
            $TransactionMovement->tm_sub_ledger_account = $pt_payment_account;
            // $TransactionMovement->tm_ledger_label       = $invoice_info->bi_invoice_code;
            $TransactionMovement->tm_debit              = 0;
            // $TransactionMovement->tm_credit             = $invoice_info->bi_total_price;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            // $TransactionMovement->tm_currency_id        = $invoice_info->bi_invoice_currency;
            $TransactionMovement->save();

            $trans_mov = new TransactionMovements();
            $trans_mov->fk_tran_id              = $at_id;
            $trans_mov->tm_ledger_account       = 701;
            $trans_mov->tm_sub_ledger_account   = 701;
            $trans_mov->tm_debit                = 0;
            // $trans_mov->tm_credit               = $invoice_info->bi_total_price;
            $trans_mov->tm_creation_date        = date('Y-m-d');
            $trans_mov->tm_transaction_date        = date('Y-m-d');
            // $trans_mov->tm_currency_id          = $invoice_info->bi_invoice_currency;
            $trans_mov->tm_ledger_label         = "Credit Purchasing for Stock ";
            $trans_mov->save();




            foreach ($order_items as $item_order) {

                $item_id = $item_order['item_id'];

                $ingredients = FnbIngredients::where('in_item_id', $item_id)
                    ->where('in_is_deleted', 0)
                    ->get();

                foreach ($ingredients as $ing) {
                    $product_id = $ing->in_product_id;
                    $total_qty = $ing->in_stock_quantity * $item_order['quantity'];


                    $this->reduceStock($product_id, $warehouse_id, $total_qty);
                }

                if (!empty($item_order['modifiers'])) {
                    foreach ($item_order['modifiers'] as $mod) {

                        if (!isset($mod['id'])) {
                            continue;
                        }

                        $modifier = Modifier::find($mod['id']);
                        if (!$modifier) {
                            continue;
                        }

                        $product_id   = $modifier->m_item_id;
                        $qty_per_unit = $modifier->m_quantity * $item_order['quantity'];

                        $this->reduceStock($product_id, $warehouse_id, $qty_per_unit);
                    }
                }
            }


            $display_currency_code = $request->input('currency_display_code');
            $display_rate          = (float) $request->input('currency_display_rate', 1);

            $currency = null;

            if ($display_currency_id) {
                $currency = Currency::find($display_currency_id);
            }

            if (!$currency && $display_currency_code) {
                $currency = Currency::where('cc_currency_code', $display_currency_code)->first();
            }

            if (!$currency) {
                $currency = Currency::find($order_info->fo_currency_id); // fallback only
            }


            $data = array(
                "company_info" => $company_info,
                "creation_date" => $creation_date,
                "fo_order_code" => $order_code,
                "delivery_id" => $delivery_id,
                "customer_info" => $customer_info,
                "lst_order_items" => $final_items,
                "order_info" => $order_info,
                "sub_total" => $sub_total_display,
                "cost_total" => $total_display,
                "discount" => $discount,
                "currency" => $currency,
                "currency_display_code" => $request->input('currency_display_code'),
                "currency_display_rate" => (float) $request->input('currency_display_rate', 1),

            );

            $receipt_html = view('templates.fnbreceipt', $data)->render();

            $result_array['is_error'] = 0;
            $result_array['error_msg'] = "Order Saved";
            $result_array['receipt_html'] = $receipt_html;

            return Response()->json($result_array);
        }
    }


    public function CreateEmptyOrder(Request $request)
    {

        $g_hash = $request->input('g_hash');
        $store_id = $request->input('store_id');
        $user_id = $request->input('user_id');
        $company_id = $request->input('company_id');
        $user = Users::find($user_id);

        $check_hash = "POS567{$user->u_username}{$user->u_fullname}{$user->u_email}POS567";
        $check_hash = hash('sha256', $check_hash);

        if ($g_hash !== $check_hash) {
            return response()->json(['is_error' => 1, 'error_msg' => 'Invalid hash']);
        }

        $pendingKitchenStatusId = $this->resolvePendingKitchenStatusId();

        if (!$pendingKitchenStatusId) {
            return response()->json([
                'is_error' => 1,
                'error_msg' => 'Kitchen Pending status is not configured'
            ]);
        }

        $order = new FnbOrders();
        $order->fo_branch_id = $company_id;
        $order->fo_store_id = $store_id;
        $order->fo_order_code = $this->GenerateOrdereCodeFNB();
        $order->fo_order_status = $this->resolveOrderStatusId('pending');
        $order->fo_kitchen_status = $pendingKitchenStatusId;
        $order->fo_order_type = "dine_in";
        $order->fo_payment_status = "unpaid";
        $order->fo_customer_id    = null;
        $order->save();

        $order_id = $order->fo_id;

        return response()->json([
            'is_error' => 0,
            'order_id' => $order_id,
        ]);
    }


    public function UpdateOrder(Request $request)
    {


        $order = FnbOrders::find($request->order_id);
        if (!$order) {
            return response()->json(['is_error' => 1, 'error_msg' => 'Order not found']);
        }
        if (empty($order->fo_order_code)) {
            $order->fo_order_code = $this->GenerateOrdereCodeFNB();
            $order->save();
        }
        $pendingKitchenStatusId = SystemStatus::where('ss_status_type', 'kitchen_order_statuses')
            ->orderBy('ss_id')
            ->value('ss_id');


        $order->fo_order_type   = $request->order_type;
        $order->fo_customer_id  = $request->customer_id ?? 0;
        $order->fo_subtotal     = $request->sub_total;
        $order->fo_discount     = $request->discount;
        $order->fo_total_amount = $request->total;
        $order->fo_order_status = 2;
        $order->save();

        $pendingKitchenStatusId = SystemStatus::where('ss_status_type', 'kitchen_order_statuses')
            ->where('ss_status_title', 'Pending')
            ->value('ss_id');

        if (!$pendingKitchenStatusId) {
            throw new \Exception('Pending kitchen status not found');
        }

        $alreadySentToKitchen = FnbOrderItems::where('oi_order_id', $order->fo_id)
            ->whereNotNull('oi_kitchen_status')
            ->where('oi_kitchen_status', '!=', $pendingKitchenStatusId)
            ->exists();

        // tabless
        FnbOrderTables::where('ot_order_id', $order->fo_id)->delete();

        $tableIds = collect(
            is_array($request->table_ids)
                ? $request->table_ids
                : explode(',', (string) $request->table_ids)
        )
            ->map(fn($id) => (int) trim($id))
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values();

        foreach ($tableIds as $tableId) {
            FnbOrderTables::create([
                'ot_order_id' => $order->fo_id,
                'ot_table_id' => $tableId,
            ]);
        }


        // item
        FnbOrderItems::where('oi_order_id', $order->fo_id)
            ->update(['oi_is_deleted' => 1]);

        $items = json_decode($request->order_items, true);


        foreach ($items as $it) {

            $menuItem = FnbMenuItem::find($it['item_id']);

            if (!$menuItem) {
                throw new \Exception("Menu item not found: " . $it['item_id']);
            }

            $data = [
                'oi_order_id'      => $order->fo_id,
                'oi_item_id'       => $it['item_id'],
                'oi_quantity'      => $it['quantity'],
                'oi_unit_price'    => $it['unit_price'],
                'oi_item_discount' => $it['discount'],
                'oi_notes'         => $it['notes'] ?? '',
                'oi_station_id'    => $menuItem->mi_kitchen_station_id,
            ];

            if (!$alreadySentToKitchen) {
                $data['oi_kitchen_status'] = $pendingKitchenStatusId;
            }

            FnbOrderItems::create($data);
        }

        if (!$alreadySentToKitchen) {
            $order->fo_kitchen_status = $pendingKitchenStatusId;
            $order->save();
        }

        $orderItems = FnbOrderItems::where('oi_order_id', $order->fo_id)->get();

        $grouped = [];

        foreach ($orderItems as $it) {

            if (!$it->oi_station_id) {

                $menuItem = FnbMenuItem::find($it->oi_item_id);

                if ($menuItem && $menuItem->mi_kitchen_station_id) {
                    $it->oi_station_id = (int) $menuItem->mi_kitchen_station_id;
                } else {
                    $it->oi_station_id = 1;
                    \Log::warning(
                        'Order item missing kitchen station, forced fallback in UpdateOrder',
                        [
                            'order_item_id' => $it->oi_id,
                            'menu_item_id'  => $it->oi_item_id,
                            'fallback_station_id' => 1,
                        ]
                    );
                }
                $it->save();
            }

            $stationId = (int) $it->oi_station_id;

            if (!isset($grouped[$stationId])) {
                $grouped[$stationId] = [];
            }

            $menu = FnbMenuItem::find($it->oi_item_id);

            $grouped[$stationId][] = [
                'qty'   => $it->oi_quantity,
                'name'  => $menu?->mi_item_name ?? 'Unknown',
                'notes' => $it->oi_notes,
            ];
        }

        if (count($grouped) === 0) {
            throw new \Exception("No items grouped for printing");
        }
        DB::table('fnb_print_jobs')
            ->where('order_id', $order->fo_id)
            ->whereIn('status', ['pending', 'processing'])
            ->delete();

        foreach ($grouped as $stationId => $items) {
            if (empty($items)) {
                continue;
            }

            FnbPrintJobs::create([

                'order_id' => $order->fo_id,
                'kitchen_station_id' => $stationId,
                'payload' => json_encode([
                    'order' => [
                        'id'       => $order->fo_id,
                        'code'     => $order->fo_order_code,
                        'type'     => $order->fo_order_type,
                        'datetime' => $order->fo_order_datetime,
                    ],
                    'items' => $items,
                ]),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        return response()->json(['is_error' => 0]);
    }


    public function SyncPendingOrders(Request $request)
    {
        $storeId = $request->store_id;

        $todayStart = Carbon::today()->startOfDay();
        $todayEnd   = Carbon::today()->endOfDay();

        $orders = FnbOrders::query()
            ->join('sys_status', 'sys_status.ss_id', '=', 'fnb_orders.fo_order_status')
            ->where('fnb_orders.fo_store_id', $storeId)
            ->whereRaw('LOWER(sys_status.ss_status_title) LIKE ?', ['%pos%']) // Pending / pending
            ->whereBetween('fnb_orders.fo_order_datetime', [$todayStart, $todayEnd])
            ->where('fnb_orders.fo_payment_status', '!=', 'paid')
            ->where('fnb_orders.fo_is_deleted', 0)
            ->select('fnb_orders.*')
            ->get();

        $result = [];

        foreach ($orders as $order) {

            $tables = FnbOrderTables::where('ot_order_id', $order->fo_id)
                ->pluck('ot_table_id')
                ->toArray();

            $structure = json_decode($order->fo_order_structure, true);

            if (!is_array($structure) || empty($structure['items'])) {
                continue;
            }

            $items = [];

            foreach ($structure['items'] as $it) {

                $items[] = [
                    'item_id'    => (int) $it['item_id'],
                    'qty'        => (float) $it['quantity'],
                    'unit_price' => (float) ($it['unit_price'] ?? $it['price']),
                    'notes'      => $it['notes'] ?? '',
                    'station_id' => null,
                    'modifiers'  => array_map(function ($m) {
                        return [
                            'id' => $m['id']
                        ];
                    }, $it['modifiers'] ?? []),
                ];
            }

            $result[] = [
                'order_id'   => $order->fo_id,
                'order_code' => $order->fo_order_code,
                'tables'     => $tables,
                'items'      => $items,
            ];
        }

        return response()->json([
            'is_error' => 0,
            'orders'   => $result
        ]);
    }


    public function ExportFnbOrders(Request $request)
    {
        $user_id      = $request->input('user_id');
        $g_hash       = $request->input('g_hash');
        $from         = $request->input('from');
        $to           = $request->input('to');

        $user_info    = Users::find($user_id);
        $result_array = array();

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";

        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            $result_array['is_error']      = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $orders = FnbOrders::whereBetween('fo_order_datetime', [$from, $to])
            ->select([
                'fo_order_code',
                'fo_order_type',
                'fo_order_status',
                'fo_subtotal',
                'fo_order_datetime'
            ])
            ->orderBy('fo_order_datetime', 'ASC')
            ->get();

        $headings = [
            'Order Code',
            'Order Type',
            'Order Status',
            'Subtotal',
            'Order DateTime'
        ];

        return Excel::download(
            new OrdersExport($orders, $headings),
            'fnb_orders_' . date('Ymd_His') . '.xlsx'
        );
    }

    public function GetOrdersBetweenOpenCloseCash(Request $request)
    {
        $user_id      = $request->input('user_id');
        $g_hash       = $request->input('g_hash');
        $user_info    = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";

        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']      = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $shift = FnbPosShift::where('ps_cashier_id', $user_id)
            ->where('ps_status', 'CLOSED')
            ->orderBy('ps_closed_at', 'DESC')
            ->first();

        if (!$shift) {
            $result_array['is_error']      = 1;
            $result_array['error_message'] = 'No closed shift found';
            return Response()->json($result_array);
        }

        $open_cash  = $shift->ps_opened_at;
        $close_cash = $shift->ps_closed_at;

        $lst_orders = FnbOrders::whereBetween('fo_order_datetime', [
            $open_cash,
            $close_cash
        ])->get();

        $result_array['is_error']   = 0;
        $result_array['open_cash'] = $open_cash;
        $result_array['close_cash'] = $close_cash;
        $result_array['orders']    = $lst_orders;

        return Response()->json($result_array);
    }


    public function GetOrderByCode(Request $request)
    {
        $user_id      = $request->input('user_id');
        $g_hash       = $request->input('g_hash');
        $user_info    = Users::find($user_id);
        $order_code = $request->input('order_code');

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";

        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']      = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $order = FnbOrders::where('fo_order_code', $order_code)->first();
        $tables = FnbOrderTables::where('ot_order_id', $order->fo_id)
            ->pluck('ot_table_id')
            ->toArray();


        if (!$order) {
            return response()->json(['is_error' => 1, 'error_msg' => 'Order not found']);
        }

        $items = FnbOrderItems::where('oi_order_id', $order->fo_id)->where('oi_is_deleted', 0)
            ->join('fnb_menu_items', 'fnb_menu_items.mi_id', '=', 'fnb_order_items.oi_item_id')
            ->select(
                'fnb_order_items.*',
                'fnb_menu_items.mi_item_name'
            )
            ->get();

        $items_formated = $items->map(function ($it) use ($order) {
            $mods = FnbOrderItemModifiers::where('im_item_id', $it->oi_item_id)
                ->where('im_is_deleted', 0)
                ->where('im_order_id', $order->fo_id)
                ->get()
                ->groupBy('im_modifier_id')
                ->map(function ($rows, $modifierId) {
                    $modifier = Modifier::where('m_id', $modifierId)->first();

                    return [
                        'modifier_id' => $modifierId,
                        'name' => $modifier->m_modifier_name ?? '',
                        'price' => $modifier->m_cost_modifier ?? 0,

                        'quantity' => $modifier->m_quantity,
                    ];
                })
                ->values();



            return [
                'item_id'    => $it->oi_item_id,
                'item_name'  => $it->mi_item_name,
                'quantity'   => $it->oi_quantity,
                'unit_price' => $it->oi_unit_price,
                'notes'      => $it->oi_notes,
                'station_id' => $it->oi_station_id,
                'modifiers'  => $mods,
            ];
        });

        $customer = null;

        if ($order->fo_customer_id) {
            $c = Customers::where('ic_id', $order->fo_customer_id)
                ->where('ic_is_deleted', 0)
                ->first();

            if ($c) {
                $customer = [
                    'customer_id' => $c->ic_id,
                    'name'        => $c->ic_customer_name,
                    'phone'       => $c->ic_customer_mobile,
                    'address'     => $c->ic_customer_address,
                ];
            }
        }




        return response()->json([
            'is_error' => 0,
            'order' => [
                'order_id' => $order->fo_id,
                'order_code' => $order->fo_order_code,
                'order_type' => $order->fo_order_type,
                'table_ids'  => $tables,
                'items' => $items_formated,
                'customer'   => $customer,

            ]
        ]);
    }

    public function ReprintOrderReceipt(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');
        $order_code = $request->input('order_code');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";

        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']      = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $order = FnbOrders::where('fo_order_code', $order_code)->first();

        if (!$order) {
            return response()->json([
                'is_error' => 1,
                'error_msg' => 'Order not found'
            ]);
        }

        $items = FnbOrderItems::where('oi_order_id', $order->fo_id)
            ->join('fnb_menu_items', 'fnb_menu_items.mi_id', '=', 'fnb_order_items.oi_item_id')
            ->select(
                'fnb_order_items.oi_item_id',
                'fnb_menu_items.mi_item_name as item_name',
                'fnb_order_items.oi_quantity',
                'fnb_order_items.oi_unit_price',
                'fnb_order_items.oi_notes'
            )
            ->get()
            ->map(function ($it) {
                return [
                    'item_id'   => $it->oi_item_id,
                    'item_name' => $it->item_name,
                    'quantity'  => $it->oi_quantity,
                    'price'     => $it->oi_unit_price * $it->oi_quantity,
                    'unit_price' => $it->oi_unit_price,
                    'notes'     => $it->oi_notes,
                    'modifiers' => [],
                ];
            })
            ->toArray();

        $company_info  = Companies::find($order->fo_branch_id);
        $customer_info = Customers::find($order->fo_customer_id);
        $currency      = Currency::find($order->fo_currency_id);

        $data = [
            "company_info" => $company_info,
            "creation_date" => $order->fo_order_datetime,
            "fo_order_code" => $order->fo_order_code,
            "delivery_id" => 0,
            "customer_info" => $customer_info,
            "lst_order_items" => $items,
            "order_info" => $order,
            "sub_total" => $order->fo_subtotal,
            "cost_total" => $order->fo_total_amount,
            "discount" => $order->fo_discount,
            "currency" => $currency,
            "currency_display_code" => $currency?->cc_currency_code ?? '',
            "currency_display_rate" => 1,
        ];

        $receipt_html = view('templates.fnbreceipt', $data)->render();

        return response()->json([
            'is_error' => 0,
            'receipt_html' => $receipt_html
        ]);
    }

    public function GetListOfOrders(Request $request)
    {
        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            $result_array['is_error']      = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $date_from    = $request->input('date_from');
        $date_to      = $request->input('date_to');
        $warehouse_id = $request->input('warehouse_id');
        $filter = $request->input('filter');

        if ($filter === 'today') {
            $date_from = date('Y-m-d');
            $date_to   = date('Y-m-d');
        }

        if ($filter === 'yesterday') {
            $date_from = date('Y-m-d', strtotime('-1 day'));
            $date_to   = $date_from;
        }

        if ($filter === 'lastweek') {
            $date_from = date('Y-m-d', strtotime('monday last week'));
            $date_to   = date('Y-m-d', strtotime('sunday last week'));
        }

        if ($filter === 'lastmonth') {
            $date_from = date('Y-m-01', strtotime('last month'));
            $date_to   = date('Y-m-t', strtotime('last month'));
        }


        $query = FnbOrders::query()
            ->leftJoin('currency as c', 'c.cc_id', '=', 'fnb_orders.fo_currency_id')
            ->leftJoin('inventory_warehouses as iw', 'iw.w_id', '=', 'fnb_orders.fk_warehouse_id')
            ->where('fnb_orders.fo_is_deleted', 0)
            ->where('fnb_orders.fo_payment_status', 'paid');

        if (!empty($warehouse_id)) {
            $query->where('fnb_orders.fk_warehouse_id', $warehouse_id);
        }


        if (!empty($date_from)) {
            $query->where('fnb_orders.fo_order_datetime', '>=', $date_from . ' 00:00:00');
        }

        if (!empty($date_to)) {
            $query->where('fnb_orders.fo_order_datetime', '<=', $date_to . ' 23:59:59');
        }

        $lst_orders = $query
            ->orderBy('fnb_orders.fo_order_datetime', 'DESC')
            ->select(
                'fnb_orders.fo_id',
                'fnb_orders.fo_order_code',
                'fnb_orders.fo_total_amount',
                'fnb_orders.fo_order_datetime',
                'fnb_orders.fk_warehouse_id',
                'fnb_orders.fo_currency_id',
                'c.cc_currency_code',
                'iw.w_warehouse_name'
            )
            ->get();

        $orders_array = [];

        foreach ($lst_orders as $index => $order) {
            $orders_array[$index] = [
                'fo_id'             => $order->fo_id,
                'fo_order_code'     => $order->fo_order_code,
                'fo_total_amount'   => $order->fo_total_amount,
                'fo_order_datetime' => $order->fo_order_datetime,
                'warehouse_id'      => $order->fk_warehouse_id,
                'warehouse_name'    => $order->w_warehouse_name,
                'currency_code'     => $order->cc_currency_code == null ? 'USD' : $order->cc_currency_code,
            ];
        }

        return response()->json([
            'is_error'   => 0,
            'error_msg'  => '',
            'lst_orders' => $orders_array,
        ]);
    }

    public function GetPendingOrders(Request $request)
    {
        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return response()->json([
                "is_error" => 1,
                "error_msg" => "hash sequence is not valid !!"
            ]);
        }

        $orders = FnbOrders::where('fnb_orders.fo_is_deleted', 0)
            ->whereIn('fnb_orders.fo_order_status', function ($q) {
                $q->select('ss_id')
                    ->from('sys_status')
                    ->whereRaw('LOWER(ss_status_title) LIKE ?', ['p%'])
                    ->where('ss_status_type', 'like', '%pos%')
                    ->where('ss_is_deleted', 0);
            })

            ->leftJoin(
                'sys_status as order_status',
                'order_status.ss_id',
                '=',
                'fnb_orders.fo_kitchen_status'
            )
            ->select(
                'fnb_orders.fo_id',
                'fnb_orders.fo_order_code',
                'fnb_orders.fo_order_type',
                'fnb_orders.fo_table_id',
                'fnb_orders.fo_creation_date',
                'fnb_orders.fo_kitchen_status',
                'order_status.ss_status_title as order_status_title'
            )
            ->orderBy('fnb_orders.fo_creation_date')
            ->get();

        foreach ($orders as $order) {
            $order->items = FnbOrderItems::where('oi_order_id', $order->fo_id)
                ->where('oi_is_deleted', 0)
                ->leftJoin('fnb_menu_items', 'fnb_menu_items.mi_id', '=', 'fnb_order_items.oi_item_id')
                ->leftJoin(
                    'sys_status as item_status',
                    'item_status.ss_id',
                    '=',
                    'fnb_order_items.oi_kitchen_status'
                )
                ->select(
                    'oi_id',
                    'oi_quantity',
                    'oi_notes',
                    'oi_station_id',
                    'oi_kitchen_status',
                    'item_status.ss_status_title',
                    'fnb_menu_items.mi_item_name'
                )
                ->get();
        }

        return response()->json([
            "is_error" => 0,
            "lst_pending_orders" => $orders
        ]);
    }

    /**
     * Api to return order
     *
     * @author Mohamad Kheiredine
     * @access public
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ReturnOrder(Request $request)
    {
        $g_hash     = $request->input('g_hash');
        $user_id    = (int) $request->input('user_id');
        $order_code = trim((string) $request->input('order_code'));
        $reason     = (string) $request->input('reason', '');

        $user_info = Users::find($user_id);
        if (!$user_info) {
            return response()->json(["is_error" => 1, "error_msg" => "User not found"]);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash !== $g_hash) {
            return response()->json(["is_error" => 1, "error_msg" => "hash sequence is not valid !!"]);
        }

        if ($order_code === '') {
            return response()->json(["is_error" => 1, "error_msg" => "order_code is required"]);
        }

        DB::beginTransaction();

        try {
            $now = Carbon::now();

            $order = FnbOrders::where('fo_order_code', $order_code)
                ->where('fo_is_deleted', 0)
                ->lockForUpdate()
                ->first();

            if (!$order) {
                DB::rollBack();
                return response()->json(['is_error' => 1, 'error_msg' => 'Order not found']);
            }

            if ((int) $order->fo_returned === 1) {
                DB::rollBack();
                return response()->json(['is_error' => 1, 'error_msg' => 'Order already returned']);
            }

            $warehouse_id = (int) $order->fk_warehouse_id;

            // menu items
            $orderItems = FnbOrderItems::where('oi_order_id', $order->fo_id)
                ->where('oi_is_deleted', 0)
                ->get();

            if ($orderItems->isEmpty()) {
                DB::rollBack();
                return response()->json(['is_error' => 1, 'error_msg' => 'Order has no items']);
            }

            $wasteByProduct = [];

            foreach ($orderItems as $oi) {

                $menuItemId = (int) $oi->oi_item_id;
                $itemQty    = (float) $oi->oi_quantity;

                $ingredients = FnbIngredients::where('in_item_id', $menuItemId)
                    ->where('in_is_deleted', 0)
                    ->get();

                foreach ($ingredients as $ing) {
                    $productId  = (int) $ing->in_product_id;
                    $perItemQty = (float) $ing->in_stock_quantity;

                    $need = $itemQty * $perItemQty;
                    if ($need <= 0) continue;

                    $wasteByProduct[$productId] =
                        ($wasteByProduct[$productId] ?? 0) + $need;
                }

                // jib l products mn l modifiers
                $orderModifiers = DB::table('fnb_order_item_modifiers AS oim')
                    ->join('fnb_menu_item_modifiers AS mim', function ($join) use ($menuItemId) {
                        $join->on('mim.fk_modifier_id', '=', 'oim.im_modifier_id')
                            ->where('mim.fk_menu_item_id', '=', $menuItemId);
                    })
                    ->where('oim.im_item_id', $oi->oi_id)
                    ->where('oim.im_is_deleted', 0)
                    ->where('mim.im_is_deleted', 0)
                    ->where('oim.im_modifier_type', 'add')

                    ->where('mim.im_product_id', '>', 0)

                    ->select(
                        'mim.im_product_id',
                        DB::raw('COUNT(*) as modifier_qty')
                    )
                    ->groupBy('mim.im_product_id')
                    ->get();


                foreach ($orderModifiers as $mod) {

                    $productId  = (int) $mod->im_product_id;
                    $perItemQty = (float) $mod->modifier_qty;

                    if ($perItemQty <= 0) {
                        continue;
                    }

                    $need = $itemQty * $perItemQty;

                    if ($need <= 0) {
                        continue;
                    }

                    $wasteByProduct[$productId] =
                        ($wasteByProduct[$productId] ?? 0) + $need;
                }
            }

            if (empty($wasteByProduct)) {
                DB::rollBack();
                return response()->json([
                    'is_error' => 1,
                    'error_msg' => 'No product waste could be calculated'
                ]);
            }

            foreach ($wasteByProduct as $productId => $totalWasteQty) {

                $remaining = (float) $totalWasteQty;

                $stocks = Stocks::where('fk_product_id', $productId)
                    ->where('fk_warehouse_id', $warehouse_id)
                    ->where('is_is_deleted', 0)
                    ->orderBy('is_id', 'asc')   // FIFO
                    ->lockForUpdate()
                    ->get();

                if ($stocks->isEmpty()) {
                    throw new \Exception("No stock rows for product {$productId}");
                }

                foreach ($stocks as $stock) {
                    if ($remaining <= 0) break;

                    $available = (float) $stock->is_quanity;
                    if ($available <= 0) continue;

                    $consume = min($available, $remaining);

                    FnbWasteStock::create([
                        'fk_product_id'   => $productId,
                        'fk_stock_id'     => $stock->is_id,
                        'fk_warehouse_id' => $warehouse_id,
                        'ws_quantity'     => $consume,
                        'ws_date'         => $now->toDateString(),
                        'ws_created_by'   => $user_id,
                        'ws_created_at'   => $now,
                    ]);

                    $remaining -= $consume;
                }

                if ($remaining > 0) {
                    throw new \Exception(
                        "Insufficient stock to register waste for product {$productId}"
                    );
                }
            }

            $order->fo_returned        = 1;
            $order->fo_returned_date   = $now;
            $order->fo_returned_reason = $reason;
            $order->save();

            DB::commit();

            return response()->json([
                'is_error' => 0,
                'message' => 'Order returned successfully (products wasted, stock not restored)',
                'order_id' => $order->fo_id,
                'order_code' => $order->fo_order_code,
                'warehouse_id' => $warehouse_id,
                'waste_products_count' => count($wasteByProduct),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'is_error' => 1,
                'error_msg' => 'Return order failed',
                'debug' => $e->getMessage(),
            ]);
        }
    }
}
