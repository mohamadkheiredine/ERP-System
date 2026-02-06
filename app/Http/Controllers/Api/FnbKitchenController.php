<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\models\FnB\FnbOrderItems;
use App\models\FnB\FnbOrders;
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

    public function UpdateKitchenStatus(Request $request)
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

        $oi_id   = $request->input('oi_id');
        $new_status = $request->input('oi_kitchen_status');
        $order_item = FnbOrderItems::find($oi_id);
        if (!$order_item) {
            return response()->json([
                "is_error" => 1,
                "error_msg" => "Order item not found."
            ]);
        }


        $old_status = $order_item->oi_kitchen_status;

        $new_status = $request->input('oi_kitchen_status');
        $order_item = FnbOrderItems::find($oi_id);

        $old_status = $order_item->oi_kitchen_status;

        $statusInfo = SystemStatus::where('ss_id', $new_status)
            ->select('ss_status_type', 'ss_is_closed')
            ->first();

        $isClosed = false;

        if (
            $statusInfo &&
            $statusInfo->ss_status_type === 'kitchen_order_statuses' &&
            (int)$statusInfo->ss_is_closed === 1
        ) {
            $isClosed = true;
        }

        $order_item->oi_kitchen_status = $new_status;

        $order_item->oi_is_kitchen_closed = $isClosed ? 1 : 0;

        $order_item->save();

        $orderId = $order_item->oi_order_id;

        $remaining = FnbOrderItems::where('oi_order_id', $orderId)
            ->where('oi_is_deleted', 0)
            ->where('oi_kitchen_status', '!=', $new_status)
            ->count();

        if ($remaining === 0) {
            FnbOrders::where('fo_id', $orderId)
                ->update(['fo_kitchen_status' => $new_status]);
        }


        return response()->json([
            "is_error" => 0,
            "error_msg" => "",
            "message" => "Kitchen status updated successfully.",
            "old_status" => $old_status,
            "new_status" => $new_status,
            "is_closed" => $isClosed,
            "order_id" => $orderId,
            "oi_id" => $oi_id
        ]);
    }
}
