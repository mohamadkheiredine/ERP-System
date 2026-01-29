<?php

/***********************************************************
 * CashflowController.php
 * Product : titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 28/1/2026
 * Developed By  : Mohamad kheiredine
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2026
 *
 * Page Description :
 ***********************************************************/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\models\Accounting\ChartAccounts;
use Illuminate\Http\Request;
use Config;
use App\models\Users\Users;
use App\models\Expenses\Expenses;
use App\models\FnB\FnbOrders;


class CashflowController extends Controller
{
    public function GetCashflow(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash = $request->input('g_hash');

        $page = max(1, (int) $request->input('page', 1));
        $page_size = (int) $request->input('page_size', 0);
        $q = $request->input('q');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $user_name = $request->input('user', '');

        $nbr_rows_per_pages = $page_size > 0
            ? $page_size
            : Config::get('appconfig.max_rows_per_page');

        $skip = ($page - 1) * $nbr_rows_per_pages;

        $result_array = array();
        $user_info = Users::find($user_id);

        if (!$user_info) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'Invalid user';
            return Response()->json($result_array);
        }

        $c_hash = "POS567"
            . $user_info->u_username
            . $user_info->u_fullname
            . $user_info->u_email
            . "POS567";

        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $orders_query = FnbOrders::with(['Payment', 'CreatedBy'])
            ->where('fo_is_deleted', 0)
            ->where('fo_payment_status', 'paid');

        if (!empty($q)) {
            $orders_query->where(function ($query) use ($q) {
                $query
                    ->where('fo_order_code', 'LIKE', "%$q%")
                    ->orWhereHas('Payment', function ($q2) use ($q) {
                        $q2->where('pt_payment_type', 'LIKE', "%$q%");
                    })
                    ->orWhereHas('CreatedBy', function ($q2) use ($q) {
                        $q2->where('u_username', 'LIKE', "%$q%");
                    });
            });
        }


        if (!empty($from_date)) {
            $orders_query->whereDate('fo_order_datetime', '>=', $from_date);
        }

        if (!empty($to_date)) {
            $orders_query->whereDate('fo_order_datetime', '<=', $to_date);
        }

        if (!empty($user_name)) {
            $orders_query->whereHas('CreatedBy', function ($query) use ($user_name) {
                $query->where('u_username', 'LIKE', "%$user_name%");
            });
        }

        $expenses_query = Expenses::with(['Payment', 'Employee'])->whereAcIsDeleted(0);

        if (!empty($q)) {
            $expenses_query->where(function ($query) use ($q) {
                $query
                    ->where('ac_description', 'LIKE', "%$q%")
                    ->orWhereHas('Payment', function ($q2) use ($q) {
                        $q2->where('pt_payment_type', 'LIKE', "%$q%");
                    })
                    ->orWhereHas('Employee', function ($q2) use ($q) {
                        $q2->where('u_username', 'LIKE', "%$q%");
                    });
            });
        }


        if (!empty($from_date)) {
            $expenses_query->where('ac_expense_date', '>=', $from_date);
        }

        if (!empty($to_date)) {
            $expenses_query->where('ac_expense_date', '<=', $to_date);
        }

        if (!empty($user_name)) {
            $expenses_query->whereHas('Employee', function ($query) use ($user_name) {
                $query->where('u_username', 'LIKE', "%$user_name%");
            });
        }

        $sum_in  = (float) $orders_query->sum('fo_paid_amount');
        $sum_out = (float) $expenses_query->sum('ac_amount');

        $orders   = $orders_query->get();
        $expenses = $expenses_query->get();

        $rows = array();

        foreach ($orders as $order) {
            $rows[] = array(
                'id'     => $order->fo_id,
                't'      => $order->fo_order_datetime,
                'type'   => 'in',
                'method' => $order->Payment ? $order->Payment->pt_payment_type : '-',
                'user'   => $order->CreatedBy ? $order->CreatedBy->u_username : '-',
                'note'   => 'Order #' . $order->fo_order_code,
                'amount' => $order->fo_paid_amount,
            );
        }

        foreach ($expenses as $expense) {
            $rows[] = array(
                'id'     => $expense->ac_id,
                't'      => $expense->ac_expense_date,
                'type'   => 'out',
                'method' => $expense->Payment ? $expense->Payment->pt_payment_type : '-',
                'user'   => $expense->Employee ? $expense->Employee->u_username : '-',
                'note'   => $expense->ac_description,
                'amount' => $expense->ac_amount,
            );
        }

        usort($rows, function ($a, $b) {
            return strtotime($b['t']) <=> strtotime($a['t']);
        });

        $total_rows  = count($rows);
        $total_pages = max(1, ceil($total_rows / $nbr_rows_per_pages));

        $rows = array_slice($rows, $skip, $nbr_rows_per_pages);

        $result_array['is_error']    = 0;
        $result_array['rows']        = $rows;
        $result_array['sum_in']      = $sum_in;
        $result_array['sum_out']     = $sum_out;
        $result_array['page']        = $page;
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }

    public function ExportCashflowCSV(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $q         = $request->input('q');
        $from_date = $request->input('from_date');
        $to_date   = $request->input('to_date');
        $user_name = $request->input('user');

        $result_array = array();

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email  . "POS567";

        $c_hash = hash('sha256', $c_hash);

        // validate hash
        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $orders_query = FnbOrders::with(['Payment', 'CreatedBy'])
            ->where('fo_is_deleted', 0)
            ->where('fo_payment_status', 'paid');

        if (!empty($q)) {
            $orders_query->where(function ($query) use ($q) {
                $query
                    ->where('fo_order_code', 'LIKE', "%$q%")
                    ->orWhereHas('Payment', function ($q2) use ($q) {
                        $q2->where('pt_payment_type', 'LIKE', "%$q%");
                    })
                    ->orWhereHas('CreatedBy', function ($q2) use ($q) {
                        $q2->where('u_username', 'LIKE', "%$q%");
                    });
            });
        }

        if (!empty($from_date)) {
            $orders_query->whereDate('fo_order_datetime', '>=', $from_date);
        }

        if (!empty($to_date)) {
            $orders_query->whereDate('fo_order_datetime', '<=', $to_date);
        }

        if (!empty($user_name)) {
            $orders_query->whereHas('CreatedBy', function ($query) use ($user_name) {
                $query->where('u_username', 'LIKE', "%$user_name%");
            });
        }

        $orders = $orders_query->orderBy('fo_order_datetime', 'DESC')->get();

        $expenses_query = Expenses::with(['Payment', 'Employee'])
            ->whereAcIsDeleted(0);

        if (!empty($q)) {
            $expenses_query->where(function ($query) use ($q) {
                $query
                    ->where('ac_description', 'LIKE', "%$q%")
                    ->orWhereHas('Payment', function ($q2) use ($q) {
                        $q2->where('pt_payment_type', 'LIKE', "%$q%");
                    })
                    ->orWhereHas('Employee', function ($q2) use ($q) {
                        $q2->where('u_username', 'LIKE', "%$q%");
                    });
            });
        }

        if (!empty($from_date)) {
            $expenses_query->where('ac_expense_date', '>=', $from_date);
        }

        if (!empty($to_date)) {
            $expenses_query->where('ac_expense_date', '<=', $to_date);
        }

        if (!empty($user_name)) {
            $expenses_query->whereHas('Employee', function ($query) use ($user_name) {
                $query->where('u_username', 'LIKE', "%$user_name%");
            });
        }

        $expenses = $expenses_query->orderBy('ac_expense_date', 'DESC')->get();

        $filename = "cashflow_" . date('Y-m-d') . ".csv";

        $headers = array(
            "Content-Type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
        );

        $callback = function () use ($orders, $expenses) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Date',
                'Type',
                'Method',
                'User',
                'Note',
                'Amount'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->fo_order_datetime,
                    'IN',
                    $order->Payment ? $order->Payment->pt_payment_type : '',
                    $order->CreatedBy ? $order->CreatedBy->u_username : '',
                    'Order #' . $order->fo_order_code,
                    $order->fo_paid_amount,
                ]);
            }

            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->ac_expense_date,
                    'OUT',
                    $expense->Payment ? $expense->Payment->pt_payment_type : '',
                    $expense->Employee ? $expense->Employee->u_username : '',
                    strip_tags($expense->ac_description),
                    $expense->ac_amount,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function GetAccounts(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash = $request->input('g_hash');

        $result_array = array();
        $user_info = Users::find($user_id);

        if (!$user_info) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'Invalid user';
            return Response()->json($result_array);
        }

        $c_hash = "POS567"
            . $user_info->u_username
            . $user_info->u_fullname
            . $user_info->u_email
            . "POS567";

        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $lst_accounts = ChartAccounts::whereAaIsDeleted(0)->get();
        $accounts_array = [];

        $accounts_array = $lst_accounts->map(function ($account) {
            return [
                'aa_id' => $account->aa_id,
                'aa_account' => $account->aa_account,
                'aa_account_label' => $account->aa_account_label,
                'aa_sub_account' => $account->aa_sub_account,
            ];
        });


        $result_array['is_error'] = 0;
        $result_array['error_message'] = 'Accounts fetched successfully';

        $result_array['lst_accounts'] = $accounts_array;

        return response()->json($result_array);
    }
}
