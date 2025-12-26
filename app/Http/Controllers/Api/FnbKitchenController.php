<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Users\Users;
use App\models\FnB\KitchenStations;
use App\models\System\SystemStatus;

class FnbKitchenController extends Controller
{
    public function GetListKitchenOrderStatus(Request $request)
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

        $lst_kitchen_status = SystemStatus::whereSsIsDeleted(0)->whereSsStatusType('kitchen_order_statuses')->get();

        $kitchen_status_array = [];
        foreach ($lst_kitchen_status as $index => $kitchen_status_info) {
            $kitchen_status_array[$index]['ss_id']   = $kitchen_status_info->ss_id;
            $kitchen_status_array[$index]['ss_status_title'] = $kitchen_status_info->ss_status_title;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_kitchen_statuses'] = $kitchen_status_array;

        return Response()->json($result_array);
    }

    public function GetStationsName(Request $request)
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

        $lst_kitchens = KitchenStations::whereKsIsDeleted(0)->get();

        $kitchens_array = [];
        foreach ($lst_kitchens as $index => $kitchen_info) {
            $kitchens_array[$index]['ks_id']   = $kitchen_info->ks_id;
            $kitchens_array[$index]['ks_name'] = $kitchen_info->ks_name;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_kitchens'] = $kitchens_array;

        return Response()->json($result_array);
    }
}
