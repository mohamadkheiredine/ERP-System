<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
use App\models\FnB\FnbItem;
use App\Models\FnB\FnbMenuItemModifier;
use App\models\FnB\FnbOrderItems;
use App\models\FnB\FnbOrders;
use App\models\FnB\FnbOrderTables;
use App\models\FnB\MenuCategories;
use App\models\FnB\Modifier;

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

    public function CreateOrder(Request $request)
    {
        $g_hash   = $request->input('g_hash');
        $order_items = $request->input('order_items');
        $order_items = json_decode($order_items, true);
        $store_id = $request->input('store_id');
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
        $delivery_id          = strlen($delcustomername) > 0 ? 1 : 0;
        $customer_info = null;

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        if ($order_type == 'delivery') {
            if ($customer_id > 0) {
                $customer_info = Customers::find($customer_id);
                $customer_info->ic_customer_name = $delcustomername;
                $customer_info->ic_customer_address = $delcustomeraddress;
                $customer_info->ic_customer_phone = $delcustomerphone;
                $customer_info->ic_customer_mobile = $delcustomerphone;
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

        $company_id = $user_info->fk_company_id;

        $company_info   = Companies::find($company_id);

        $creation_date = date("Y-m-d H:i:s");

        $order_info = new FnbOrders();
        $order_info->fo_order_code = $order_code;
        $order_info->fo_order_type = $order_type;
        $order_info->fo_store_id = $store_id;
        $order_info->fo_customer_id = $customer_id;
        $order_info->fo_order_status = 1;
        $order_info->fo_order_datetime = $creation_date;
        $order_info->fo_subtotal = $sub_total;
        $order_info->fo_discount = $discount;
        $order_info->fo_total_amount = $total;
        $order_info->fo_created_by = $user_id;
        $order_info->fo_currency_id = 1;
        $order_info->save();

        $fo_id = $order_info->fo_id;

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

            $item_db = FnbItem::find($item_order['item_id']);

            $final_items[] = [
                "item_id"   => $item_order['item_id'],
                "item_name" => $item_db ? $item_db->fi_item_name : "",
                "quantity"  => $item_order['quantity'],
                "price"     => $item_order['price'],
                "total"     => $item_order['quantity'] * $item_order['price'],
            ];
        }


        $structure = array(
            "fo_id" => $fo_id,
            "fo_code" => $order_code,
            "items" => $order_items
        );

        $order_info->fo_order_structure = json_encode($structure);
        $order_info->save();
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

        if (!empty($category_id)) {
            $lst_categories = MenuCategories::where('mc_id', $category_id)->where('mc_is_deleted', 0)->get();
        } else {
            $lst_categories = MenuCategories::where('mc_is_deleted', 0)->get();
        }

        $categories_array = array();
        foreach ($lst_categories as $index => $category_info) {
            $categories_array[$index]['mc_id'] = $category_info->mc_id;
            $categories_array[$index]['mc_category_name'] = $category_info->mc_category_name;
            $categories_array[$index]['mc_category_description'] = $category_info->mc_category_description;
        }

        $result_array['is_error'] = 1;
        $result_array['error_msg'] = '';
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

        $query = FnbItem::select(
            'fnb_item.fi_id',
            'fnb_item.fi_item_name',
            'fnb_item.fi_category_id',
            DB::raw('(SELECT oi_unit_price
                      FROM fnb_order_items
                      WHERE oi_item_id = fnb_item.fi_id
                      ORDER BY oi_id DESC
                      LIMIT 1) AS last_unit_price')
        )
            ->where('fnb_item.fi_is_deleted', 0);


        if (!empty($category_id)) {
            $query->where('fi_category_id', $category_id);
        }

        $lst_items = $query->get();
        $items_array = array();
        foreach ($lst_items as $i => $item) {
            $items_array[$i] = [
                'fi_id'          => $item->fi_id,
                'fi_item_name'   => $item->fi_item_name,
                'fi_category_id' => $item->fi_category_id,
                'fi_item_price'  => $item->last_unit_price ?? 0
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
            $query->where('fo_warehouse_id', $warehouse_id);
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
            $orders_array[$index]['fo_warehouse_id']  = $order->fo_warehouse_id;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_orders'] = $orders_array;
        return Response()->json($result_array);
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

        $lst_tables = FnbOrderTables::whereFtIsDeleted(0)->get();

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

    public function GetLastItemId(Request $request)
    {
        $g_hash = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);
        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";

        if (hash('sha256', $c_hash) != $g_hash) {
            return response()->json(['is_error' => 1, 'msg' => 'Invalid hash']);
        }

        $last = FnbOrderItems::orderBy('oi_id', 'DESC')->first();
        dd("last id is ", $last);
        return response()->json([
            'is_error' => 0,
            'last_id' => $last ? $last->oi_id : 0
        ]);
    }
}
