<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\models\Accounting\ChartAccounts;
use Illuminate\Http\Request;
use App\models\System\Currency;
use App\models\Users\Users;
use App\models\Accounting\DefaultAccounts;
use App\models\Billing\PaymentTypes;

class AccountingController extends Controller
{
    public function GetGLAccounts(Request $request)
    {

        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256',$c_hash);
        $result_array        = array();


        if( $c_hash != $g_hash )
        {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $lst_accounts = ChartAccounts::whereAaIsDeleted(0)->get();

        $accounts_array = array();

        foreach ( $lst_accounts as  $index => $account_info )
        {
            $accounts_array[$index]['aa_id'] = $account_info->aa_id;
            $accounts_array[$index]['aa_account_ref'] = $account_info->aa_account_ref;
            $accounts_array[$index]['aa_account_label'] = $account_info->aa_account_label;
            $accounts_array[$index]['aa_account_information'] = $account_info->aa_account_information;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_accounts'] = $accounts_array;

        return Response()->json($result_array);
    }

}
