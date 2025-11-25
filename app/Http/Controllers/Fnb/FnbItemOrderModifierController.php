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
use App\models\FnB\FnbOrderItemModifiers;
use App\models\FnB\FnbOrderItems;
use Milon\Barcode\DNS1D;


class FnbItemOrderModifierController extends Controller
{

    public function DisplayListItemsOrdersModifiers()
    {
        $lst_menu_items_orders_modifiers = FnbOrderItemModifiers::whereImIsDeleted(0)->get();

        $data = [
            "lst_menu_items_orders_modifiers" => $lst_menu_items_orders_modifiers
        ];

        $result_array = [
            'display'     => view("fnb.orders.displaylistitemsordersmodifiers", $data)->render()
        ];

        return response()->json($result_array);
    }

    public function SaveItemOrderModifierInfo(Request $request)
    {
        $im_item_id = $request->input('im_item_id');
        $im_modifier_id = $request->input('im_modifier_id');
        $im_modifier_name = $request->input('im_modifier_name');
        $im_modifier_type = $request->input('im_modifier_type');
        $im_modifier_cost = $request->input('im_modifier_cost');
        $im_currency_id = $request->input('im_currency_id');

        $result_array = array();

        $item_order_modifier_info = new FnbOrderItemModifiers();

        $item_order_modifier_info->im_item_id = $im_item_id;
        $item_order_modifier_info->im_modifier_id = $im_modifier_id;
        $item_order_modifier_info->im_modifier_name = $im_modifier_name;
        $item_order_modifier_info->im_modifier_type = $im_modifier_type;
        $item_order_modifier_info->im_modifier_cost = $im_modifier_cost;
        $item_order_modifier_info->im_currency_id = $im_currency_id;

        $item_order_modifier_info->save();

        $item_order = FnbOrderItems::find($im_item_id);

        $item_order->oi_unit_price += $im_modifier_cost;
        $item_order->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Information Has been saved';

        return Response()->json($result_array);
    }

    public function DeleteItemOrderModifier(Request $request)
    {
        $im_id = $request->input('im_id');

        $modifier = FnbOrderItemModifiers::find($im_id);

        $item_order = FnbOrderItems::where('oi_item_id',$modifier->im_item_id)->first();

        $item_order->oi_unit_price -= $modifier->im_modifier_cost;
        $item_order->save();

        $item_order_modifier_info = FnbOrderItemModifiers::find($im_id);
        $item_order_modifier_info->im_is_deleted = 1;
        $item_order_modifier_info->im_deleted_by = Session('user_id');
        $item_order_modifier_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
