<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\models\FnB\FnbPosShift;
use App\models\FnB\FnbSessionFields;
use App\models\Sales\Orders;
use App\models\Sales\Terminals;
use App\models\Sales\StoreEmployees;
use App\models\System\Currency;
use App\models\Users\Users;
use Illuminate\Http\Request;
use DB;

class RetailPosShiftController extends Controller
{
    private function getTerminalForUser(int $user_id): ?Terminals
    {
        $storeEmployee = StoreEmployees::where('se_employee_id', $user_id)->first();
        if (!$storeEmployee) {
            return null;
        }

        $terminal = Terminals::where('pt_store_id', $storeEmployee->se_store_id)
            ->where('pt_is_deleted', 0)
            ->where('pt_is_active', 1)
            ->first();

        if (!$terminal) {
            $terminal                   = new Terminals();
            $terminal->pt_store_id      = $storeEmployee->se_store_id;
            $terminal->pt_terminal_name = 'POS Terminal';
            $terminal->pt_description   = 'Default terminal (auto-created)';
            $terminal->pt_is_active     = 1;
            $terminal->pt_is_deleted    = 0;
            $terminal->save();
        }

        return $terminal;
    }


    public function OpenShift(Request $request)
    {
        $user_id    = (int) $request->input('user_id');
        $g_hash     = $request->input('g_hash');
        $currencies = $request->input('currencies', []);

        $user_info = Users::find($user_id);
        if (!$user_info) {
            return response()->json(['is_error' => 1, 'error_msg' => 'Invalid user']);
        }
        $c_hash = hash('sha256', "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567");
        if ($c_hash !== $g_hash) {
            return response()->json(['is_error' => 1, 'error_msg' => 'hash sequence is not valid !!']);
        }

        $terminal = $this->getTerminalForUser($user_id);
        if (!$terminal) {
            return response()->json([
                'is_error'  => 1,
                'error_msg' => 'This user is not assigned to any store. Please assign the user to a store first.',
            ]);
        }

        $existingShift = FnbPosShift::where('ps_terminal_id', $terminal->pt_id)
            ->where('ps_status', 'OPEN')
            ->first();

        if ($existingShift) {
            return response()->json([
                'is_error'  => 1,
                'error_msg' => 'This terminal already has an open shift.',
                'shift_id'  => $existingShift->ps_id,
            ]);
        }

        DB::beginTransaction();

        try {
            $shift                = new FnbPosShift();
            $shift->ps_cashier_id  = $user_id;
            $shift->ps_terminal_id = $terminal->pt_id;
            $shift->ps_opened_at   = now();
            $shift->ps_status      = 'OPEN';
            $shift->save();

            foreach ($currencies as $row) {
                $currency = Currency::find($row['currency_id']);

                $cash                    = new FnbSessionFields();
                $cash->sf_shift_id       = $shift->ps_id;
                $cash->sf_cashier_id     = $user_id;
                $cash->sf_currency_id    = $row['currency_id'];
                $cash->sf_currency_code  = $currency ? $currency->cc_currency_code : '';
                $cash->sf_open_value     = $row['open_value'];
                $cash->sf_expected_value = 0;
                $cash->sf_close_value    = 0;
                $cash->save();
            }

            DB::commit();

            return response()->json([
                'is_error' => 0,
                'shift_id' => $shift->ps_id,
                'message'  => 'Shift opened successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'is_error'  => 1,
                'error_msg' => $e->getMessage(),
            ]);
        }
    }

    public function CloseShift(Request $request)
    {
        $user_id      = (int) $request->input('user_id');
        $g_hash       = $request->input('g_hash');
        $closing_cash = $request->input('closing_cash', []);
        $notes        = $request->input('notes', '');

        $user_info = Users::find($user_id);
        if (!$user_info) {
            return response()->json(['is_error' => 1, 'error_msg' => 'Invalid user']);
        }
        $c_hash = hash('sha256', "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567");
        if ($c_hash !== $g_hash) {
            return response()->json(['is_error' => 1, 'error_msg' => 'hash sequence is not valid !!']);
        }

        $terminal = $this->getTerminalForUser($user_id);
        if (!$terminal) {
            return response()->json([
                'is_error'  => 1,
                'error_msg' => 'This user is not assigned to any store. Please assign the user to a store first.',
            ]);
        }

        $shift = FnbPosShift::where('ps_terminal_id', $terminal->pt_id)
            ->where('ps_status', 'OPEN')
            ->first();

        if (!$shift) {
            return response()->json([
                'is_error'  => 1,
                'error_msg' => 'No open shift found.',
            ]);
        }

        $total_difference = 0;

        foreach ($closing_cash as $currency_id => $close_value) {
            $sessionField = FnbSessionFields::where('sf_shift_id', $shift->ps_id)
                ->where('sf_currency_id', $currency_id)
                ->first();

            if (!$sessionField) {
                continue;
            }

            $difference = (float) $close_value - $sessionField->sf_expected_value;

            $sessionField->sf_close_value = (float) $close_value;
            $sessionField->save();

            $total_difference += $difference;
        }

        $shift->ps_closed_at  = now();
        $shift->ps_status     = 'CLOSED';
        $shift->ps_notes      = $notes;
        $shift->ps_difference = $total_difference;
        $shift->save();

        return response()->json([
            'is_error' => 0,
            'shift_id' => $shift->ps_id,
            'message'  => 'Shift closed successfully',
        ]);
    }

    public function GetOpenCurrencies(Request $request)
    {
        $user_id = (int) $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);
        if (!$user_info) {
            return response()->json(['is_error' => 1, 'error_msg' => 'Invalid user']);
        }
        $c_hash = hash('sha256', "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567");
        if ($c_hash !== $g_hash) {
            return response()->json(['is_error' => 1, 'error_msg' => 'hash sequence is not valid !!']);
        }

        $terminal = $this->getTerminalForUser($user_id);
        if (!$terminal) {
            return response()->json([
                'is_error'  => 1,
                'error_msg' => 'This user is not assigned to any store. Please assign the user to a store first.',
            ]);
        }

        $shift = FnbPosShift::where('ps_terminal_id', $terminal->pt_id)
            ->where('ps_status', 'OPEN')
            ->first();

        if (!$shift) {
            return response()->json([
                'is_error'  => 1,
                'error_msg' => 'No open shift found.',
            ]);
        }

        $sessionFields = FnbSessionFields::where('sf_shift_id', $shift->ps_id)->get();

        if ($sessionFields->isEmpty()) {
            return response()->json([
                'is_error' => 0,
                'data'     => [],
            ]);
        }

        $salesOrders = Orders::whereSoIsDeleted(0)
            ->whereBetween('so_order_date', [$shift->ps_opened_at, now()])
            ->get();

        $expectedByCurrency = $salesOrders
            ->groupBy('so_order_currency')
            ->map(fn($items) => $items->sum('so_total_cost'));

        $currencies = Currency::whereIn(
            'cc_id',
            $sessionFields->pluck('sf_currency_id')
        )->get()->keyBy('cc_id');

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
            'data'     => $result,
        ]);
    }
}
