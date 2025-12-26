<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\models\FnB\FnbPosShift;
use Illuminate\Http\Request;
use DB;
use App\models\System\Currency;
use App\models\Users\Users;
use App\models\FnB\FnbOrders;
use App\models\FnB\FnbSessionFields;

class FnbShiftController extends Controller
{
    public function OpenShift(Request $request)
    {
        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');
        $currencies = $request->input('currencies');

        // "currencies": [
        //     { "currency_id": 1, "open_value": 1000 },
        //     { "currency_id": 2, "open_value": 1220 },
        //     { "currency_id": 3, "open_value": 10000 }
        // ]

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $openShift = FnbPosShift::where('ps_cashier_id', $user_id)
            ->where('ps_status', 'OPEN')
            ->first();

        if ($openShift) {
            return response()->json([
                'is_error' => 1,
                'error_msg' => 'You already have an open shift',
                'shift_id' => $openShift->ps_id
            ]);
        }

        DB::beginTransaction();

        try {
            $shift = new FnbPosShift();
            $shift->ps_cashier_id = $user_id;
            $shift->ps_opened_at  = now();
            $shift->ps_status     = 'OPEN';
            $shift->save();

            foreach ($currencies as $row) {

                $cash = new FnbSessionFields();
                $cash->sf_shift_id     = $shift->ps_id;
                $cash->sf_cashier_id   = $user_id;
                $cash->sf_currency_id  = $row['currency_id'];
                $cash->sf_open_value   = $row['open_value'];
                $cash->sf_expected_value = 0;
                $cash->sf_close_value  = 0;
                $cash->save();
            }

            DB::commit();

            return response()->json([
                'is_error' => 0,
                'shift_id' => $shift->ps_id,
                'message'  => 'Shift opened successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'is_error' => 1,
                'error_msg' => $e->getMessage()
            ]);
        }
    }

    public function CloseShift(Request $request)
    {
        $g_hash        = $request->input('g_hash');
        $user_id       = $request->input('user_id');
        $closing_cash  = $request->input('closing_cash');
        $notes         = $request->input('notes', '');


        $user_info = Users::find($user_id);
        $result_array = array();

        if (!$user_info) {
            return response()->json([
                'is_error' => 1,
                'error_msg' => 'Invalid user'
            ]);
        }

        $c_hash = "POS567"
            . $user_info->u_username
            . $user_info->u_fullname
            . $user_info->u_email
            . "POS567";

        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $shift = FnbPosShift::where('ps_cashier_id', $user_id)
            ->where('ps_status', 'OPEN')
            ->first();

        if (!$shift) {
            return response()->json([
                'is_error' => 1,
                'error_msg' => 'No open shift found'
            ]);
        }

        //   closing_cash example:
        //   {
        //     "1": 950.00,
        //     "2": 1200.00,
        //     "3": 10000000
        //  }

        $total_difference = 0;
        foreach ($closing_cash as $currency_id => $close_value) {

            $sessionField = FnbSessionFields::where('sf_shift_id', $shift->ps_id)
                ->where('sf_currency_id', $currency_id)
                ->first();

            if (!$sessionField) {
                continue;
            }

            $difference = $close_value - $sessionField->sf_expected_value;

            $sessionField->sf_close_value    = (float) $close_value;
            $sessionField->save();
            $total_difference += $difference;
        }

        $shift->ps_closed_at = now();
        $shift->ps_status    = 'CLOSED';
        $shift->ps_notes     = $notes;
        $shift->ps_difference = $total_difference;
        $shift->save();

        return response()->json([
            'is_error' => 0,
            'shift_id' => $shift->ps_id,
            'message'  => 'Shift closed successfully'
        ]);
    }

    public function GetOpenCurrencies(Request $request)
    {
        $g_hash  = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return response()->json([
                'is_error' => 1,
                'error_msg' => 'Invalid user'
            ]);
        }

        $c_hash = "POS567"
            . $user_info->u_username
            . $user_info->u_fullname
            . $user_info->u_email
            . "POS567";

        $c_hash = hash('sha256', $c_hash);

        if ($c_hash !== $g_hash) {
            return response()->json([
                'is_error' => 1,
                'error_msg' => 'hash sequence is not valid !!'
            ]);
        }

        $shift = FnbPosShift::where('ps_cashier_id', $user_id)
            ->where('ps_status', 'OPEN')
            ->first();

        if (!$shift) {
            return response()->json([
                'is_error' => 1,
                'error_msg' => 'No open shift'
            ]);
        }

        $sessionFields = FnbSessionFields::where('sf_shift_id', $shift->ps_id)->get();

        if ($sessionFields->isEmpty()) {
            return response()->json([
                'is_error' => 0,
                'data' => []
            ]);
        }

        $orders = FnbOrders::whereIn('fo_payment_status', ['paid', 'partial'])
            ->whereBetween('fo_order_datetime', [
                $shift->ps_opened_at,
                now()
            ])
            ->get();

        $expectedByCurrency = $orders
            ->groupBy('fo_currency_id')
            ->map(fn($items) => $items->sum('fo_paid_amount'));

        $currencies = Currency::whereIn(
            'cc_id',
            $sessionFields->pluck('sf_currency_id')
        )
            ->get()
            ->keyBy('cc_id');

        $result = [];

        foreach ($sessionFields as $sf) {
            $expected = $expectedByCurrency[$sf->sf_currency_id] ?? 0;
            $currency = $currencies[$sf->sf_currency_id] ?? null;

            $sf->sf_expected_value = $expected;
            $sf->save();

            $result[] = [
                'currency_id'    => $sf->sf_currency_id,
                'currency_code'  => $currency->cc_currency_code ?? '',
                'currency_name'  => $currency->cc_currency_name ?? '',
                'open_value'     => $sf->sf_open_value,
                'expected_value' => $expected,
            ];
        }

        return response()->json([
            'is_error' => 0,
            'data' => $result
        ]);
    }
}
