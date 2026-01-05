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
use Maatwebsite\Excel\Facades\Excel;

class FnbOrderController extends Controller
{


    public function GenerateOrdereCodeFNB()
    {
        return DB::transaction(function () {
            $year = date("Y");
            $last = FnbOrders::whereYear('fo_order_datetime', $year)
                ->orderBy('fo_id', 'desc')
                ->lockForUpdate()
                ->first();

            $lastNumber = 0;

            if ($last && !empty($last->fo_order_code)) {
                $lastNumber = intval(substr($last->fo_order_code, 3));
            }

            $next = $lastNumber + 1;

            return "ORD" . sprintf('%04d', $next);
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

    public function CreateOrder(Request $request)
    {
        $g_hash   = $request->input('g_hash');
        $order_items = $request->input('order_items');

        // If POSTMAN sends array → use it as is
        if (is_array($order_items)) {
        }
        // If POS sends JSON string → decode it
        else if (is_string($order_items)) {
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

        $order_code = $this->GenerateOrdereCodeFNB();

        $company_info   = Companies::find($company_id);

        $creation_date = date("Y-m-d H:i:s");

        $order_info = new FnbOrders();
        $order_info->fo_order_code = $order_code;
        $order_info->fo_order_type = $order_type;
        $order_info->fo_store_id = $store_id;
        $order_info->fo_branch_id = $company_id;
        $order_info->fo_customer_id = $customer_id;
        $order_info->fo_order_status = 5;
        $order_info->fo_order_datetime = $creation_date;
        $order_info->fo_subtotal = $sub_total_display;
        $order_info->fo_discount = $discount;
        $order_info->fo_created_by = $user_id;
        $order_info->fo_currency_id = $display_currency_id;
        $order_info->fo_total_amount = $total_display;
        $order_info->fo_payment_status = 'paid';
        $order_info->fo_paid_amount  = (float) $total_display;
        $order_info->save();

        $fo_id = $order_info->fo_id;

        FnbOrderTables::where('ot_order_id', $fo_id)->delete(); // remove old links (if editing in future)

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
            $item->oi_unit_price = $item_order['price'];
            $item->oi_item_discount = isset($item_order['discount']) ? $item_order['discount'] : 0;
            $item->oi_kitchen_status = 0;
            $item->oi_station_id = isset($item_order['station_id']) ? $item_order['station_id'] : 1;
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

                    $order_mod = new FnbOrderItemModifiers();
                    $order_mod->im_item_id = $item->oi_id;
                    $order_mod->im_modifier_id  = $modifier->m_id;
                    $order_mod->im_modifier_cost        = $modifier->m_cost_modifier;
                    $order_mod->save();

                    $mods[] = [
                        "id"    => $modifier->m_id,
                        "name"  => $modifier->m_modifier_name,
                        "price" => $modifier->m_price_modifier * $display_rate,
                        "qty"   => 1,
                    ];
                }
            }


            $unit_price_display = floatval($item_order['price']);
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
            "fo_code" => $order_code,
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
                $qty_per_unit = $ing->in_stock_quantity;

                // dd($qty_per_unit, $product_id);

                $this->reduceStock($product_id, $warehouse_id, $qty_per_unit);
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
                    $qty_per_unit = $modifier->m_quantity;

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

    public function CreateEmptyOrder(Request $request)
    {
        $g_hash = $request->g_hash;
        $user = Users::find($request->user_id);

        $check_hash = "POS567{$user->u_username}{$user->u_fullname}{$user->u_email}POS567";
        $check_hash = hash('sha256', $check_hash);

        if ($g_hash !== $check_hash) {
            return response()->json(['is_error' => 1, 'error_msg' => 'Invalid hash']);
        }

        // Create new empty order
        $order = new FnbOrders();
        $order->fo_store_id = $request->input('store_id');
        $order->fo_order_status = 1; // pending
        $order->fo_order_type = "dine_in";
        $order->save();

        return response()->json([
            'is_error' => 0,
            'order_id' => $order->fo_id,
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

        $order->fo_order_type   = $request->order_type;
        $order->fo_customer_id  = $request->customer_id ?? 0;
        $order->fo_subtotal     = $request->sub_total;
        $order->fo_discount     = $request->discount;
        $order->fo_total_amount = $request->total;
        $order->fo_order_status = 2;
        $order->save();

        // TABLES
        FnbOrderTables::where('ot_order_id', $order->fo_id)->delete();
        foreach (explode(',', $request->table_ids) as $tid) {
            FnbOrderTables::create([
                'ot_order_id' => $order->fo_id,
                'ot_table_id' => $tid
            ]);
        }

        // ITEM
        FnbOrderItems::where('oi_order_id', $order->fo_id)->delete();
        $items = json_decode($request->order_items, true);

        foreach ($items as $it) {

            $menuItem = FnbMenuItem::find($it['item_id']);

            if (!$menuItem) {
                throw new \Exception("Menu item not found: " . $it['item_id']);
            }

            FnbOrderItems::create([
                'oi_order_id'        => $order->fo_id,
                'oi_item_id'         => $it['item_id'],
                'oi_quantity'        => $it['quantity'],
                'oi_unit_price'      => $it['unit_price'],
                'oi_item_discount'   => $it['discount'],
                'oi_notes'           => $it['notes'] ?? '',
                'oi_station_id'      => $menuItem->mi_kitchen_station_id,
                'oi_kitchen_status'  => 1,
            ]);
        }

        $orderItems = FnbOrderItems::where('oi_order_id', $order->fo_id)->get();

        $grouped = [];

        foreach ($orderItems as $it) {
            if (!$it->oi_station_id) {
                throw new \Exception("Order item {$it->oi_id} has no kitchen station");
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

        // SAFETY CHECK (VERY IMPORTANT)
        if (count($grouped) === 0) {
            throw new \Exception("No items grouped for printing");
        }

        // DELETE OLD PENDING JOBS FOR THIS ORDER
        DB::table('fnb_print_jobs')
            ->where('order_id', $order->fo_id)
            ->whereIn('status', ['pending', 'processing'])
            ->delete();

        // INSERT ONE JOB PER STATION
        foreach ($grouped as $stationId => $items) {
            if (empty($items)) {
                continue;
            }

            DB::table('fnb_print_jobs')->insert([
                'order_id' => $order->fo_id,
                'kitchen_station_id' => $stationId,
                'payload' => json_encode([
                    'order' => [
                        'id'       => $order->fo_id,
                        'code'     => $order->fo_order_code,
                        'type'     => $order->fo_order_type,
                        'datetime' => $order->fo_order_datetime,
                    ],
                    'items' => $items, // ONLY THIS STATION ITEMS
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
        $orders = FnbOrders::where('fo_store_id', $request->store_id)
            ->where('fo_order_status', 1)
            ->get();

        $data = [];

        foreach ($orders as $order) {
            $tables = FnbOrderTables::where('ot_order_id', $order->fo_id)
                ->pluck('ot_table_id')
                ->toArray() ?? [];

            $items = FnbOrderItems::where('oi_order_id', $order->fo_id)->get()->toArray();

            $data[] = [
                'order_id' => $order->fo_id,
                'tables' => $tables,
                'items' => $items,
            ];
        }

        return response()->json(['is_error' => 0, 'orders' => $data]);
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
}
