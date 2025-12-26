<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Users\Users;
use App\models\FnB\Modifier;

class FnbModifiersController extends Controller
{
    public function GetListModifiers(Request $request)
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

        $lst_modifiers = Modifier::whereMIsDeleted(0)->get();

        $modifiers_array = [];
        foreach ($lst_modifiers as $index => $modifier_info) {
            $modifiers_array[$index]['m_id']   = $modifier_info->m_id;
            $modifiers_array[$index]['m_modifier_name'] = $modifier_info->m_modifier_name;
            $modifiers_array[$index]['m_price_modifier'] = $modifier_info->m_price_modifier;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_modifiers'] = $modifiers_array;

        return Response()->json($result_array);
    }
}
