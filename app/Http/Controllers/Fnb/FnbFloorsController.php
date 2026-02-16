<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\System\Companies;
use App\models\Fnb\Floor;
use App\models\Sales\Stores;
use Config;

class FnbFloorsController extends Controller
{
    public function index()
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $data = array(
            "lst_companies" => $lst_companies
        );
        return Response()->view('fnb.fnb', $data);
    }

    public function addFloor()
    {
        $lst_stores = Stores::wherePsIsDeleted(0)->get();
        $data = array(
            "lst_stores" => $lst_stores,
        );
        return Response()->view('fnb.addform', $data);
    }

    public function DisplayListFloors(Request $request)
    {
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        $default_company_id = session('default_company_id');

        if ($page_number > 1)
            $skip = ($page_number - 1) * $nbr_rows_per_pages;
        else
            $skip = 0;


        $floors_cond = Floor::whereFlIsDeleted(0)->where('fl_branch_id', $default_company_id);

        if (!empty($general_search)) {
            $floors_cond->where('fl_floor_name', 'LIKE', '%' . $general_search . '%');
        }

        $floors_count = $floors_cond->count();

        $total_pages = ceil($floors_count / $nbr_rows_per_pages);
        $total_pages = intval($total_pages);

        $list_floors = $floors_cond
            ->leftJoin('pos_stores', 'pos_stores.ps_id', '=', 'fnb_floor.fl_store_id')
            ->select('fnb_floor.*', 'pos_stores.ps_store_name')
            ->skip($skip)->take($nbr_rows_per_pages)->get();
        $data = array(
            "lst_floors" => $list_floors,
        );

        $result_array = array();
        $result_array['display'] = view("fnb.listfloors", $data)->render();
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }

    public function SaveFloorInfo(Request $request)
    {
        $fl_id                          = $request->input('fl_id');
        $default_company_id = session('default_company_id');
        $fl_floor_name                  = $request->input('fl_floor_name');
        $fl_store_id                    = $request->input('fl_store_id');

        $result_array = array();

        $floor_info = new Floor();
        if ($fl_id != null) {
            $floor_info = Floor::find($fl_id);
        }


        $floor_info->fl_branch_id          = $default_company_id;
        $floor_info->fl_floor_name          = $fl_floor_name;
        $floor_info->fl_store_id            = $fl_store_id;

        $floor_info->save();

        $fl_id = $floor_info->fl_id;

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Floor information Information Has been saved';

        return Response()->json($result_array);
    }

    public function editFloor($fl_id)
    {
        $floor_info = Floor::find($fl_id);
        $lst_stores = Stores::wherePsIsDeleted(0)->get();

        $data = array(
            "floor_info" => $floor_info,
            "lst_stores" => $lst_stores,
        );
        return view('fnb.editform', $data);
    }

    public function deleteFloorInfo(Request $request)
    {
        $fl_id = $request->input('fl_id');

        $floor_info = Floor::find($fl_id);
        $floor_info->fl_is_deleted = 1;
        $floor_info->fl_deleted_by = Session('user_id');
        $floor_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
