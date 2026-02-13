<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use App\models\FnB\FnbPrinters;
use Illuminate\Http\Request;
use App\models\System\Companies;
use App\models\FnB\KitchenStations;
use App\models\Inventory\WareHouses;
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
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
        $data = array(
            "lst_companies" => $lst_companies,
            "lst_warehouses" => $lst_warehouses,
        );
        return Response()->view('fnb.kitchen.addform', $data);
    }

    public function DisplayListKitchens(Request $request)
    {
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        $default_company_id = session('default_company_id');

        if ($page_number > 1)
            $skip = ($page_number - 1) * $nbr_rows_per_pages;
        else
            $skip = 0;


        $kitchen_cond = KitchenStations::whereKsIsDeleted(0);

        $kitchen_cond = $kitchen_cond->where('ks_branch_id', $default_company_id);

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
        $ks_warehouse_id = $request->input('ks_warehouse_id');
        $default_company_id = session('default_company_id');
        $printer_ip = $request->input('printer_ip');
        $printer_port = $request->input('printer_port');

        $result_array = array();

        $kitchen_info = new KitchenStations();
        if ($ks_id != null) {
            $kitchen_info = KitchenStations::find($ks_id);
        }

        if($ks_warehouse_id == "")
        {
            $warehouse_info = new WareHouses();
            $warehouse_info->w_company_id = $default_company_id;
            $warehouse_info->w_warehouse_ref = substr($ks_name,0,5);
            $warehouse_info->w_warehouse_name = $ks_name;
            $warehouse_info->w_warehouse_adddress = "";
            $warehouse_info->w_owner_id = session('user_id');
            $warehouse_info->w_warehouse_status = 1;
            $warehouse_info->save();
        }


        $kitchen_info->ks_name = $ks_name;
        $kitchen_info->ks_description = $ks_description;
        $kitchen_info->ks_branch_id = $default_company_id;
        $kitchen_info->ks_is_active = $ks_active;
        $kitchen_info->ks_warehouse_id = $ks_warehouse_id;

        $kitchen_info->save();

        $ks_id = $kitchen_info->ks_id;
        $kitchen_info->ks_code = 'K' . $ks_id;
        $kitchen_info->save();

        $printer = FnbPrinters::where('ks_id', $ks_id)->first();

        if (!$printer) {
            $printer = new FnbPrinters();
            $printer->ks_id = $ks_id;
        }
        $printer->printer_ip = $printer_ip;
        $printer->printer_port = $printer_port;
        $printer->printer_name = $ks_name . 'Printer';
        $printer->is_enabled = $ks_active;
        $printer->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Kitchen Information Has been saved';

        return Response()->json($result_array);
    }

    public function editKitchen($ks_id)
    {
        $kitchen_info = KitchenStations::find($ks_id);
        $lst_warehouses = WareHouses::all();

        $data = array(
            "kitchen_info" => $kitchen_info,
            "lst_warehouses" => $lst_warehouses,
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
