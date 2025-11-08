<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Companies;
use App\Models\Fnb\Floor;
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
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $data = array(
            "lst_companies" => $lst_companies
        );
        return Response()->view('fnb.addform', $data);
    }

    public function DisplayListFloors(Request $request)
    {
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        $fl_branch_id = $request->input('fl_branch_id');

        if ($page_number > 1)
            $skip = ($page_number - 1) * $nbr_rows_per_pages;
        else
            $skip = 0;


        $floors_cond = Floor::whereFlIsDeleted(0);

        if (!empty($fl_branch_id) && $fl_branch_id != 0) {
            $floors_cond = $floors_cond->where('fl_branch_id', $fl_branch_id);
        }

        if (!empty($general_search)) {
            $floors_cond->where('fl_floor_name', 'LIKE', '%' . $general_search . '%');
        }

        $floors_count = $floors_cond->count();

        $total_pages = ceil($floors_count / $nbr_rows_per_pages);
        $total_pages = intval($total_pages);

        $list_floors = $floors_cond->skip($skip)->take($nbr_rows_per_pages)->get();
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
        $fl_branch_id                  = $request->input('fl_branch_id');
        $fl_floor_name                  = $request->input('fl_floor_name');

        $result_array = array();

        $floor_info = new Floor();
        if ($fl_id != null) {
            $floor_info = Floor::find($fl_id);
        }


        $floor_info->fl_branch_id          = $fl_branch_id;
        $floor_info->fl_floor_name          = $fl_floor_name;

        $floor_info->save();

        $fl_id = $floor_info->fl_id;

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Store information Information Has been saved';

        return Response()->json($result_array);
    }

    public function editFloor($fl_id)
    {
        $floor_info = Floor::find($fl_id);
        $lst_companies = Companies::whereCdIsDeleted(0)->get();

        $data = array(
            "lst_companies" => $lst_companies,
            "floor_info" => $floor_info,
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
