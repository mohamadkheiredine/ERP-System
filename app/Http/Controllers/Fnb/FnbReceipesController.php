<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Companies;
use App\Models\FnB\Floor;
use App\models\FnB\FnbItem;
use App\Models\FnB\FnbIngredients;
use App\models\Inventory\Products;
use App\models\System\Currency;
use App\models\System\Units;
use Config;

class FnbReceipesController extends Controller
{
    public function index()
    {
        // $lst_floors = Floor::whereFlIsDeleted(0)->get();
        $data = array(
            // "lst_floors" => $lst_floors
        );
        return Response()->view('fnb.receipes.fnb-receipes', $data);
    }

    public function DisplayListReceipes(Request $request)
    {
        $general_search = $request->input('general_search');

        $receipe_cond = FnbItem::whereFiIsDeleted(0);

        if (!empty($general_search)) {
            $receipe_cond->where('fi_item_name', 'LIKE', '%' . $general_search . '%');
        }


        $list_receipes = $receipe_cond->get();

        $data = array(
            "list_receipes" => $list_receipes,
        );

        $result_array = array();
        $result_array['display'] = view("fnb.receipes.cardsReceipes", $data)->render();

        return Response()->json($result_array);
    }

    public function GetReceipe(Request $request)
    {
        $fi_id = $request->input('fi_id');
        $ingredients = FnbIngredients::with(['Product', 'Item'])
            ->where('in_item_id', $fi_id)
            ->where('in_is_deleted', 0)
            ->get();

        $item = FnbItem::find($fi_id);

        $data = array(
            "ingredients" => $ingredients,
            "item" => $item
        );

        $result_array = array();
        $result_array['display'] = view("fnb.receipes.ReceipeIngredients", $data)->render();

        return Response()->json($result_array);
    }

    public function DisplayListIngredients(Request $request)
    {
        $fi_id = $request->input('fi_id');
        $lst_ingredients = FnbIngredients::with(['Unit', 'Item'])->where('in_item_id', $fi_id)
            ->where('in_is_deleted', 0)
            ->get();

        $lst_units = Units::whereSuIsDeleted(0)->get();
        $lst_products = Products::wherePProductIsDeleted(0)->wherePProductType(21)->get();

        $data = array(
            "lst_ingredients" => $lst_ingredients,
            "lst_units" => $lst_units,
            "lst_products" => $lst_products
        );
        $result_array = array();
        $result_array['display'] = view("fnb.receipes.listingredients", $data)->render();

        return Response()->json($result_array);
    }

    public function addIngredient(Request $request)
    {
        $item_id = $request->item_id;
        $lst_products = Products::wherePProductIsDeleted(0)->wherePProductType(21)->get();
        $lst_items = FnbItem::whereFiIsDeleted(0)->get();
        $lst_unit_of_measure = Units::whereSuIsDeleted(0)->get();
        $lst_currencies = Currency::get();

        $data = array(
            "lst_products" => $lst_products,
            "lst_items" => $lst_items,
            "lst_unit_of_measure" => $lst_unit_of_measure,
            "lst_currencies" => $lst_currencies,
            "item_id" => $item_id
        );

        return Response()->view('fnb.receipes.addform', $data);
    }

    public function SaveIngredientInfo(Request $request)
    {
        $item_id = $request->input('item_id');
        $in_ingredient_name = $request->input('in_ingredient_name');
        $in_stock_quantity = $request->input('in_stock_quantity');
        $in_unit_of_measure = $request->input('in_unit_of_measure');
        $in_waste_percent = $request->input('in_waste_percent');
        $in_cost_per_unit = $request->input('in_cost_per_unit');
        $in_notes = $request->input('in_notes');

        $result_array = array();

        $ingredient_info = new FnbIngredients();

        $ingredient_info->in_ingredient_name = $in_ingredient_name;
        $ingredient_info->in_stock_quantity = $in_stock_quantity;
        $ingredient_info->in_unit_of_measure = $in_unit_of_measure;
        $ingredient_info->in_waste_percent = $in_waste_percent;
        $ingredient_info->in_cost_per_unit = $in_cost_per_unit;
        $ingredient_info->in_notes = $in_notes;
        $ingredient_info->in_item_id = $item_id;

        $ingredient_info->in_line_cost = $in_stock_quantity * $in_cost_per_unit * (1 + $in_waste_percent);

        $ingredient_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Informations Has been saved';
        $result_array['item_id'] = $item_id;

        return Response()->json($result_array);
    }

    public function DeleteIngredientInfo(Request $request)
    {
        $in_id = $request->input('in_id');

        $ingredient_info = FnbIngredients::find($in_id);
        $ingredient_info->in_is_deleted = 1;
        $ingredient_info->in_deleted_by = Session('user_id');
        $ingredient_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
