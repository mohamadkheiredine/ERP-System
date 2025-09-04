<?php
/***********************************************************
SalaryDetailsController
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 21, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/




namespace App\Http\Controllers\PayRoll;

use App\Http\Controllers\Controller;
use App\models\Accounting\TransactionMovements;
use App\models\Accounting\Transactions;
use App\models\PayRolls\PayrollsPaymentMethods;
use App\models\PayRolls\PayrollsSalaryDetails;
use App\models\PayRolls\PayrollsTransactions;
use App\Models\User;
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
use App\models\Shipment\OrderStatus;
use App\models\System\Companies;
use App\models\Users\Users;
use App\models\PayRolls\PayrollsPeriods;



class PayRollsTransactionsController extends Controller
{

    /**
     * Page to control Salary Details Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_employees = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();

        $data = array(
            "lst_companies" => $lst_companies,
            "lst_employees" => $lst_employees
        );
        return Response()->view('payrolls.transactions',$data);
    }


    /**
     * Display list PayRoll saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {

        $pt_company_id               = $request->input('pt_company_id');
        $pt_employee_id               = $request->input('pt_employee_id');
        $pp_status                  = $request->input('pp_status');
        $page_number                = $request->input('page_number');
        $general_search             = $request->input('general_search');
        $nbr_rows_per_pages         = Config::get('appconfig.max_rows_per_page');

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;

        $transactions_cond = PayrollsTransactions::wherePtIsDeleted(0);

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        if( $pt_company_id > 0 )
        {
            $transactions_cond = $transactions_cond->wherePtCompanyId($pt_company_id);
        }

        if( $pt_employee_id > 0 )
        {
            $transactions_cond = $transactions_cond->wherePtEmployeeId($pt_employee_id);
        }

        $trans_count = $transactions_cond->count();


        $total_pages = ceil( $trans_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_transactions = $transactions_cond->skip($skip)->take($nbr_rows_per_pages)->get();


        $data = array(
            "lst_transactions" => $lst_transactions
        );

        $result_array = array();
        $result_array['is_error'] = 0;
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view('payrolls.lstpayrolltransactions',$data)->render();

        return Response()->json($result_array);
    }


    public function PayEmployeePayRoll(Request $request)
    {
        $pt_id = $request->input('pt_id');
        $transaction_info = PayrollsTransactions::find($pt_id);
        $salary_details = PayrollsSalaryDetails::where('pd_payroll_transaction',$pt_id)->get();
        $salary_details = $salary_details[0];
        $currency_id = session('company_currency');
        $details_info = PayrollsSalaryDetails::find($salary_details->pd_id);

        $transaction = new Transactions();
        $todays_date = date('Y-m-d');


        $transaction->at_transaction_date   = $todays_date;
        $transaction->at_creation_date      = $todays_date;
        $transaction->at_accounting_doc     = "Transaction For PayRoll from Employee " . $transaction_info->Employee->u_fullname . "";
        $transaction->fk_acc_journal_id     = 1;
        $transaction->at_currency_id        = $currency_id;
        $transaction->save();

        $at_id = $transaction->at_id;


        $details_info->pd_salary_paid = 1;
        $details_info->save();

        $transaction_info->pt_payment_date = $todays_date;
        $transaction_info->pt_processed_at = $todays_date;
        $transaction_info->pt_transaction_date = $todays_date;
        $transaction_info->pt_transaction_id = $at_id;
        $transaction_info->pt_status = 'paid';
        $transaction_info->save();


        $total_salary = $transaction_info->pt_gross_salary;
        $total_comission = $transaction_info->ot_total_comissions;

        $trans_mov= new TransactionMovements();
        $trans_mov->fk_tran_id              = $at_id;
        $trans_mov->tm_ledger_account       = 6311;
        $trans_mov->tm_sub_ledger_account   = 6311 ;
        $trans_mov->tm_debit                = $total_salary;
        $trans_mov->tm_credit               = 0;
        $trans_mov->tm_creation_date        = date('Y-m-d');
        $trans_mov->tm_transaction_date        = date('Y-m-d');
        $trans_mov->tm_currency_id          = $currency_id;
        $trans_mov->tm_ledger_label         = "Debit For Total Sallary of " . $transaction_info->Employee->u_fullname . " on " . $todays_date;
        $trans_mov->save();

        $trans_mov= new TransactionMovements();
        $trans_mov->fk_tran_id              = $at_id;
        $trans_mov->tm_ledger_account       = 6318;
        $trans_mov->tm_sub_ledger_account   = 6318 ;
        $trans_mov->tm_debit                = $total_comission;
        $trans_mov->tm_credit               = 0;
        $trans_mov->tm_creation_date        = date('Y-m-d');
        $trans_mov->tm_transaction_date        = date('Y-m-d');
        $trans_mov->tm_currency_id          = $currency_id;
        $trans_mov->tm_ledger_label         = "Debit For Total Comission of " . $transaction_info->Employee->u_fullname . " on " . $todays_date;
        $trans_mov->save();

        $trans_mov= new TransactionMovements();
        $trans_mov->fk_tran_id              = $at_id;
        $trans_mov->tm_ledger_account       = 6318;
        $trans_mov->tm_sub_ledger_account   = 6318 ;
        $trans_mov->tm_debit                = 0;
        $trans_mov->tm_credit               = $total_comission + $total_salary;
        $trans_mov->tm_creation_date        = date('Y-m-d');
        $trans_mov->tm_transaction_date        = date('Y-m-d');
        $trans_mov->tm_currency_id          = $currency_id;
        $trans_mov->tm_ledger_label         = "Credit From Cash For Salary of " . $transaction_info->Employee->u_fullname . " on " . $todays_date;
        $trans_mov->save();


        $result_array = array();
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Pay Payroll transaction Completed Successfully";

        return Response()->json($result_array);

    }


}
