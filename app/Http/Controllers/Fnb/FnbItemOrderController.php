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
use App\models\FnB\FnbOrderItems;
use App\models\Inventory\Customers;
use Milon\Barcode\DNS1D;


class FnbItemOrderController extends Controller
{

    public function DisplayListItemsOrders(Request $request)
    {
        $page_number   = $request->input('page_number');
        $order_id     = $request->input('order_id');

        $nbr_rows_per_pages = Config::get('apmconfig.max_rows_per_page', 10);

        $skip = ($page_number > 1)
            ? ($page_number - 1) * $nbr_rows_per_pages
            : 0;

        $query = FnbOrderItems::where('oi_is_deleted', 0)->where('oi_order_id', $order_id)->with(['Item', 'Order']);

        // Pagination count
        $total_items  = $query->count();
        $total_pages  = max(1, ceil($total_items / $nbr_rows_per_pages));

        $lst_menu_items_orders = $query
            ->skip($skip)
            ->take($nbr_rows_per_pages)
            ->get();

        $data = [
            "lst_menu_items_orders" => $lst_menu_items_orders
        ];

        $result_array = [
            'total_pages' => $total_pages,
            'display'     => view("fnb.orders.displaylistitemsorders", $data)->render()
        ];

        return response()->json($result_array);
    }

    public function SaveItemOrderInfo(Request $request)
    {
        $oi_order_id = $request->input('oi_order_id');
        $oi_item_id = $request->input('oi_item_id');
        $oi_quantity = $request->input('oi_quantity');
        $oi_unit_price = $request->input('oi_unit_price');
        $oi_item_discount = $request->input('oi_item_discount');

        $oi_kitchen_status = $request->input('oi_kitchen_status');
        $oi_station_id = $request->input('oi_station_id');
        $oi_notes = $request->input('oi_notes');
        $oi_currency_id = $request->input('oi_currency_id');

        $result_array = array();

        $item_order_info = new FnbOrderItems();

        $item_order_info->oi_order_id = $oi_order_id;
        $item_order_info->oi_item_id = $oi_item_id;
        $item_order_info->oi_quantity = $oi_quantity;
        $item_order_info->oi_unit_price = $oi_unit_price;
        $item_order_info->oi_item_discount = $oi_item_discount;
        $item_order_info->oi_kitchen_status = $oi_kitchen_status;
        $item_order_info->oi_station_id = $oi_station_id;
        $item_order_info->oi_notes = $oi_notes;
        $item_order_info->oi_currency_id = $oi_currency_id;

        $item_order_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Information Has been saved';

        return Response()->json($result_array);
    }

    public function DeleteItemOrderInfo(Request $request)
    {
        $oi_id = $request->input('oi_id');

        $item_order_info = FnbOrderItems::find($oi_id);
        $item_order_info->oi_is_deleted = 1;
        $item_order_info->oi_deleted_by = Session('user_id');
        $item_order_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }


}
