<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Companies;
use App\Models\FnB\Floor;
use App\Models\FnB\FnbIngredients;
use App\models\FnB\FnbMenuItem;
use App\models\Inventory\Products;
use App\models\System\Currency;
use App\models\System\Units;
use Config;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class FnbReceipesController extends Controller
{
    public function index()
    {
        $data = array();
        return Response()->view('fnb.receipes.fnb-receipes', $data);
    }

    public function DisplayListReceipes(Request $request)
    {
        $general_search = $request->input('general_search');

        $receipe_cond = FnbMenuItem::whereMiIsDeleted(0);

        if (!empty($general_search)) {
            $receipe_cond->where('mi_item_name', 'LIKE', '%' . $general_search . '%');
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
        $mi_id = $request->input('mi_id');
        $ingredients = FnbIngredients::with(['Product', 'Unit', 'Item'])
            ->where('in_item_id', $mi_id)
            ->where('in_is_deleted', 0)
            ->get();

        $item = FnbMenuItem::find($mi_id);
        $lst_units = Units::whereSuIsDeleted(0)->get();
        $lst_products = Products::wherePProductIsDeleted(0)
            ->wherePProductType(21)
            ->get();

        $data = array(
            "lst_ingredients" => $ingredients,
            "item"            => $item,
            "lst_units"       => $lst_units,
            "lst_products"    => $lst_products,
        );

        $result_array = array();
        $result_array['display'] = view("fnb.receipes.ReceipeIngredients", $data)->render();

        return Response()->json($result_array);
    }


    public function DisplayListIngredients(Request $request)
    {
        $mi_id = $request->input('mi_id');

        $lst_ingredients = FnbIngredients::with(['Product', 'Unit', 'Item'])
            ->where('in_item_id', $mi_id)
            ->where('in_is_deleted', 0)
            ->get();

        $lst_units = Units::whereSuIsDeleted(0)->get();
        $lst_products = Products::wherePProductIsDeleted(0)
            ->wherePProductType(21)
            ->get();

        $data = array(
            "lst_ingredients" => $lst_ingredients,
            "lst_units"       => $lst_units,
            "lst_products"    => $lst_products,
        );

        $result_array = array();
        $result_array['display'] = view("fnb.receipes.listingredients", $data)->render();

        return Response()->json($result_array);
    }


    public function addIngredient(Request $request)
    {
        $item_id = $request->item_id;
        $lst_products = Products::wherePProductIsDeleted(0)->wherePProductType(21)->get();
        $lst_items = FnbMenuItem::whereMiIsDeleted(0)->get();
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
        $in_product_id = $request->input('in_product_id');
        $in_stock_quantity = $request->input('in_stock_quantity');
        $in_unit_of_measure = $request->input('in_unit_of_measure');
        $in_waste_percent = $request->input('in_waste_percent');
        $in_cost_per_unit = $request->input('in_cost_per_unit');
        $in_notes = $request->input('in_notes');
        $in_ingredient_name = $request->input('in_ingredient_name');
        $in_ingredient_code = $request->input('in_ingredient_code');

        $result_array = array();

        $ingredient_info = new FnbIngredients();

        $ingredient_info->in_product_id = $in_product_id;
        $ingredient_info->in_ingredient_name = $in_ingredient_name;
        $ingredient_info->in_ingredient_code = $in_ingredient_code;
        $ingredient_info->in_stock_quantity = $in_stock_quantity;
        $ingredient_info->in_unit_of_measure = $in_unit_of_measure;
        $ingredient_info->in_waste_percent = $in_waste_percent;
        $ingredient_info->in_cost_per_unit = $in_cost_per_unit;
        $ingredient_info->in_notes = $in_notes;
        $ingredient_info->in_item_id = $item_id;

        $ingredient_info->in_line_cost = $in_stock_quantity * $in_cost_per_unit * (1 + $in_waste_percent / 100);

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
    public function SaveRecipeInfo(Request $request)
    {
        $mi_id = $request->input('mi_id');
        $mi_item_description = $request->input('mi_item_description');

        $result_array = array();

        $item = FnbMenuItem::find($mi_id);

        if (!$item) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'Recipe not found';
            return Response()->json($result_array);
        }

        $item->mi_item_description = $mi_item_description;
        $item->mi_updated_by = Session('user_id');
        $item->save();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = 'Recipe information has been saved';

        return Response()->json($result_array);
    }

    public function PrintReceipePdf($mi_id)
    {
        $item = FnbMenuItem::where('mi_id', $mi_id)
            ->where('mi_is_deleted', 0)
            ->firstOrFail();

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
            'printedBy'   => session('user_fullname'),
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

        return $pdf->stream('Recipe_' . $item->mi_item_name . '.pdf');
    }

    public function DownloadReceipePdf($mi_id)
    {
        $item = FnbMenuItem::where('mi_id', $mi_id)
            ->where('mi_is_deleted', 0)
            ->firstOrFail();

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
            'printedBy'   => session('user_fullname'),
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

        return $pdf->download('Recipe_' . $item->mi_item_name . '.pdf');
    }
}
