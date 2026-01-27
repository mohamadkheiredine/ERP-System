<?php

/***********************************************************
 * ExpensesController.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/12/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\library\ExpensesManager;
use App\models\Accounting\Transactions;
use App\models\Billing\PaymentTypes;
use App\models\Expenses\ExpensePayments;
use App\models\Expenses\Expenses;
use App\models\Expenses\ExpensesCategories;
use Validator;
use Input;
use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeads;
use App\models\Users\Users;
use App\models\Inventory\Customers;
use App\models\Inventory\WareHouses;
use App\models\System\Industry;
use Maatwebsite\Excel\Facades\Excel;
use App\models\CRM\CRMServiceCategories;
use App\library\CustomersManager;
use App\models\System\Countries;
use App\models\Accounting\VatAccounts;
use App\models\Inventory\Vendors;
use App\models\Accounting\ChartAccounts;
use App\models\Accounting\DefaultAccounts;
use App\models\Accounting\TransactionMovements;
use App\library\AccountingManager;


class ExpensesController extends Controller
{


    public function GetListExpenseCategories(Request $request)
    {

        $user_id = $request->input('user_id');
        $g_hash = $request->input('g_hash');
        $customer_search = $request->input('searchquery');
        $current_page = $request->input('current_page');
        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $lst_categories = ExpensesCategories::whereEcIsDeleted(0)->get();
        $categories_array = array();
        foreach ($lst_categories as $category) {
            $categories_array[] = array(
                'id' => $category->ec_id,
                'category_label' => $category->ec_name,
            );
        }


        $result_array['categories_array'] = $categories_array;

        return Response()->json($result_array);
    }


    /**
     * Submit new Expense
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SubmitNewexpense(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash = $request->input('g_hash');
        $expense_id = $request->input('expense_id');
        $date = $request->input('date');
        $category_id = $request->input('category_id');
        $payment_type = $request->input('payment_type');
        $currency_id = $request->input('currency_id');
        $amount = $request->input('amount');
        $note = $request->input('note');
        $attachment = $request->input('attachment');
        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }



        if ($attachment != null) {
            $expenses_obj = new ExpensesManager();
            $res_data = $expenses_obj->UploadExtensesVoucher($expense_id);
        }

        $expenses_category = ExpensesCategories::find($category_id);
        $payment_info = PaymentTypes::find($payment_type);

        // Save expenses
        if (!empty($expense_id)) {
            $expenses_info = Expenses::find($expense_id);

            if (!$expenses_info) {
                $result_array['is_error'] = 1;
                $result_array['error_message'] = 'Expense not found';
                return Response()->json($result_array);
            }
        } else {
            $expenses_info = new Expenses();
            $expenses_info->ac_employee_id = $user_id;
        }

        $expenses_info->ac_category_id = $category_id;
        $expenses_info->ac_amount = $amount;
        $expenses_info->ac_expense_date = $date;
        $expenses_info->ac_currency_id = $currency_id;
        $expenses_info->ac_description = $note;
        $expenses_info->ac_payment_id = $payment_type;
        if ($attachment != null) {
            $expenses_info->ac_base_src = $res_data['data']['ac_base_src'];
            $expenses_info->ac_file_name = $res_data['data']['ac_file_name'];
            $expenses_info->ac_extension = $res_data['data']['ac_extension'];
        }

        $expenses_info->save();

        if (empty($expense_id)) {
            $AccTransaction = new Transactions();
            $AccTransaction->at_transaction_date    = $date;
            $AccTransaction->at_creation_date       = date("Y-m-d");
            $AccTransaction->at_accounting_doc      = "Expenses Created On " . $date;
            $AccTransaction->fk_acc_journal_id      = 3;
            $AccTransaction->save();
            $at_id = $AccTransaction->at_id;

            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $payment_info->pt_payment_account;
            $TransactionMovement->tm_sub_ledger_account = $payment_info->pt_payment_account;
            $TransactionMovement->tm_ledger_label       = "Expenses Created On " . $date;
            $TransactionMovement->tm_debit              = $amount;
            $TransactionMovement->tm_credit             = 0;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_transaction_date   = $date;
            $TransactionMovement->tm_currency_id        = $currency_id;
            $TransactionMovement->save();

            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $expenses_category->pt_payment_account;
            $TransactionMovement->tm_sub_ledger_account = $expenses_category->pt_payment_account;
            $TransactionMovement->tm_ledger_label       = "Expenses Created On " . $date;
            $TransactionMovement->tm_debit              = 0;
            $TransactionMovement->tm_credit             = $amount;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_transaction_date   = $date;
            $TransactionMovement->tm_currency_id        = $currency_id;
            $TransactionMovement->save();

            // save expenses Payment
            $expense_payment = new ExpensePayments();
            $expense_payment->aa_expense_id = $expenses_info->ac_id;
            $expense_payment->aa_amount = $amount;
            $expense_payment->aa_transaction_id = $at_id;
            $expense_payment->aa_paid_by = $user_id;
            $expense_payment->aa_paid_date = $date;
            $expense_payment->save();
        }


        return Response()->json($result_array);
    }


    /**
     * Get List Expenses
     * @param Request $request
     * @return void
     */
    public function GetListExpenses(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash = $request->input('g_hash');
        $page = $request->input('page');
        $page_size = $request->input('page_size');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        $result_array = array();

        $q = $request->input('q');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $category_id = $request->input('category_id');
        $payment_type = $request->input('payment_type');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        if ($page > 1)
            $skip = ($page - 1) * $nbr_rows_per_pages;
        else
            $skip = 0;

        $expenses_query = Expenses::whereAcIsDeleted(0);

        if (!empty($q)) {
            $expenses_query = $expenses_query->where('ac_id', 'LIKE', "%$q%");
            $expenses_query = $expenses_query->orWhere('ac_description', 'LIKE', "%$q%");
        }

        if (!empty($from_date)) {
            $expenses_query = $expenses_query->where('ac_expense_date', '>=', $from_date);
        }

        if (!empty($to_date)) {
            $expenses_query = $expenses_query->where('ac_expense_date', '<=', $to_date);
        }

        if (!empty($category_id) && $category_id != 0) {
            $expenses_query = $expenses_query->where('ac_category_id', $category_id);
        }

        if (!empty($payment_type) && $payment_type != 0) {
            $expenses_query = $expenses_query->where('ac_payment_id', $payment_type);
        }

        $count_expenses = $expenses_query->count();

        $lst_expenses = $expenses_query->skip($skip)->take($nbr_rows_per_pages)->orderby('ac_id', "DESC")->get();


        $total_pages = ceil($count_expenses / $nbr_rows_per_pages);

        $expenses_data = array();

        foreach ($lst_expenses as $expense) {
            $expenses_data[] = array(
                'ref' => $expense->ac_id,
                'category' => $expense->Category->ec_name,
                'date' => $expense->ac_expense_date,
                'payment' => $expense->Payment ? $expense->Payment->pt_payment_type : "-",
                'Amount' => $expense->ac_amount,
                'note' => $expense->ac_description,
                'category_id' => $expense->ac_category_id,
                'payment_id' => $expense->ac_payment_id,
                'currency_id' => $expense->ac_currency_id,

            );
        }

        $result_array['is_error'] = 0;
        $result_array['rows'] = $expenses_data;
        $result_array['total'] = $count_expenses;
        $result_array['page'] = $page;
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }

    /**
     * @author Mohammed kheiredine
     */

    public function DeleteExpense(Request $request)
    {
        $user_id   = $request->input('user_id');
        $g_hash    = $request->input('g_hash');
        $expense_id = $request->input('expense_id');

        $result_array = array();

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';
            return response()->json($result_array);
        }

        $expense = Expenses::find($expense_id);

        if (!$expense) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'Expense not found';
            return response()->json($result_array);
        }

        $expense->ac_is_deleted = 1;
        $expense->ac_deleted_by = $user_id;
        $expense->save();

        $result_array['is_error'] = 0;
        return response()->json($result_array);
    }
}
