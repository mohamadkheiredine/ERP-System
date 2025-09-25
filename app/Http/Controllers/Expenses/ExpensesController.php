<?php
/***********************************************************
ExpensesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Inventory\unknown;
use App\Http\Controllers\Inventory\View;
use App\models\Accounting\ChartAccounts;
use App\models\Accounting\TransactionMovements;
use App\models\Accounting\Transactions;
use App\models\Billing\PaymentTypes;
use App\models\CostCenter\CostCenters;
use App\models\Expenses\ExpensePayments;
use App\models\Expenses\Expenses;
use App\models\System\Currency;
use App\models\System\SystemStatus;
use App\models\Users\Users;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use Config;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Expenses\ExpensesCategories;



class ExpensesController extends Controller
{

    /**
     * Page to control Expenses Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $lst_expenses_categories = ExpensesCategories::whereEcIsDeleted(0)->get();
        $lst_employees = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_cost_centers = CostCenters::whereAcIsDeleted(0)->get();

        $data = array(
            "lst_categories" => $lst_expenses_categories,
            "lst_employees" => $lst_employees,
            "lst_cost_centers" => $lst_cost_centers,
        );
        return Response()->view('expenses.expenses',$data);
    }


    /**
     * Display list of Expenses
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $page_number            = $request->input('page_number');
        $search_query           = $request->input('search_query');
        $ac_category_id           = $request->input('ac_category_id');
        $ac_employee_id           = $request->input('ac_employee_id');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
          $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
          $skip = 0;



        $expenses_cond = Expenses::whereAcIsDeleted(0);

        if(strlen($search_query) > 0)
            $expenses_cond = $expenses_cond->where('ac_description' , 'LIKE' , '%' . $search_query . '%');
        if(strlen($ac_category_id) > 0)
            $expenses_cond = $expenses_cond->where('ac_category_id' , '=' , $ac_category_id);
        if(strlen($ac_employee_id) > 0)
            $expenses_cond = $expenses_cond->where('ac_employee_id' , '=' , $ac_employee_id);

        $expenses_count = $expenses_cond->count();


        $total_pages = ceil( $expenses_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_expenses = $expenses_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('ac_id', 'ASC')->get();

        $data = array(
            "lst_expenses" => $lst_expenses
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("expenses.lstexpenses",$data)->render();

        return Response()->json($result_array);
    }



    /**
     * Function of Adding a new Expenses
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_currencies = Currency::all();
        $lst_expenses_categories = ExpensesCategories::whereEcIsDeleted(0)->get();
        $lst_employees = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_cost_centers = CostCenters::whereAcIsDeleted(0)->get();
        $lst_payment_types = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_expenses_status = SystemStatus::whereSsStatusType("expenses_status")->whereSsIsDeleted(0)->get();

        $data = array(
            'lst_currencies' => $lst_currencies,
            'lst_categories' => $lst_expenses_categories,
            'lst_employees' => $lst_employees,
            'lst_expenses_status' => $lst_expenses_status,
            'lst_cost_centers' => $lst_cost_centers,
            'lst_payment_types' => $lst_payment_types,
        );
        return view('expenses.addexpense',$data);
    }


    /**
     * Save Expense Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveExpenseInfo(Request $request)
    {
        $ac_id                                          = $request->input('ac_id');
        $ac_employee_id                                 = $request->input('ac_employee_id');
        $ac_category_id                                 = $request->input('ac_category_id');
        $ac_amount                                      = $request->input('ac_amount');
        $ac_expense_date                                = $request->input('ac_expense_date');
        $ac_currency_id                                 = $request->input('ac_currency_id');
        $ac_description                                 = $request->input('ac_description');
        $ac_status_id                                   = $request->input('ac_status_id');
        $ac_cost_center_id                              = $request->input('ac_cost_center_id');
        $ac_payment_type                                = $request->input('ac_payment_type');
        $ac_is_paid                                     = $request->has('ac_is_paid') ? 1 : 0;

        $result_array = array();


        $expenses_info = new Expenses();
        if($ac_id != null)
        {
            $expenses_info = Expenses::find($ac_id);
        }



        $expenses_info->ac_employee_id                  = $ac_employee_id;
        $expenses_info->ac_category_id                  = $ac_category_id;
        $expenses_info->ac_amount                  = $ac_amount;
        $expenses_info->ac_expense_date                  = $ac_expense_date;
        $expenses_info->ac_currency_id                  = $ac_currency_id;
        $expenses_info->ac_description                  = $ac_description;
        $expenses_info->ac_status_id                  = $ac_status_id;
        $expenses_info->ac_cost_center_id                  = $ac_cost_center_id;
        $expenses_info->ac_payment_type                  = $ac_payment_type;
        $expenses_info->ac_is_paid                       = $ac_is_paid;
        $expenses_info->save();


        if($ac_id == null && $ac_is_paid == 1) // when add expenses and is paid we create accounting records
        {

            $todays_date = date("Y-m-d");

            $payment_type = PaymentTypes::find($ac_payment_type);
            $account_id = $expenses_info->Category->ec_gl_account_id;



            // save transaction and movement
            $transaction = new Transactions();
            $transaction->at_transaction_date   = $todays_date;
            $transaction->at_creation_date      = $todays_date;
            $transaction->at_accounting_doc     = "Transaction For Expense of " . $expenses_info->Category->ec_name;
            $transaction->fk_acc_journal_id     = 1;
            $transaction->at_currency_id        = $ac_currency_id;
            $transaction->save();
            $at_id = $transaction->at_id;

            $trans_mov= new TransactionMovements();
            $trans_mov->fk_tran_id              = $at_id;
            $trans_mov->tm_ledger_account       = $account_id;
            $trans_mov->tm_sub_ledger_account   = $account_id ;
            $trans_mov->tm_debit                = $ac_amount;
            $trans_mov->tm_credit               = 0;
            $trans_mov->tm_creation_date        = $todays_date;
            $trans_mov->tm_transaction_date        = $todays_date;
            $trans_mov->tm_currency_id          = $ac_currency_id;
            $trans_mov->tm_ledger_label         = "Debit Expense For " . $expenses_info->Category->ec_name;
            $trans_mov->save();

            $trans_mov= new TransactionMovements();
            $trans_mov->fk_tran_id              = $at_id;
            $trans_mov->tm_ledger_account       = $payment_type->pt_payment_account;
            $trans_mov->tm_sub_ledger_account   = $payment_type->pt_payment_account;
            $trans_mov->tm_debit                = 0;
            $trans_mov->tm_credit               = $ac_amount;
            $trans_mov->tm_creation_date        = $todays_date;
            $trans_mov->tm_transaction_date        = $todays_date;
            $trans_mov->tm_currency_id          = $ac_currency_id;
            $trans_mov->tm_ledger_label         = "Credit Expense For " . $expenses_info->Category->ec_name;
            $trans_mov->save();


            $payment_info = new ExpensePayments();
            $payment_info->aa_expense_id = $expenses_info->ac_id;
            $payment_info->aa_transaction_id = $at_id;
            $payment_info->aa_movement_id = 0;
            $payment_info->aa_paid_by = $ac_employee_id;
            $payment_info->aa_paid_date = $todays_date;
            $payment_info->aa_amount = $ac_amount;
            $payment_info->aa_remarks = "Expense Payment For " . $expenses_info->Category->ec_name;
            $payment_info->save();

            $expenses_info->ac_payment_id = $payment_info->aa_id;
            $expenses_info->save();
        }


        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Expenses Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit Expenses Category Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $pc_id
     */
    public function EditForm( $ac_id )
    {
        $expenses_info        = Expenses::find($ac_id);
        $lst_currencies = Currency::all();
        $lst_expenses_categories = ExpensesCategories::whereEcIsDeleted(0)->get();
        $lst_employees = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_cost_centers = CostCenters::whereAcIsDeleted(0)->get();
        $lst_expenses_status = SystemStatus::whereSsStatusType("expenses_status")->whereSsIsDeleted(0)->get();
        $lst_payment_types = PaymentTypes::wherePtIsDeleted(0)->get();

        $data = array(
            "expenses_info" => $expenses_info,
            'lst_currencies' => $lst_currencies,
            'lst_categories' => $lst_expenses_categories,
            'lst_employees' => $lst_employees,
            'lst_expenses_status' => $lst_expenses_status,
            'lst_payment_types' => $lst_payment_types,
            'lst_cost_centers' => $lst_cost_centers
        );
        return view('expenses.editexpense',$data);
    }


    /**
     * Delete Expenses category information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteExpensesInfo(Request $request)
    {

        $ac_id = $request->input('ac_id');

        $expenses_info = Expenses::find( $ac_id);
        $expenses_info->ac_is_deleted          = 1;
        $expenses_info->ac_deleted_by          = Session('user_id');
        $expenses_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
