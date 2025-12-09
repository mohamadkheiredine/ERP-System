<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use App\library\AccountingManager;
use App\library\OrdersManager;
use App\models\Accounting\TransactionMovements;
use App\models\Accounting\Transactions;
use App\models\Billing\InvoiceProducts;
use App\models\Billing\Invoices;
use App\models\Billing\PaymentTypes;
use App\models\Billing\Receipts;
use App\Models\FnB\FnbIngredients;
use App\models\FnB\FnbItem;
use App\models\FnB\FnbMenuItem;
use App\models\FnB\FnbOrderItemModifiers;
use App\models\FnB\FnbOrderItems;
use App\models\FnB\FnbOrders;
use Illuminate\Http\Request;
use App\Models\System\Companies;
use App\Models\FnB\KitchenStations;
use App\models\FnB\KitchenStations as FnBKitchenStations;
use App\models\FnB\Modifier;
use App\models\FnB\Tables;
use App\models\Inventory\Customers;
use App\models\Inventory\Products;
use App\models\Inventory\StockIds;
use App\models\Inventory\Stocks;
use App\models\Sales\OrderStatus;
use App\models\Sales\Stores;
use App\models\Sales\StoreWarehouses;
use App\models\System\Currency;
use App\models\System\SystemStatus;
use Illuminate\Support\Facades\DB;

use Config;
use Maatwebsite\Excel\Concerns\ToArray;

class FnbOrdersController extends Controller
{
    public function index()
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $data = array(
            "lst_companies" => $lst_companies
        );
        return Response()->view('fnb.orders.fnb-orders', $data);
    }

    public function addOrder()
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_stores = Stores::wherePsIsDeleted(0)->get();

        $lst_tables = Tables::whereFtIsDeleted(0)->get();
        $lst_customers = Customers::whereIcIsDeleted(0)->get();
        $lst_currencies = Currency::get();
        $lst_order_status = SystemStatus::whereSsIsDeleted(0)->whereSsStatusType('pos_order_statuses')->get();
        $lst_kitchen_status = SystemStatus::whereSsIsDeleted(0)->whereSsStatusType('kitchen_order_statuses')->get();


        $OrderManager   = new OrdersManager();
        $order_code     = $OrderManager->GenerateFnbOrderCode();
        unset($OrderManager);

        $data = array(
            "lst_companies" => $lst_companies,
            "lst_stores" => $lst_stores,
            "lst_tables" => $lst_tables,
            "lst_customers" => $lst_customers,
            "lst_currencies" => $lst_currencies,
            "lst_order_status" => $lst_order_status,
            "order_code" => $order_code,
            "lst_kitchen_status" => $lst_kitchen_status,
        );
        return Response()->view('fnb.orders.addform', $data);
    }

    public function DisplayListOrders(Request $request)
    {
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        $ps_company_id = $request->input('ps_company_id');

        if ($page_number > 1)
            $skip = ($page_number - 1) * $nbr_rows_per_pages;
        else
            $skip = 0;


        $order_cond = FnbOrders::whereFoIsDeleted(0);

        if (!empty($ps_company_id) && $ps_company_id != 0) {
            $order_cond = $order_cond->where('fo_branch_id', $ps_company_id);
        }

        if (!empty($general_search)) {
            $order_cond->where('fo_order_type', 'LIKE', '%' . $general_search . '%');
        }

        $orders_count = $order_cond->count();

        $total_pages = ceil($orders_count / $nbr_rows_per_pages);
        $total_pages = intval($total_pages);

        $lst_orders = $order_cond->skip($skip)->take($nbr_rows_per_pages)->get();

        $data = array(
            "lst_orders" => $lst_orders,
        );

        $result_array = array();
        $result_array['display'] = view("fnb.orders.listorders", $data)->render();
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
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

    public function SaveOrderInfo(Request $request)
    {
        $fo_id = $request->input('fo_id');
        $fo_order_code = $request->input('fo_order_code');
        $fo_branch_id = $request->input('fo_branch_id');
        $fo_order_type = $request->input('fo_order_type');
        $fo_store_id = $request->input('fo_store_id');
        $fo_table_id = $request->input('fo_table_id');
        $fo_customer_id = $request->input('fo_customer_id');
        $fo_order_status = $request->input('fo_order_status');
        $fo_subtotal = $request->input('fo_subtotal');
        $fo_discount = $request->input('fo_discount');
        $fo_tax = $request->input('fo_tax');
        $fo_service_charge = $request->input('fo_service_charge');
        $fo_total_amount = $request->input('fo_total_amount');
        $fo_currency_id = $request->input('cc_id');
        $fo_payment_status = $request->input('fo_payment_status');
        $fo_notes = $request->input('fo_notes');
        $fo_paid_amount = $request->input('fo_paid_amount');
        $fo_is_paid = $request->input('fo_is_paid') ? 1 : 0;

        $result_array = array();
        $order_info_structure = array();

        $order_info = new FnbOrders();
        if ($fo_id != null) {
            $order_info = FnbOrders::find($fo_id);
        }

        $order_info->fo_order_code = $fo_order_code;
        $order_info->fo_order_type = $fo_order_type;
        $order_info->fo_store_id = $fo_store_id;
        $order_info->fo_table_id = $fo_table_id;
        $order_info->fo_customer_id = $fo_customer_id;
        $order_info->fo_order_status = $fo_order_status;
        $order_info->fo_subtotal = $fo_subtotal;
        $order_info->fo_discount = $fo_discount;
        $order_info->fo_tax = $fo_tax;
        $order_info->fo_service_charge = $fo_service_charge;
        $order_info->fo_total_amount = $fo_total_amount;
        $order_info->fo_currency_id = $fo_currency_id;
        $order_info->fo_payment_status = $fo_payment_status;
        $order_info->fo_notes = $fo_notes;
        $order_info->fo_branch_id = $fo_branch_id;
        $order_info->fo_paid_amount = $fo_paid_amount;
        $order_info->fo_is_paid = $fo_is_paid;


        if ($fo_is_paid == 1) {
            $lst_items = FnbOrderItems::where('oi_order_id', $fo_id)
                ->where('oi_is_deleted', 0)
                ->get();

            $items_array = [];

            foreach ($lst_items as $item) {

                // $item_info = $item->Item;
                // $item_name = $item_info ? $item_info->fi_item_name : "";

                $ingredients = FnbIngredients::where('in_item_id', $item->oi_item_id)
                    ->where('in_is_deleted', 0)
                    ->get()
                    ->map(function ($ing) {
                        return [
                            'id'        => $ing->in_id,
                            'name'      => $ing->in_ingredient_name,
                            'unit_cost' => $ing->in_cost_per_unit,
                            'qty'       => $ing->in_stock_quantity,
                            'currency'  => $ing->in_currency_id
                        ];
                    })
                    ->toArray();

                $modifiers = FnbOrderItemModifiers::where('im_item_id', $item->oi_item_id)
                    ->get()
                    ->map(function ($mod) {
                        return [
                            'id'      => $mod->im_id,
                            'name'    => $mod->im_modifier_name,
                            'type'    => $mod->im_modifier_type,
                            'cost'    => $mod->im_modifier_cost,
                            'currency' => $mod->im_currency_id
                        ];
                    })
                    ->toArray();

                $items_array[] = [
                    'id'        => $item->oi_id,
                    'item_id'   => $item->oi_item_id,
                    'quantity'  => $item->oi_quantity,
                    'unit_price' => $item->oi_unit_price,
                    'total'     => $item->oi_total_price,
                    'ingredients' => $ingredients,
                    'modifiers' => $modifiers,
                ];
            }

            $order_info_structure = [
                'fo_id'   => $fo_id,
                'fo_code' => $fo_order_code,
                'items'   => $items_array
            ];


            $order_info->fo_order_structure = json_encode($order_info_structure);
            $order_info->save();

            $storeWarehouse = StoreWarehouses::where('sw_store_id', $fo_store_id)->first();

            $warehouse_id = $storeWarehouse->sw_warehouse_id;

            $customer_info  = Customers::find($fo_customer_id);
            $AccountingManager = new AccountingManager();
            $default_company_id = session('default_company_id');

            $invoice_code = $AccountingManager->GenerateInvoiceCode();

            $invoice_info = new Invoices();
            $invoice_info->bi_invoice_ref   = $invoice_code;
            $invoice_info->bi_invoice_code  = $invoice_code;
            $invoice_info->fk_account_id    = $customer_info->ic_account_number;
            $invoice_info->fk_customer_id   = $fo_customer_id;
            $invoice_info->bi_invoice_date  = $order_info->fo_creation_date;
            $invoice_info->bi_due_date      = $order_info->fo_order_datetime;
            $invoice_info->bi_payment_terms = 1;
            $invoice_info->bi_invoice_items_type = 1;
            $invoice_info->bi_invoice_type = 1;
            $invoice_info->bi_payment_type  = 2;
            $invoice_info->bi_invoice_note  = $order_info->fo_notes;
            $invoice_info->bi_total_cost    = $order_info->fo_total_amount;
            $invoice_info->bi_vat_id        = $order_info->fo_tax;
            $invoice_info->bi_discount      = 0;
            $invoice_info->bi_total_price    = $order_info->fo_total_amount;
            $invoice_info->bi_invoice_currency = $order_info->fo_currency_id;
            $invoice_info->bi_invoice_paid = 1;
            $invoice_info->bi_invoice_status = 1;
            $invoice_info->bi_number_payments = 1;
            $invoice_info->bi_company_id = $default_company_id;
            $invoice_info->save();

            $bi_id = $invoice_info->bi_id;

            // create receipt for this order

            $receipt_code = $AccountingManager->generateReceiptCode();

            $receipt_info = new Receipts();
            $receipt_info->fk_invoice_id = $bi_id;
            $receipt_info->br_company_id = $default_company_id;
            $receipt_info->br_customer_id = $customer_info->ic_id;
            $receipt_info->br_account_from = $customer_info->ic_account_number;
            $receipt_info->br_receipt_number = $receipt_code;
            $receipt_info->br_receipt_date = date("Y-m-d");
            $receipt_info->br_creation_date = date("Y-m-d");
            $receipt_info->br_receipt_label = "Receipt from customer " . $customer_info->ic_customer_name . " of order #" .  $order_info->fo_order_code;
            $receipt_info->br_payment_value = $order_info->fo_total_amount;
            $receipt_info->br_receipt_currency = $order_info->fo_currency_id;
            $receipt_info->br_receipt_paid = 1;
            $receipt_info->br_company_id = session('company_id');
            $receipt_info->br_receipt_note = $order_info->fo_notes;
            $receipt_info->save();

            $lst_order_items = FnbOrderItems::whereOiOrderId($fo_id)->get();
            foreach ($lst_order_items as $key => $oi_info) {
                $invoice_items = new InvoiceProducts();
                foreach ($order_info_structure['items'] as $item) {
                    foreach ($item['ingredients'] as $ing) {

                        $ingredient = FnbIngredients::find($ing['id']);
                        if (!$ingredient) continue;

                        $product_id   = $ingredient->in_product_id;
                        $product_info = Products::find($product_id);

                        $invoice_items->ii_item_id = $product_id;
                        $invoice_items->ii_item_label        = $product_info->p_product_name;

                        $invoice_items->fk_invoice_id = $bi_id;
                        $invoice_items->ii_item_type         = 1;
                        $invoice_items->ii_cost_price        = $oi_info->oi_unit_price;
                        $invoice_items->ii_item_price        = $oi_info->oi_unit_price;
                        $invoice_items->ii_total_price        = $oi_info->oi_total_price;
                        $invoice_items->ii_item_qyt          = $oi_info->oi_quantity;
                        $invoice_items->ii_price_currency    = $oi_info->oi_currency_id;
                        $invoice_items->save();
                    }

                    foreach ($item['modifiers'] as $mod) {

                        $order_mod = FnbOrderItemModifiers::find($mod['id']);
                        if (!$order_mod) continue;

                        $modifier = Modifier::find($order_mod->im_modifier_id);
                        if (!$modifier) continue;

                        $product_id   = $modifier->m_item_id;

                        $product_info = Products::find($product_id);

                        $invoice_items->ii_item_id = $product_id;
                        $invoice_items->ii_item_label        = $product_info->p_product_name;
                        $invoice_items->fk_invoice_id = $bi_id;
                        $invoice_items->ii_item_type         = 1;
                        $invoice_items->ii_cost_price        = $oi_info->oi_unit_price;
                        $invoice_items->ii_item_price        = $oi_info->oi_unit_price;
                        $invoice_items->ii_total_price        = $oi_info->oi_total_price;
                        $invoice_items->ii_item_qyt          = $oi_info->oi_quantity;
                        $invoice_items->ii_price_currency    = $oi_info->oi_currency_id;
                        $invoice_items->save();
                    }
                }
            }


            $payment_type_info      = PaymentTypes::find(2);
            $pt_payment_account     = $payment_type_info->pt_payment_account;

            $AccTransaction = new Transactions();
            $AccTransaction->at_transaction_date    = $invoice_info->bi_invoice_date;
            $AccTransaction->at_creation_date       = date("Y-m-d");
            $AccTransaction->at_accounting_doc      = $invoice_info->bi_invoice_code;
            $AccTransaction->fk_acc_journal_id      = 3;
            $AccTransaction->save();
            $at_id = $AccTransaction->at_id;

            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $customer_info->ic_account_number;
            $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number;
            $TransactionMovement->tm_ledger_label       = $invoice_info->bi_invoice_code;
            $TransactionMovement->tm_debit              = $invoice_info->bi_total_price;
            $TransactionMovement->tm_credit             = 0;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_currency_id        = $invoice_info->bi_invoice_currency;
            $TransactionMovement->save();


            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $customer_info->ic_account_number;
            $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number;
            $TransactionMovement->tm_ledger_label       = $invoice_info->bi_invoice_code;
            $TransactionMovement->tm_debit              = 0;
            $TransactionMovement->tm_credit             = $invoice_info->bi_total_price;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_currency_id        = $invoice_info->bi_invoice_currency;
            $TransactionMovement->save();


            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $pt_payment_account;
            $TransactionMovement->tm_sub_ledger_account = $pt_payment_account;
            $TransactionMovement->tm_ledger_label       = $invoice_info->bi_invoice_code;
            $TransactionMovement->tm_debit              = 0;
            $TransactionMovement->tm_credit             = $invoice_info->bi_total_price;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_currency_id        = $invoice_info->bi_invoice_currency;
            $TransactionMovement->save();

            $trans_mov = new TransactionMovements();
            $trans_mov->fk_tran_id              = $at_id;
            $trans_mov->tm_ledger_account       = 701;
            $trans_mov->tm_sub_ledger_account   = 701;
            $trans_mov->tm_debit                = 0;
            $trans_mov->tm_credit               = $invoice_info->bi_total_price;
            $trans_mov->tm_creation_date        = date('Y-m-d');
            $trans_mov->tm_transaction_date        = date('Y-m-d');
            $trans_mov->tm_currency_id          = $invoice_info->bi_invoice_currency;
            $trans_mov->tm_ledger_label         = "Credit Purchasing for Stock ";
            $trans_mov->save();



            foreach ($order_info_structure['items'] as $item) {

                foreach ($item['ingredients'] as $ing) {

                    $ingredient = FnbIngredients::find($ing['id']);
                    if (!$ingredient) continue;

                    $product_id   = $ingredient->in_product_id;
                    $qty_per_unit = $ingredient->in_stock_quantity;

                    $this->reduceStock($product_id, $warehouse_id, $qty_per_unit);
                }

                foreach ($item['modifiers'] as $mod) {

                    $order_mod = FnbOrderItemModifiers::find($mod['id']);
                    if (!$order_mod) continue;

                    $modifier = Modifier::find($order_mod->im_modifier_id);
                    if (!$modifier) continue;

                    $product_id   = $modifier->m_item_id;
                    $qty_per_unit = (float) $modifier->m_quantity;

                    $this->reduceStock($product_id, $warehouse_id, $qty_per_unit);
                }
            }
        }


        $order_info->save();

        //update kitchen status for all order items and menu items
        $kitchen_status = $request->input('oi_kitchen_status');

        if (!empty($fo_id) && $kitchen_status != null && $kitchen_status != 0) {

            FnbOrderItems::where('oi_order_id', $fo_id)
                ->where('oi_is_deleted', 0)
                ->update([
                    'oi_kitchen_status' => $kitchen_status
                ]);

            $order_items = FnbOrderItems::where('oi_order_id', $fo_id)
                ->where('oi_is_deleted', 0)
                ->pluck('oi_item_id')
                ->toArray();

            if (!empty($order_items)) {
                FnbMenuItem::whereIn('mi_id', $order_items)
                    ->update([
                        'mi_kitchen_status_id' => $kitchen_status
                    ]);
            }
        }

        $fo_id = $order_info->fo_id;

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Information Has been saved';

        return Response()->json($result_array);
    }

    public function editOrder($fo_id)
    {
        $order_info = FnbOrders::find($fo_id);
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_stores = Stores::wherePsIsDeleted(0)->get();
        $lst_customers = Customers::whereIcIsDeleted(0)->get();
        $lst_currencies = Currency::get();
        $lst_order_status = SystemStatus::whereSsIsDeleted(0)->whereSsStatusType('pos_order_statuses')->get();
        $lst_tables = Tables::whereFtIsDeleted(0)->get();
        $lst_items = FnbMenuItem::whereMiIsDeleted(0)->get();
        $lst_stations = KitchenStations::whereKsIsDeleted(0)->get();
        $lst_kitchen_status = SystemStatus::whereSsIsDeleted(0)->whereSsStatusType('kitchen_order_statuses')->get();
        $lst_modifiers = Modifier::whereMIsDeleted(0)->get();
        $lst_statuses = SystemStatus::whereSsIsDeleted(0)->get();


        $order_code = "";
        if ($order_info->so_order_code != null) {
            $OrderManager = new OrdersManager();
            $order_code = $OrderManager->GenerateOrdereCode();
            unset($OrderManager);
        }

        $data = array(
            "lst_companies" => $lst_companies,
            "order_info" => $order_info,
            "lst_stores" => $lst_stores,
            "lst_tables" => $lst_tables,
            "lst_customers" => $lst_customers,
            "lst_currencies" => $lst_currencies,
            "lst_order_status" => $lst_order_status,
            "lst_items" => $lst_items,
            "lst_stations" => $lst_stations,
            "lst_kitchen_status" => $lst_kitchen_status,
            "lst_modifiers" => $lst_modifiers,
            "order_code" => $order_code,
            "lst_statuses" => $lst_statuses,
        );
        return view('fnb.orders.editform', $data);
    }

    public function DeleteOrderInfo(Request $request)
    {
        $fo_id = $request->input('fo_id');

        $order_info = FnbOrders::find($fo_id);
        $order_info->fo_is_deleted = 1;
        $order_info->fo_deleted_by = Session('user_id');
        $order_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
