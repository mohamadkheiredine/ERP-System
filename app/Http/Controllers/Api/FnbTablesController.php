<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\models\FnB\Tables;
use Illuminate\Http\Request;
use App\models\Users\Users;

class FnbTablesController extends Controller
{
    public function GetListTables(Request $request)
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

        $lst_tables = Tables::whereFtIsDeleted(0)->get();

        $tables_array = [];
        foreach ($lst_tables as $index => $table_info) {
            $tables_array[$index]['ft_id']   = $table_info->ft_id;
            $tables_array[$index]['ft_label'] = $table_info->ft_label;
            $tables_array[$index]['ft_number_seats'] = $table_info->ft_number_seats;
            $tables_array[$index]['ft_status_id'] = $table_info->ft_status_id;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_tables'] = $tables_array;

        return Response()->json($result_array);
    }
}
