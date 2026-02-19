<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Users\Users;
use App\models\FnB\FnbMenuItem;
use App\Models\FnB\FnbIngredients;
use App\models\System\Units;
use App\Models\System\Companies;
use App\models\System\Currency;
use Barryvdh\DomPDF\Facade\Pdf;

class FnbItemController extends Controller
{

    /**
     * @author Mohammed kheiredine
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            $unit_label = '';
            if ($item->mi_unit_id) {
                $unit = Units::find($item->mi_unit_id);
                $unit_label = $unit ? $unit->su_unit_label : '';
            }

            $items_array[$index] = [
                'mi_id' => $item->mi_id,
                'mi_item_name' => $item->mi_item_name,
                'mi_category_id' => $item->mi_category_id,
                'category_name' => $item->Category ? $item->Category->mc_category_name : "",
                'mi_base_price' => $item->mi_base_price,
                'mi_cost_price' => $item->mi_cost_price,
                'currency_code' => $item->Currency ? $item->Currency->cc_currency_code : "GNF",
                'cc_id' => $item->mi_currency_id,
                'mi_unit_id' => $item->mi_unit_id,
                'unit_label' => $unit_label,
                'mi_is_available' => $item->mi_is_available,
                'mi_is_spicy' => $item->mi_is_spicy,
                'mi_is_vegetarian' => $item->mi_is_vegetarian,
                'mi_barcode' => $item->mi_barcode,
                'mi_image' => $item->mi_image_file_name,
                'mi_item_description' => $item->mi_item_description,
                'mi_loyalty_points' => (int) ($item->mi_loyalty_points ?? 0),
            ];
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_items'] = $items_array;
        return Response()->json($result_array);
    }

    /**
     * @author Mohammed kheiredine
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     *@author Mohammed kheiredine
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @author Mohammed kheiredine
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetListIngredientsForMenuItem(Request $request)
    {
        $g_hash  = $request->input('g_hash');
        $user_id = $request->input('user_id');
        $item_id = $request->input('item_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'User not found']);
        }

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'hash sequence is not valid !!']);
        }

        $lst_ingredients = FnbIngredients::with(['Product', 'Unit', 'Currency'])
            ->where('in_item_id', $item_id)
            ->where('in_is_deleted', 0)
            ->get();

        $ingredients_array = [];
        foreach ($lst_ingredients as $index => $ing) {
            $ingredients_array[$index] = [
                'in_id'              => $ing->in_id,
                'in_ingredient_name' => $ing->in_ingredient_name,
                'in_ingredient_code' => $ing->in_ingredient_code,
                'in_product_id'      => $ing->in_product_id,
                'product_name'       => $ing->Product ? $ing->Product->p_product_name : '',
                'in_stock_quantity'  => $ing->in_stock_quantity,
                'in_unit_of_measure' => $ing->in_unit_of_measure,
                'unit_label'         => $ing->Unit ? $ing->Unit->su_unit_label : '',
                'in_cost_per_unit'   => $ing->in_cost_per_unit,
                'in_currency_id'     => $ing->in_currency_id,
                'currency_code'      => $ing->Currency ? $ing->Currency->cc_currency_code : '',
                'in_waste_percent'   => $ing->in_waste_percent,
                'in_line_cost'       => $ing->in_line_cost,
                'in_notes'           => $ing->in_notes,
            ];
        }

        return Response()->json([
            'is_error'    => 0,
            'error_msg'   => '',
            'ingredients' => $ingredients_array,
        ]);
    }


    /**
     * @author Mohammed kheiredine
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SaveIngredientForItem(Request $request)
    {
        //if in_id mwjood => update existing ingredient
        //if in_id is null => creates new ingredient linked to item_id

        $g_hash  = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $in_id = $request->input('in_id');
        $item_id = $request->input('item_id');
        $in_ingredient_name = $request->input('in_ingredient_name');
        $in_ingredient_code = $request->input('in_ingredient_code');
        $in_product_id = $request->input('in_product_id');
        $in_stock_quantity = $request->input('in_stock_quantity');
        $in_unit_of_measure = $request->input('in_unit_of_measure');
        $in_cost_per_unit = $request->input('in_cost_per_unit');
        $in_currency_id = $request->input('in_currency_id');
        $in_waste_percent = $request->input('in_waste_percent', 0);
        $in_notes = $request->input('in_notes');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'User not found']);
        }

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'hash sequence is not valid !!']);
        }

        if ($in_id != null) {
            $ingredient = FnbIngredients::find($in_id);
            if (!$ingredient) {
                return Response()->json(['is_error' => 1, 'error_msg' => 'Ingredient not found']);
            }
        } else {
            $ingredient = new FnbIngredients();
            $ingredient->in_item_id = $item_id;
        }

        $ingredient->in_ingredient_name = $in_ingredient_name;
        $ingredient->in_ingredient_code = $in_ingredient_code;
        $ingredient->in_product_id = $in_product_id;
        $ingredient->in_stock_quantity = $in_stock_quantity;
        $ingredient->in_unit_of_measure = $in_unit_of_measure;
        $ingredient->in_cost_per_unit = $in_cost_per_unit;
        $ingredient->in_currency_id = $in_currency_id;
        $ingredient->in_waste_percent = $in_waste_percent;
        $ingredient->in_notes = $in_notes;
        $ingredient->in_line_cost = $in_stock_quantity * $in_cost_per_unit * (1 + $in_waste_percent);

        $ingredient->save();

        return Response()->json([
            'is_error'  => 0,
            'error_msg' => 'Ingredient has been saved',
            'in_id'     => $ingredient->in_id,
        ]);
    }

    /**
     * @author Mohammed kheiredine
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetListUnits(Request $request)
    {
        $g_hash  = $request->input('g_hash');
        $user_id = $request->input('user_id');


        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'User not found']);
        }

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'hash sequence is not valid !!']);
        }

        $lst_units = Units::whereSuIsDeleted(0)->get();

        $units_array = [];
        foreach ($lst_units as $index => $unit) {
            $units_array[$index] = [
                'su_id'         => $unit->su_id,
                'su_unit_label' => $unit->su_unit_label,
                'su_unit_code'  => $unit->su_unit_code,
            ];
        }

        return Response()->json([
            'is_error'  => 0,
            'error_msg' => '',
            'lst_units' => $units_array,
        ]);
    }

    /**
     * @author Mohammed kheiredine
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteIngredient(Request $request)
    {
        $g_hash  = $request->input('g_hash');
        $user_id = $request->input('user_id');
        $in_id   = $request->input('in_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'User not found']);
        }

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'hash sequence is not valid !!']);
        }

        $ingredient = FnbIngredients::find($in_id);
        if (!$ingredient) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'Ingredient not found']);
        }

        $ingredient->in_is_deleted = 1;
        $ingredient->in_deleted_by = $user_id;
        $ingredient->save();

        return Response()->json([
            'is_error'  => 0,
            'error_msg' => 'Ingredient has been deleted',
        ]);
    }

    /**
     * @author Mohammed kheiredine
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function UpdateItemDescription(Request $request)
    {
        $g_hash  = $request->input('g_hash');
        $user_id = $request->input('user_id');
        $mi_id   = $request->input('mi_id');
        $mi_item_description = $request->input('mi_item_description');

        $user_info = Users::find($user_id);
        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'User not found']);
        }

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'hash sequence is not valid !!']);
        }

        $item = FnbMenuItem::find($mi_id);
        if (!$item) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'Item not found']);
        }

        $item->mi_item_description = $mi_item_description;
        $item->save();

        return Response()->json([
            'is_error'  => 0,
            'error_msg' => 'Description has been saved',
        ]);
    }

    /**
     * @author Mohammed kheiredine
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function PrintRecipePdf(Request $request)
    {
        $g_hash  = $request->input('g_hash');
        $user_id = $request->input('user_id');
        $mi_id   = $request->input('mi_id');
        $download = $request->input('download', 0);

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'hash sequence is not valid !!']);
        }

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'User not found']);
        }

        $item = FnbMenuItem::where('mi_id', $mi_id)
            ->where('mi_is_deleted', 0)
            ->first();

        if (!$item) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'Item not found']);
        }

        $ingredients = FnbIngredients::with(['Product', 'Unit'])
            ->where('in_item_id', $mi_id)
            ->where('in_is_deleted', 0)
            ->get();

        $company = Companies::first();

        $logoSrc = null;

        if (
            $company &&
            $company->cd_logo_base_src &&
            $company->cd_logo_file_name &&
            $company->cd_logo_file_extension
        ) {
            $logoSrc =
                'resources/companies/' .
                trim($company->cd_logo_base_src, '/\\') . '/' .
                $company->cd_logo_file_name . '.' .
                $company->cd_logo_file_extension;

            $logoSrc = str_replace('\\', '/', $logoSrc);

            if (!file_exists(public_path($logoSrc))) {
                $logoSrc = null;
            }
        }

        $currency = Currency::where('cc_id', $item->mi_currency_id)->first();
        $currencyCode = $currency ? $currency->cc_currency_code : '';
        $totalCost = $ingredients->sum('in_line_cost');

        $data = [
            'item'        => $item,
            'ingredients' => $ingredients,
            'logoSrc'     => $logoSrc ?? null,
            'printedBy'   => $user_info->u_fullname,
            'printedAt'   => now(),
            'currency'    => $currencyCode,
            'totalCost'   => $totalCost,
        ];

        $pdf = Pdf::loadView('fnb.receipes.receipe-print', $data)
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => false,
                'chroot' => public_path(),
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'dejavu sans',
            ]);

        $fileName = 'Recipe_' . $item->mi_item_name . '.pdf';

        if ($download) {
            return $pdf->download($fileName);
        }

        return $pdf->stream($fileName);
    }
}
