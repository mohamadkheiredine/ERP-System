<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use App\library\OrdersManager;
use App\Models\FnB\FnbIngredients;
use App\models\FnB\FnbItem;
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
use App\models\Sales\OrderStatus;
use App\models\Sales\Stores;
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

        $OrderManager   = new OrdersManager();
        $order_code     = $OrderManager->GenerateOrdereCode();
        unset($OrderManager);

        $data = array(
            "lst_companies" => $lst_companies,
            "lst_stores" => $lst_stores,
            "lst_tables" => $lst_tables,
            "lst_customers" => $lst_customers,
            "lst_currencies" => $lst_currencies,
            "lst_order_status" => $lst_order_status,
            "order_code" => $order_code,
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
        }


        $order_info->fo_order_structure = json_encode($order_info_structure);
        $order_info->save();

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
        $lst_items = FnbItem::whereFiIsDeleted(0)->get();
        $lst_stations = KitchenStations::whereKsIsDeleted(0)->get();
        $lst_kitchen_status = SystemStatus::whereSsIsDeleted(0)->whereSsStatusType('pos_order_statuses')->get();
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
