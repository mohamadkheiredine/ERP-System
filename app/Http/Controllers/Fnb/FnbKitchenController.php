<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Companies;
use App\Models\FnB\KitchenStations;
use Config;

class FnbKitchenController extends Controller
{
    public function index()
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $data = array(
            "lst_companies" => $lst_companies
        );
        return Response()->view('fnb.kitchen.fnb-kitchen', $data);
    }

    public function addKitchen()
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $data = array(
            "lst_companies" => $lst_companies
        );
        return Response()->view('fnb.kitchen.addform', $data);
    }

    public function DisplayListKitchens(Request $request)
    {
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        $ps_company_id = $request->input('ps_company_id');

        if ($page_number > 1)
            $skip = ($page_number - 1) * $nbr_rows_per_pages;
        else
            $skip = 0;


        $kitchen_cond = KitchenStations::whereKsIsDeleted(0);

        if (!empty($ps_company_id) && $ps_company_id != 0) {
            $kitchen_cond = $kitchen_cond->where('ks_branch_id', $ps_company_id);
        }

        if (!empty($general_search)) {
            $kitchen_cond->where('ks_name', 'LIKE', '%' . $general_search . '%');
        }

        $kitchen_count = $kitchen_cond->count();

        $total_pages = ceil($kitchen_count / $nbr_rows_per_pages);
        $total_pages = intval($total_pages);

        $lst_kitchens = $kitchen_cond->skip($skip)->take($nbr_rows_per_pages)->get();

        $data = array(
            "lst_kitchens" => $lst_kitchens,
        );

        $result_array = array();
        $result_array['display'] = view("fnb.kitchen.listkitchen", $data)->render();
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }

    public function SaveKitchenInfo(Request $request)
    {
        $ks_id = $request->input('ks_id');
        $ks_name = $request->input('ks_name');
        $ks_description = $request->input('ks_description');
        $ks_active = $request->has('ks_active') ? 1 : 0;
        $ps_company_id = $request->input('ps_company_id');

        $result_array = array();

        $kitchen_info = new KitchenStations();
        if ($ks_id != null) {
            $kitchen_info = KitchenStations::find($ks_id);
        }

        $kitchen_info->ks_name = $ks_name;
        $kitchen_info->ks_description = $ks_description;
        $kitchen_info->ks_branch_id = $ps_company_id;
        $kitchen_info->ks_is_active = $ks_active;

        $kitchen_info->save();

        $ks_id = $kitchen_info->ks_id;

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Kitchen Information Has been saved';

        return Response()->json($result_array);
    }

    public function editKitchen($ks_id)
    {
        $kitchen_info = KitchenStations::find($ks_id);
        $lst_companies = Companies::whereCdIsDeleted(0)->get();

        $data = array(
            "lst_companies" => $lst_companies,
            "kitchen_info" => $kitchen_info,
        );
        return view('fnb.kitchen.editform', $data);
    }

    public function DeleteKitchenInfo(Request $request)
    {
        $ks_id = $request->input('ks_id');

        $kitchen_info = KitchenStations::find($ks_id);
        $kitchen_info->ks_is_deleted = 1;
        $kitchen_info->ks_deleted_by = Session('user_id');
        $kitchen_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
