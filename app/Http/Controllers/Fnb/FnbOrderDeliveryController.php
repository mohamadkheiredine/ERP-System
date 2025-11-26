<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use App\models\FnB\KitchenStations;
use App\models\FnB\MenuCategories;
use App\models\FnB\FnbItem;
use App\models\Sales\Terminals;
use App\models\System\Companies;
use App\models\Accounting\VatAccounts;
use App\Models\Fnb\FnbMenuItemModifier;
use App\models\FnB\FnbOrderDelivery;
use App\models\FnB\FnbOrderItemModifiers;
use App\models\FnB\FnbOrderItems;
use App\models\FnB\FnbOrders;
use App\models\Inventory\Customers;
use Milon\Barcode\DNS1D;


class FnbOrderDeliveryController extends Controller
{

    public function DisplayDeliveries()
    {
        $lst_deliveries = FnbOrderDelivery::whereOdIsDeleted(0)->get();

        $data = [
            "lst_deliveries" => $lst_deliveries
        ];

        $result_array = [
            'display'     => view("fnb.orders.displaydeliveries", $data)->render()
        ];

        return response()->json($result_array);
    }

    public function SaveDelivery(Request $request)
    {
        $od_order_id = $request->input("od_order_id");
        $od_delivery_address = $request->input("od_delivery_address");
        $od_delivery_status = $request->input("od_delivery_status");
        $od_delivery_cost = $request->input("od_delivery_cost");
        $ic_customer_name = $request->input("ic_customer_name");
        $ic_customer_phone = $request->input("ic_customer_phone");

        $result_array = array();

        $delivery_info = new FnbOrderDelivery();
        $customer_info = new Customers();

        $delivery_info->od_order_id = $od_order_id;
        $delivery_info->od_delivery_address = $od_delivery_address;
        $delivery_info->od_delivery_status = $od_delivery_status;
        $delivery_info->od_delivery_cost = $od_delivery_cost;

        $customer_info->ic_customer_name = $ic_customer_name;
        $customer_info->ic_customer_phone = $ic_customer_phone;

        $delivery_info->save();
        $customer_info->save();

        $order_info = FnbOrders::where('fo_id', $od_order_id)->first();

        if ($order_info) {
            $order_info->fo_total_amount = floatval($order_info->fo_total_amount) + $od_delivery_cost;
            $order_info->save();
        }

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Information Has been saved';

        return Response()->json($result_array);
    }

    public function DeleteDelivery(Request $request)
    {
        $delivery_id = $request->input('delivery_id');

        $delivery_info = FnbOrderDelivery::where('delivery_id', $delivery_id)
            ->where('od_is_deleted', 0)
            ->first();

        $delivery_cost = floatval($delivery_info->od_delivery_cost);
        $order = FnbOrders::where('fo_id', $delivery_info->od_order_id)->first();
        if ($order) {
            $order->fo_total_amount = floatval($order->fo_total_amount) - $delivery_cost;

            $order->save();
        }

        $delivery_info->od_is_deleted = 1;
        $delivery_info->od_deleted_by = Session('user_id');
        $delivery_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
