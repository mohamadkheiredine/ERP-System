<?php

namespace App\Http\Controllers\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Companies;
use App\Models\FnB\Floor;
use App\Models\FnB\Tables;
use Config;

class FnbTablesController extends Controller
{
    public function index()
    {
        $lst_floors = Floor::whereFlIsDeleted(0)->get();
        $data = array(
            "lst_floors" => $lst_floors
        );
        return Response()->view('fnb.tables.fnb-tables', $data);
    }

    public function addTable()
    {
        $lst_floors = Floor::whereFlIsDeleted(0)->get();
        $data = array(
            "lst_floors" => $lst_floors
        );
        return Response()->view('fnb.tables.addform', $data);
    }

    public function DisplayListTables(Request $request)
    {
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        $fl_id = $request->input('fl_id');

        if ($page_number > 1)
            $skip = ($page_number - 1) * $nbr_rows_per_pages;
        else
            $skip = 0;


        $tables_cond = Tables::whereFtIsDeleted(0);

        if (!empty($fl_id) && $fl_id != 0) {
            $tables_cond = $tables_cond->where('ft_floor_id', $fl_id);
        }

        if (!empty($general_search)) {
            $tables_cond->where('ft_label', 'LIKE', '%' . $general_search . '%');
        }

        $tables_count = $tables_cond->count();

        $total_pages = ceil($tables_count / $nbr_rows_per_pages);
        $total_pages = intval($total_pages);

        $list_tables = $tables_cond
            ->leftJoin('fnb_floor', 'fnb_table.ft_floor_id', '=', 'fnb_floor.fl_id')
            ->select(
                'fnb_table.*',
                'fnb_floor.fl_floor_name' // bring the floor name directly
            )
            ->skip($skip)
            ->take($nbr_rows_per_pages)
            ->get();

        $data = array(
            "lst_tables" => $list_tables,
        );

        $result_array = array();
        $result_array['display'] = view("fnb.tables.listtables", $data)->render();
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }

    public function SaveTableInfo(Request $request)
    {
        $ft_id = $request->input('ft_id');
        $ft_label = $request->input('ft_label');
        $ft_floor_id = $request->input('ft_floor_id');
        $ft_capacity = $request->input('ft_capacity');
        $ft_x_pos = $request->input('ft_x_pos');
        $ft_y_pos = $request->input('ft_y_pos');
        $ft_rotation = $request->input('ft_rotation');
        $ft_shape = $request->input('ft_shape');
        $ft_color = $request->input('ft_color');
        $ft_active = $request->input('ft_active') ? 1 : 0;

        $result_array = array();

        $tables_info = new Tables();
        if ($ft_id != null) {
            $tables_info = Tables::find($ft_id);
        }

        $tables_info->ft_floor_id = $ft_floor_id;
        $tables_info->ft_label = $ft_label;
        $tables_info->ft_capacity = $ft_capacity;
        $tables_info->ft_x_pos = $ft_x_pos;
        $tables_info->ft_y_pos = $ft_y_pos;
        $tables_info->ft_rotation = $ft_rotation;
        $tables_info->ft_shape = $ft_shape;
        $tables_info->ft_color = $ft_color;
        $tables_info->ft_active = $ft_active;

        $tables_info->save();

        $ft_id = $tables_info->ft_id;

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Store information Information Has been saved';

        return Response()->json($result_array);
    }

    public function editTable($ft_id)
    {
        $table_info = Tables::find($ft_id);
        $lst_floors = Floor::whereFlIsDeleted(0)->get();

        $data = array(
            "lst_floors" => $lst_floors,
            "table_info" => $table_info,
        );
        return view('fnb.tables.editform', $data);
    }

    public function DeleteTableInfo(Request $request)
    {
        $ft_id = $request->input('ft_id');

        $table_info = Tables::find($ft_id);
        $table_info->ft_is_deleted = 1;
        $table_info->ft_deleted_by = Session('user_id');
        $table_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
