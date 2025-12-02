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
use Milon\Barcode\DNS1D;


class FnbMenuItemModifierController extends Controller
{

    public function DisplayListItemsModifiers(Request $request)
    {
        $page_number   = $request->input('page_number');
        $item_id     = $request->input('item_id');
        $nbr_rows_per_pages = Config::get('apmconfig.max_rows_per_page', 10);

        $skip = ($page_number > 1)
            ? ($page_number - 1) * $nbr_rows_per_pages
            : 0;

        $query = FnbMenuItemModifier::where('im_is_deleted', 0)->where('fk_menu_item_id', $item_id)->with(['Item', 'Modifier']);

        // Pagination count
        $total_items  = $query->count();
        $total_pages  = max(1, ceil($total_items / $nbr_rows_per_pages));

        $lst_menu_items_modifiers = $query
            ->skip($skip)
            ->take($nbr_rows_per_pages)
            ->get();

        $data = [
            "lst_menu_items_modifiers" => $lst_menu_items_modifiers,
        ];
        $result_array = [
            'total_pages' => $total_pages,
            'display'     => view("fnb.menu-items.displaylistitemsmodifiers", $data)->render(),
        ];

        return response()->json($result_array);
    }

    public function SaveItemModifierInfo(Request $request)
    {
        $fk_menu_item_id = $request->input('fk_menu_item_id');
        $fk_modifier_id = $request->input('fk_modifier_id');
        $im_type_id = $request->input('im_type_id');
        $im_product_id = $request->input('im_product_id');
        $im_override_cost = $request->input('im_override_cost');
        $im_currency_id = $request->input('im_currency_id');
        $im_is_remove_ingredient = $request->input('im_is_remove_ingredient') ? 1 : 0;

        $result_array = array();

        $item_modifier_info = new FnbMenuItemModifier();

        $item_modifier_info->fk_menu_item_id = $fk_menu_item_id;
        $item_modifier_info->fk_modifier_id = $fk_modifier_id;
        $item_modifier_info->im_type_id = $im_type_id;
        $item_modifier_info->im_product_id = $im_product_id;
        $item_modifier_info->im_override_cost = $im_override_cost;
        $item_modifier_info->im_currency_id = $im_currency_id;
        $item_modifier_info->im_is_remove_ingredient = $im_is_remove_ingredient;

        $item_modifier_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Information Has been saved';

        return Response()->json($result_array);
    }

    public function DeleteItemModifier(Request $request)
    {
        $im_id = $request->input('im_id');

        $item_modifier_info = FnbMenuItemModifier::find($im_id);
        $item_modifier_info->im_is_deleted = 1;
        $item_modifier_info->im_deleted_by = Session('user_id');
        $item_modifier_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }


}
