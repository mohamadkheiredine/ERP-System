<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FnB\FnbMenuItemModifier;
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
            $modifiers_array[$index]['m_quantity'] = $modifier_info->m_quantity;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_modifiers'] = $modifiers_array;

        return Response()->json($result_array);
    }

    public function GetModifiersPerItem(Request $request)
    {
        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');
        $item_id = $request->input('item_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $lst_items = FnbMenuItemModifier::where('fk_menu_item_id', $item_id)->where('im_is_deleted', 0)->get();
        $modifiers_array = array();
        foreach ($lst_items as $item) {
            $modifiers_array[] = [
                'im_id' => $item->im_id,
                'fk_modifier_id' => $item->fk_modifier_id,
                'im_product_id' => $item->im_product_id,
                'im_quantity' => $item->Modifier->m_quantity ?? 0,
            ];
        }

        return response()->json([
            'is_error' => 0,
            'data' => $modifiers_array
        ]);
    }
}
