<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Users\Users;
use App\models\Sales\PosAllowedCurrencies;

class PosAllowedCurrenciesController extends Controller
{

    public function GetAllowedCurrencies(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash = $request->input('g_hash');
        $store_id = $request->input("store_id");
        $company_id = $request->input("company_id");

        $user_info           = Users::find($user_id);
        if (!$user_info) {
            return response()->json([
                'is_error' => 1,
                'error_message' => 'User not found'
            ], 404);
        }

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();


        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array, 401);
        }


        $lst_allowed_currencies = PosAllowedCurrencies::where('ac_store_id', $store_id)->where('ac_company_id', $company_id)->get();


        $currencies_array = array();

        foreach ($lst_allowed_currencies as  $index => $currency_info) {

            $currencies_array[$index]['ac_id'] = $currency_info->ac_id;
            $currencies_array[$index]['ac_store_id'] = $currency_info->ac_store_id;
            $currencies_array[$index]['ac_company_id'] = $currency_info->ac_company_id;
            $currencies_array[$index]['ac_currency_id'] = $currency_info->ac_currency_id;
            $currencies_array[$index]['ac_rate_to_original'] = $currency_info->ac_rate_to_original;
            $currencies_array[$index]['cc_currency_code'] = $currency_info->Currency->cc_currency_code;
            $currencies_array[$index]['cc_currency_name'] = $currency_info->Currency->cc_currency_name;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "the allowed currencies is obtained successfully";
        $result_array['allowed_currencies'] = $currencies_array;

        return Response()->json($result_array);
    }

    public function SaveCurrencyRate(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash = $request->input('g_hash');
        $ac_ids = $request->input('ac_id');
        $ac_rates = $request->input('ac_rate_to_original');
        $user_info           = Users::find($user_id);

        if (!$user_info) {
            return response()->json([
                'is_error' => 1,
                'error_message' => 'User not found'
            ], 404);
        }

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array, 401);
        }


        $caseSql = '';
        foreach ($ac_ids as $index => $id) {
            $rate = $ac_rates[$index];
            $caseSql .= "WHEN $id THEN $rate ";
        }

        $ids = implode(',', $ac_ids);

        \DB::update("UPDATE pos_allowed_currencies
                 SET ac_rate_to_original = CASE ac_id $caseSql END
                 WHERE ac_id IN ($ids)");


        $result_array['error_msg'] = 'Currency rate updated successfully';
        $result_array['is_error'] = 0;

        return Response()->json($result_array);
    }
    /**
     * API to add allowed currency
     * @author Mohamad Kheiredine
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddAllowedCurrency(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash = $request->input('g_hash');
        $store_id = $request->input('store_id');
        $company_id = $request->input('company_id');
        $currency_id = $request->input('ac_currency_id');
        $rate = $request->input('ac_rate_to_original', 1);

        $user_info = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array, 401);
        }

        $exists = PosAllowedCurrencies::where('ac_store_id', $store_id)
            ->where('ac_company_id', $company_id)
            ->where('ac_currency_id', $currency_id)
            ->first();

        if ($exists) {
            return response()->json([
                'is_error' => 1,
                'error_message' => 'Currency already allowed'
            ]);
        }

        PosAllowedCurrencies::create([
            'ac_store_id'          => $store_id,
            'ac_company_id'        => $company_id,
            'ac_currency_id'       => $currency_id,
            'ac_rate_to_original'  => $rate
        ]);

        return response()->json([
            'is_error' => 0,
            'error_msg' => 'Currency added successfully'
        ]);
    }

    /**
     * API to edit allowed currency
     * @author Mohammed kheiredine
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function EditAllowedCurrency(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');
        $ac_id   = $request->input('ac_id');
        $rate    = $request->input('ac_rate_to_original');

        $user_info = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array, 401);
        }

        $currency = PosAllowedCurrencies::find($ac_id);
        if (!$currency) {
            return response()->json(['is_error' => 1, 'error_message' => 'Currency not found']);
        }

        $currency->ac_rate_to_original = $rate;
        $currency->save();

        return response()->json([
            'is_error' => 0,
            'error_msg' => 'Currency updated successfully'
        ]);
    }

    /**
     * API to delete allowed currency
     * @author Mohammed kheiredine
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteAllowedCurrency(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');
        $ac_id   = $request->input('ac_id');

        $user_info = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array, 401);
        }

        PosAllowedCurrencies::where('ac_id', $ac_id)->delete();

        return response()->json([
            'is_error' => 0,
            'error_msg' => 'Currency deleted successfully'
        ]);
    }
}
