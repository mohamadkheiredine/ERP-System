<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Users\Users;
use App\models\FnB\Floor;

class FnbFloorsController extends Controller
{
    /**
     * @author Mohammed kheiredine>
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetListFloors(Request $request)
    {
        $g_hash   = $request->input('g_hash');
        $user_id  = $request->input('user_id');
        $store_id = $request->input('store_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']  = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $store_id = $request->input('store_id');

        $query = Floor::whereFlIsDeleted(0)
            ->leftJoin('pos_stores', 'pos_stores.ps_id', '=', 'fnb_floor.fl_store_id')
            ->select(
                'fnb_floor.fl_id',
                'fnb_floor.fl_floor_name',
                'fnb_floor.fl_store_id',
                'fnb_floor.fl_sort_order',
                'pos_stores.ps_store_name'
            );

        if (!empty($store_id)) {
            $query->where('fnb_floor.fl_store_id', $store_id);
        }

        $lst_floors = $query->orderBy('fnb_floor.fl_sort_order')->get();

        $floors_array = [];
        foreach ($lst_floors as $index => $floor_info) {
            $floors_array[$index]['fl_id']         = $floor_info->fl_id;
            $floors_array[$index]['fl_floor_name']  = $floor_info->fl_floor_name;
            $floors_array[$index]['fl_store_id']    = $floor_info->fl_store_id;
            $floors_array[$index]['fl_sort_order']  = $floor_info->fl_sort_order;
            $floors_array[$index]['store_name']     = $floor_info->ps_store_name;
        }

        $result_array['is_error']    = 0;
        $result_array['error_msg']   = '';
        $result_array['lst_floors']  = $floors_array;

        return Response()->json($result_array);
    }
}
