<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Users\Users;
use App\models\FnB\FnbMenuItem;

class FnbItemController extends Controller
{

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

    public function SaveMenuItem(Request $request)
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

        $mi_id = $request->input('mi_id');
        $mi_category_id = $request->input('mi_category_id');
        $mi_kitchen_station_id = $request->input('mi_kitchen_station_id');
        $mi_item_name = $request->input('mi_item_name');
        $mi_item_description = $request->input('mi_item_description');
        $mi_base_price = $request->input('mi_base_price');
        $mi_sku_code = $request->input('mi_sku_code');
        $mi_is_available = $request->input('mi_is_available');
        $mi_is_vegetarian = (int) $request->input('mi_is_vegetarian', 0);
        $mi_is_spicy = $request->input('mi_is_spicy');
        $mi_tax_percentage = $request->input('mi_tax_percentage');
        $mi_item_type = $request->input('mi_item_type');
        $mi_pos_order_display = $request->input('mi_pos_order_display');
        $mi_calories = $request->input('mi_calories');
        $mi_cost_price = $request->input('mi_cost_price');
        $mi_currency_id = $request->input('mi_currency_id');
        $mi_max_order_quantity = $request->input('mi_max_order_quantity');
        $mi_is_active = $request->has('mi_is_active') ? 1 : 0;
        $mi_created_by = $request->input('user_id');

        $result_array = array();

        if ($mi_id != null) {
            $item_info = FnbMenuItem::find($mi_id);
            if (!$item_info) {
                return Response()->json([
                    'is_error' => 1,
                    'error_msg' => 'item not found'
                ]);
            }
        } else {
            $item_info = new FnbMenuItem();
            $item_info->mi_created_by = $mi_created_by;
        }

        $item_info->mi_category_id = $mi_category_id;
        $item_info->mi_kitchen_station_id = $mi_kitchen_station_id;
        $item_info->mi_item_name = $mi_item_name;
        $item_info->mi_item_description = $mi_item_description;
        $item_info->mi_base_price = $mi_base_price;
        $item_info->mi_sku_code = $mi_sku_code;
        $item_info->mi_is_available = $mi_is_available;
        $item_info->mi_is_vegetarian = $mi_is_vegetarian;
        $item_info->mi_is_spicy = $mi_is_spicy;
        $item_info->mi_tax_percentage = $mi_tax_percentage;
        $item_info->mi_item_type = $mi_item_type;
        $item_info->mi_pos_order_display = $mi_pos_order_display;
        $item_info->mi_calories = $mi_calories;
        $item_info->mi_cost_price = $mi_cost_price;
        $item_info->mi_currency_id = $mi_currency_id;
        $item_info->mi_max_order_quantity = $mi_max_order_quantity;
        $item_info->mi_is_active = $mi_is_active;
        $item_info->mi_created_by = $mi_created_by;

        $item_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Menu Item Information Has been saved';
        $result_array['mi_id']     = $item_info->mi_id;

        return Response()->json($result_array);
    }
}
