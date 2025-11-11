<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use App\models\FnB\Item;
use App\models\FnB\Modifier;
use App\models\Inventory\Products;
use App\models\System\Currency;
use App\models\System\Units;
use Illuminate\Http\Request;
use Config;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class FnbModifiersController extends Controller
{
    public function index()
    {
        $data = array();
        return Response()->view('fnb.modifiers.fnb-modifiers', $data);
    }

    public function addModifier()
    {
        $lst_items = Products::wherePProductIsDeleted(0)->get();
        $lst_currencies = Currency::get();
        $lst_units = Units::whereSuIsDeleted(0)->get();

        $data = array(
            "lst_items" => $lst_items,
            "lst_currencies" => $lst_currencies,
            "lst_units" => $lst_units
        );
        return Response()->view('fnb.modifiers.addform', $data);
    }

    public function DisplayList(Request $request)
    {
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');

        if ($page_number > 1)
            $skip = ($page_number - 1) * $nbr_rows_per_pages;
        else
            $skip = 0;


        $modifiers_cond = Modifier::whereMIsDeleted(0);

        if (!empty($general_search)) {
            $modifiers_cond->where('m_modifier_name', 'LIKE', '%' . $general_search . '%');
        }

        $modifiers_count = $modifiers_cond->count();

        $total_pages = ceil($modifiers_count / $nbr_rows_per_pages);
        $total_pages = intval($total_pages);

        $lst_modifiers = $modifiers_cond
            ->skip($skip)
            ->take($nbr_rows_per_pages)
            ->get();

        $data = array(
            "lst_modifiers" => $lst_modifiers,
        );

        $result_array = array();
        $result_array['display'] = view("fnb.modifiers.listmodifiers", $data)->render();
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }

    public function SaveModifier(Request $request)
    {
        $m_id = $request->input('m_id');
        $m_modifier_name = $request->input('m_modifier_name');
        $m_modifier_description = $request->input('m_modifier_description');
        $m_item_id = $request->input('m_item_id');
        $m_quantity = $request->input('m_quantity');
        $m_unit_id = $request->input('m_unit_id');
        $m_currency_id = $request->input('m_currency_id');
        $m_quantity = $request->input('m_quantity');
        $m_cost_modifier = $request->input('m_cost_modifier');
        $m_price_modifier = $request->input('m_price_modifier');
        $m_is_active = $request->input('m_is_active') ? 1 : 0;
         $m_is_required = $request->input('m_is_required') ? 1 : 0;
          $m_is_single = $request->input('m_is_single') ? 1 : 0;

        $result_array = array();

        $modifier_info = new Modifier();
        if ($m_id != null) {
            $modifier_info = Modifier::find($m_id);
        }

        $modifier_info->m_modifier_name = $m_modifier_name;
        $modifier_info->m_item_id = $m_item_id;
        $modifier_info->m_modifier_description = $m_modifier_description;
        $modifier_info->m_unit_id = $m_unit_id;
        $modifier_info->m_currency_id = $m_currency_id;
        $modifier_info->m_quantity = $m_quantity;
        $modifier_info->m_cost_modifier = $m_cost_modifier;
        $modifier_info->m_price_modifier = $m_price_modifier;
        $modifier_info->m_is_active = $m_is_active;
        $modifier_info->m_is_required = $m_is_required;
        $modifier_info->m_is_single = $m_is_single;

        $modifier_info->save();

        $m_id = $modifier_info->m_id;

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Information Has been saved';

        return Response()->json($result_array);
    }

    public function editModifier($m_id)
    {
        $modifier_info = Modifier::find($m_id);
        $lst_items = Products::wherePProductIsDeleted(0)->get();
        $lst_currencies = Currency::get();
        $lst_units = Units::whereSuIsDeleted(0)->get();

        $data = array(
            "modifier" => $modifier_info,
            "lst_items" => $lst_items,
            "lst_currencies" => $lst_currencies,
            "lst_units" => $lst_units
        );
        return view('fnb.modifiers.editform', $data);
    }

    public function DeleteModifier(Request $request)
    {
        $m_id = $request->input('m_id');

        $modifier_info = Modifier::find($m_id);
        $modifier_info->m_is_deleted = 1;
        $modifier_info->m_deleted_by = Session('user_id');
        $modifier_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
