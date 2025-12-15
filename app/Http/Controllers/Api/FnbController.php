<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\models\FnB\Tables;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use Config;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use Milon\Barcode\DNS1D;
use models\Product;
use App\models\Inventory\Stocks;
use App\models\Inventory\StockMovements;
use App\models\Inventory\ProductLots;
use App\library\ProductManager;
use App\models\Inventory\WareHouses;
use App\models\Accounting\ChartAccounts;
use App\models\Accounting\VatAccounts;
use App\models\System\Currency;
use Swap\Swap;
use App\models\System\Units;
use App\models\Users\Users;
use App\models\Accounting\DefaultAccounts;
use App\models\Sales\Orders;
use App\library\OrdersManager;
use App\models\Sales\OrderProducts;
use App\library\AccountingManager;
use App\models\Billing\Invoices;
use App\models\Inventory\Customers;
use App\models\Billing\InvoiceProducts;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\models\Billing\PaymentTypes;
use App\models\System\Companies;
use App\models\System\CurrencyExchangeRates;
use App\models\Billing\InvoicePayments;
use App\models\Inventory\StockIds;
use App\models\Inventory\Vendors;
use App\models\Phones\PhoneLines;
use League\Csv\Writer;
use App\library\CustomersManager;
use App\models\Billing\Receipts;
use App\Models\FnB\FnbIngredients;
use App\models\FnB\FnbItem;
use App\models\FnB\FnbMenuItem;
use App\Models\FnB\FnbMenuItemModifier;
use App\models\FnB\FnbOrderAuditLog;
use App\models\FnB\FnbOrderItemModifiers;
use App\models\FnB\FnbOrderItems;
use App\models\FnB\FnbOrderKitchen;
use App\models\FnB\FnbOrders;
use App\models\FnB\FnbOrderTables;
use App\models\FnB\KitchenStations;
use App\models\FnB\MenuCategories;
use App\models\FnB\Modifier;
use App\models\Sales\StoreWarehouses;
use App\models\System\SystemStatus;

class FnbController extends Controller
{


    public function GenerateOrdereCodeFNB()
    {
        $year = date("Y");

        $count_orders = FnbOrders::whereYear('fo_order_datetime', $year)->count();

        $index = $count_orders + 1;
        $order_code = "ORD" . sprintf('%04d', $index);

        return $order_code;
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
        $order_items = $request->input('order_items');

        // If POSTMAN sends array → use it as is
        if (is_array($order_items)) {
            // do nothing
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
        $discount = $request->input('discount');
        $total = $request->input('total');
        $order_type = $request->input('order_type');
        $customer_id = $request->input('customer_id');

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

        if ($order_type == 'takeaway') {
            if ($customer_id > 0) {
                $customer_info = Customers::find($customer_id);
                $customer_info->ic_customer_name = $delcustomername;
                $customer_info->ic_customer_address = $delcustomeraddress;
                $customer_info->ic_customer_phone = $delcustomerphone;
                $customer_info->ic_customer_mobile = $delcustomerphone;
                $customer_info->ic_customer_type = $customer_type;
                $customer_info->save();
            } else {
                $customer_check = Customers::whereIcCustomerName($delcustomername)->get();

                if (count($customer_check) == 0) {
                    $customer_info = new Customers();
                    $customer_info->ic_customer_name = $delcustomername;
                    $customer_info->ic_customer_address = $delcustomeraddress;
                    $customer_info->ic_customer_phone = $delcustomerphone;
                    $customer_info->ic_customer_mobile = $delcustomerphone;
                    $customer_info->ic_customer_code = rand(10000, 99999);
                    $customer_info->ic_customer_type = $customer_type;

                    $customer_info->save();
                    $customer_id = $customer_info->ic_id;
                } else {
                    $customer_id = $customer_check[0]->ic_id;
                    $customer_info = Customers::find($customer_id);
                    $customer_info->ic_customer_name = $delcustomername;
                    $customer_info->ic_customer_address = $delcustomeraddress;
                    $customer_info->ic_customer_phone = $delcustomerphone;
                    $customer_info->save();
                }
            }
        }

        $order_code = $this->GenerateOrdereCodeFNB();


        $company_info   = Companies::find($company_id);
        // dd($company_info);

        $creation_date = date("Y-m-d H:i:s");

        $order_info = new FnbOrders();
        $order_info->fo_order_code = $order_code;
        $order_info->fo_order_type = $order_type;
        $order_info->fo_store_id = $store_id;
        $order_info->fo_branch_id = $company_id;
        $order_info->fo_customer_id = $customer_id;
        $order_info->fo_order_status = 5;
        $order_info->fo_order_datetime = $creation_date;
        $order_info->fo_subtotal = $sub_total;
        $order_info->fo_discount = $discount;
        $order_info->fo_total_amount = $total;
        $order_info->fo_created_by = $user_id;
        $order_info->fo_currency_id = 1;
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
            $item->oi_currency_id = 1;
            $item->save();

            $item_db = FnbMenuItem::find($item_order['item_id']);

            $mods = [];

            if (!empty($item_order['modifiers'])) {
                foreach ($item_order['modifiers'] as $m) {
                    $order_mod = FnbOrderItemModifiers::find($m['id']);
                    if (!$order_mod) continue;

                    $modifier = Modifier::find($order_mod->im_modifier_id);
                    if (!$modifier) continue;

                    $mods[] = [
                        "id"    => $m['id'],
                        "name"  => $modifier->m_modifier_name,
                        "price" => $modifier->m_modifier_cost,
                        "qty"   => $modifier->m_quantity,
                    ];
                }
            }

            $final_items[] = [
                "item_id"   => $item_order['item_id'],
                "item_name" => $item_db ? $item_db->mi_item_name : "",
                "quantity"  => $item_order['quantity'],
                "price"     => $item_order['price'],
                "total"     => $item_order['quantity'] * $item_order['price'],
                "modifiers" => $mods,
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
        // dd($customer_info);
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
        $TransactionMovement->tm_ledger_account     = $customer_info->ic_account_number;
        $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number;
        $TransactionMovement->tm_ledger_label       = $order_code;
        $TransactionMovement->tm_debit              = $total;
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

            if (isset($item_order['modifiers'])) {
                foreach ($item_order['modifiers'] as $mod) {

                    $order_mod = FnbOrderItemModifiers::find($mod['id']);
                    if (!$order_mod) continue;

                    $modifier = Modifier::find($order_mod->im_modifier_id);
                    if (!$modifier) continue;

                    $product_id = $modifier->m_item_id;
                    $qty_per_unit = $modifier->m_quantity;

                    $this->reduceStock($product_id, $warehouse_id, $qty_per_unit);
                }
            }
        }


        $currency = Currency::find($order_info->fo_currency_id);

        $data = array(
            "company_info" => $company_info,
            "creation_date" => $creation_date,
            "fo_order_code" => $order_code,
            "delivery_id" => $delivery_id,
            "customer_info" => $customer_info,
            "lst_order_items" => $final_items,
            "order_info" => $order_info,
            "sub_total" => $sub_total,
            "discount" => $discount,
            "cost_total" => $total,
            "currency" => $currency,

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

        $order->fo_order_type = $request->order_type;
        $order->fo_customer_id = $request->customer_id;
        $order->fo_subtotal = $request->sub_total;
        $order->fo_discount = $request->discount;
        $order->fo_total_amount = $request->total;
        $order->fo_order_status = 2; // sent to kitchen
        $order->save();

        // TABLES
        FnbOrderTables::where('ot_order_id', $order->fo_id)->delete();
        $tids = explode(',', $request->table_ids);
        foreach ($tids as $tid) {
            FnbOrderTables::create([
                'ot_order_id' => $order->fo_id,
                'ot_table_id' => $tid
            ]);
        }

        // ITEMS
        FnbOrderItems::where('oi_order_id', $order->fo_id)->delete();
        $items = json_decode($request->order_items, true);

        foreach ($items as $it) {
            FnbOrderItems::create([
                'oi_order_id' => $order->fo_id,
                'oi_item_id' => $it['item_id'],
                'oi_quantity' => $it['quantity'],
                'oi_unit_price' => $it['unit_price'],
                'oi_item_discount' => $it['discount'],
                'oi_notes' => $it['notes'],
                'oi_station_id' => $it['station_id'],
                'oi_kitchen_status' => 1,
            ]);
        }

        return Response()->json(['is_error' => 0, 'order_id' => $order->fo_id]);
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
                ->toArray();

            $items = FnbOrderItems::where('oi_order_id', $order->fo_id)->get();

            $data[] = [
                'order_id' => $order->fo_id,
                'tables' => $tables,
                'items' => $items,
            ];
        }

        return response()->json(['is_error' => 0, 'orders' => $data]);
    }



    public function ListItemCategories(Request $request)
    {
        $category_id = $request->input('category_id');
        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $categories_cond = MenuCategories::whereMcIsDeleted(0);
        if (!empty($category_id)) {
            $categories_cond = $categories_cond->whereCategoryId($category_id);
        }
        $lst_categories = $categories_cond->get();

        $categories_array = array();
        foreach ($lst_categories as $index => $category_info) {
            $categories_array[$index]['mc_id'] = $category_info->mc_id;
            $categories_array[$index]['mc_category_name'] = $category_info->mc_category_name;
            $categories_array[$index]['mc_category_description'] = $category_info->mc_category_description;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = 'Fetch Data Completed';
        $result_array['lst_item_categories'] = $categories_array;
        return Response()->json($result_array);
    }

    public function GetListOfItems(Request $request)
    {
        $category_id = $request->input('category_id');

        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $item_cond = FnbMenuItem::whereMiIsDeleted(0);
        if ($category_id != null) {
            $item_cond = $item_cond->whereMiCategoryId($category_id);
        }

        $lst_fnb_items = $item_cond->get();


        $items_array = array();
        foreach ($lst_fnb_items as $index => $item) {
            $items_array[$index] = [
                'mi_id'            => $item->mi_id,
                'mi_item_name'     => $item->mi_item_name,
                'mi_category_id'   => $item->mi_category_id,
                'category_name'    => $item->Category ? $item->Category->mc_category_name : "",
                'mi_base_price'    => $item->mi_base_price,
                'mi_cost_price'    => $item->mi_cost_price,
                'currency_code'    => $item->Currency ? $item->Currency->cc_currency_code : "GNF",
                'cc_id'      => $item->mi_currency_id,
                'mi_is_available'  => $item->mi_is_available,
                'mi_is_spicy'      => $item->mi_is_spicy,
                'mi_is_vegetarian' => $item->mi_is_vegetarian,
                'mi_barcode'       => $item->mi_barcode,
                'mi_image'         => $item->mi_image_file_name,
            ];
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_items'] = $items_array;
        return Response()->json($result_array);
    }

    public function GetListOfOrders(Request $request)
    {

        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $date_from    = $request->input('date_from');
        $date_to      = $request->input('date_to');
        $warehouse_id = $request->input('warehouse_id');
        $query = FnbOrders::where('fo_is_deleted', 0);

        if (!empty($warehouse_id)) {
            $query->where('warehouse_id', $warehouse_id);
        }

        if (!empty($date_from)) {
            $query->whereDate('fo_order_datetime', '>=', $date_from);
        }

        if (!empty($date_to)) {
            $query->whereDate('fo_order_datetime', '<=', $date_to);
        }
        $lst_orders = $query->orderBy('fo_order_datetime', 'DESC')->get();

        $orders_array = array();
        foreach ($lst_orders as $index => $order) {
            $orders_array[$index]['fo_id']            = $order->fo_id;
            $orders_array[$index]['fo_order_code']          = $order->fo_order_code;
            $orders_array[$index]['fo_total_amount']  = $order->fo_total_amount;
            $orders_array[$index]['fo_order_datetime'] = $order->fo_order_datetime;
            $orders_array[$index]['warehouse_id']  = $order->warehouse_id;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_orders'] = $orders_array;
        return Response()->json($result_array);
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

        $orders = FnbOrders::where('fo_is_deleted', 0)
            ->where('fo_order_status', 1)
            ->get();

        $result = [];

        foreach ($orders as $order) {

            $items = FnbOrderItems::where('oi_order_id', $order->fo_id)
                ->where('oi_is_deleted', 0)
                ->leftJoin('fnb_menu_items', 'fnb_menu_items.mi_id', '=', 'fnb_order_items.oi_item_id')
                ->leftJoin('sys_status', 'sys_status.ss_id', '=', 'fnb_order_items.oi_kitchen_status')
                ->select(
                    'oi_id',
                    'oi_item_id',
                    'oi_quantity',
                    'oi_unit_price',
                    'oi_notes',
                    'oi_kitchen_status',
                    'oi_station_id',
                    'fnb_menu_items.mi_item_name',
                    'fnb_menu_items.mi_kitchen_station_id',
                    'sys_status.ss_status_title',
                    'sys_status.ss_status_color'
                )
                ->get();

            $result[] = [
                'fo_id' => $order->fo_id,
                'fo_order_code' => $order->fo_order_code,
                'fo_order_type' => $order->fo_order_type,
                'fo_table_id' => $order->fo_table_id,
                'items' => $items,
                'fo_created_at' => $order->fo_creation_date,
            ];
        }

        return response()->json([
            "is_error" => 0,
            "lst_pending_orders" => $result
        ]);
    }


    public function UpdateKitchenStatus(Request $request)
    {
        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $oi_id   = $request->input('oi_id');
        $new_status = $request->input('oi_kitchen_status');
        $order_item = FnbOrderItems::find($oi_id);
        if (!$order_item) {
            return response()->json([
                "is_error" => 1,
                "error_msg" => "Order item not found."
            ]);
        }

        $old_status = $order_item->oi_kitchen_status;

        $order_item->oi_kitchen_status = $new_status;
        $order_item->save();

        return response()->json([
            "is_error" => 0,
            "error_msg" => "",
            "message" => "Kitchen status updated successfully.",
            "old_status" => $old_status,
            "new_status" => $new_status
        ]);
    }

    public function GetListModifiers(Request $request)
    {

        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $lst_modifiers = Modifier::whereMIsDeleted(0)->get();

        $modifiers_array = [];
        foreach ($lst_modifiers as $index => $modifier_info) {
            $modifiers_array[$index]['m_id']   = $modifier_info->m_id;
            $modifiers_array[$index]['m_modifier_name'] = $modifier_info->m_modifier_name;
            $modifiers_array[$index]['m_price_modifier'] = $modifier_info->m_price_modifier;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_modifiers'] = $modifiers_array;

        return Response()->json($result_array);
    }

    public function GetListTables(Request $request)
    {

        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $lst_tables = Tables::whereFtIsDeleted(0)->get();

        $tables_array = [];
        foreach ($lst_tables as $index => $table_info) {
            $tables_array[$index]['ft_id']   = $table_info->ft_id;
            $tables_array[$index]['ft_label'] = $table_info->ft_label;
            $tables_array[$index]['ft_number_seats'] = $table_info->ft_number_seats;
            $tables_array[$index]['ft_status_id'] = $table_info->ft_status_id;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_tables'] = $tables_array;

        return Response()->json($result_array);
    }

    public function GetListKitchenOrderStatus(Request $request)
    {

        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $lst_kitchen_status = SystemStatus::whereSsIsDeleted(0)->whereSsStatusType('kitchen_order_statuses')->get();

        $kitchen_status_array = [];
        foreach ($lst_kitchen_status as $index => $kitchen_status_info) {
            $kitchen_status_array[$index]['ss_id']   = $kitchen_status_info->ss_id;
            $kitchen_status_array[$index]['ss_status_title'] = $kitchen_status_info->ss_status_title;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_kitchen_statuses'] = $kitchen_status_array;

        return Response()->json($result_array);
    }

    public function GetListItemsByKitchen(Request $request)
    {
        $kitchen_id = $request->input('mi_kitchen_station_id');

        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $lst_menu_items = FnbMenuItem::where('mi_kitchen_station_id', $kitchen_id)->where('mi_is_deleted', 0)->get();

        $menu_items_array = [];
        foreach ($lst_menu_items as $index => $item_info) {
            $menu_items_array[$index]['mi_id']   = $item_info->mi_id;
            $menu_items_array[$index]['mi_item_name'] = $item_info->mi_item_name;
            $menu_items_array[$index]['mi_sku_code'] = $item_info->mi_sku_code;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_menu_items_by_kitchen'] = $menu_items_array;

        return Response()->json($result_array);
    }

    public function GetStationsName(Request $request)
    {
        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $lst_kitchens = KitchenStations::whereKsIsDeleted(0)->get();

        $kitchens_array = [];
        foreach ($lst_kitchens as $index => $kitchen_info) {
            $kitchens_array[$index]['ks_id']   = $kitchen_info->ks_id;
            $kitchens_array[$index]['ks_name'] = $kitchen_info->ks_name;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_kitchens'] = $kitchens_array;

        return Response()->json($result_array);
    }
}
