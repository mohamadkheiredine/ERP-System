<?php
/***********************************************************
InvoicePaymentsController
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 22, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/


namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\models\PayRolls\PayrollsComissions;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use App;
use Config;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Accounting\ChartAccounts;
use App\models\System\Countries;
use App\models\Accounting\AccountingJournals;
use App\models\Accounting\Journaltypes;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\library\AccountsManager;
use App\library\AccountingManager;
use models\Account;
use App\models\CRM\CRMAccounts;
use App\models\Billing\Invoices;
use App\models\Accounting\BankAccounts;
use App\models\Billing\PaymentTypes;
use App\models\Billing\PaymentTerms;
use App\models\Billing\InvoiceProducts;
use App\models\Inventory\Products;
use App\models\Accounting\VatAccounts;
use App\models\System\Currency;
use App\models\Billing\InvoicePayments;
use App\models\Billing\Receipts;
use App\models\System\Companies;
use App\models\Inventory\Customers;
use App\models\Users\Users;
use App\models\Users\UserTypes;
use Dompdf\Dompdf;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;



class InvoicePaymentsController extends Controller
{
    public function index()
    {

        $data = array();
        return Response()->view('billing.bills',$data);
    }




    /**
     * Function to generate Table of list of journal vouchers and
     * Show it based on parameters chosen
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
        $general_search              = $request->input('general_search');
        $pi_start_date              = $request->input('pi_start_date');
        $pi_end_date                = $request->input('pi_end_date');
        $pi_upto_date                = $request->input('pi_upto_date');
        $page_number                = $request->input("page_number");
        $nbr_rows_per_pages         = Config::get('appconfig.max_rows_per_page');



        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        $bills_cond = InvoicePayments::whereIpIsDeleted(0);



        if(strlen($pi_start_date) > 0)
            $bills_cond = $bills_cond->where('ip_billing_date','>=',$pi_start_date);
        if(strlen($pi_end_date) > 0)
            $bills_cond = $bills_cond->where('ip_billing_date','<',$pi_end_date);

        if(strlen($pi_upto_date) > 0)
            $bills_cond = $bills_cond->where('ip_billing_date','<=',$pi_upto_date);



        $bills_count =     $bills_cond->count();
        $total_pages = ceil( $bills_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_bills_info   = $bills_cond->skip($skip)->take($nbr_rows_per_pages)->get();

        $lst_currency           = Currency::all();
        $currency_array         = CreateDatabaseArrayByIndex($lst_currency,"cc_id");


        $data = array(
            "lst_bills_info" => $lst_bills_info,
            "currency_array" => $currency_array
        );

        $result_array = array();
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("billing.lstbills",$data)->render();

        return Response()->json($result_array);
    }



    /**
     * Open form of add new Journal Vouchers
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function AddForm()
    {
        $lst_collectors     = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_COLLECTOR)->get();
        $lst_currencies     = Currency::all();
        $lst_payment_types       = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_admins = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_ADMIN)->get();
        $lst_technicians        = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TECHNICIAN)->get();


        $data = array(
            'lst_collectors' => $lst_collectors,
            'lst_admins' => $lst_admins,
            'lst_technicians' => $lst_technicians,
            'lst_currency' => $lst_currencies,
            'lst_payment_types' => $lst_payment_types,
        );

        return Response()->view('billing.addbill',$data);
    }




    /**
     * get information of selected Account and open the edit form fields
     * @param unknown $w_id
     * @return \Illuminate\Http\Response
     */
    public function EditForm( $bi_id )
    {

        $lst_collectors     = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_COLLECTOR)->get();
        $lst_currencies     = Currency::all();
        $lst_payment_types       = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_admins = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_ADMIN)->get();
        $lst_technicians        = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TECHNICIAN)->get();

        $bill_info = InvoicePayments::find($bi_id);

        $data = array(
            'lst_collectors' => $lst_collectors,
            'lst_technicians' => $lst_technicians,
            'lst_admins' => $lst_admins,
            'lst_currency' => $lst_currencies,
            'bill_info' => $bill_info,
            'lst_payment_types' => $lst_payment_types,
        );

        return Response()->view('billing.editbill',$data);

    }




    /**
     * Save information of new Journal voucher and
     * add a transaction and movement records in the banks
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Json $result_array
     */
    public function SavePaymentBillInfo(Request $request)
    {
        $ip_id                              = $request->input('ip_id');
        $ip_payment_doc                     = $request->input('ip_payment_doc');
        $ip_client_id                       = $request->input('ip_client_id');
        $ip_collector_id                    = $request->input('ip_collector_id');
        $ip_payment_type_id                 = $request->input('ip_payment_type_id');
        $ip_client_code                     = $request->input('ip_client_code');
        $ip_client_name                     = $request->input('ip_client_name');
        $ip_billing_nbr                     = $request->input('ip_billing_nbr');
        $ip_billing_date                    = $request->input('ip_billing_date');
        $ip_payment_amount                  = $request->input('ip_payment_amount');
        $ip_currency_id                     = $request->input('ip_currency_id');
        $ip_pay_date                        = $request->input('ip_pay_date');
        $ip_updated_by                      = session('user_id');
        $ip_updated_date                    = date("Y-m-d");
        $ip_is_update                       = $request->has('ip_is_update') ? 1 : 0;
        $ip_billing_status                  = $request->has('ip_billing_status') ? 1 : 0;

        $bills_info     = new InvoicePayments();
        $is_new = true;
        if( $ip_id  > 0 )
        {
            $bills_info    = InvoicePayments::find( $ip_id );
            $bills_info->ip_updated_by = $ip_updated_by;
            $bills_info->ip_updated_date = $ip_updated_date;
            $is_new = false;
        }

        $client_info  = CRMAccounts::whereCaAccountCode($ip_client_code)->first();
        $ip_client_id = $client_info->ca_id;

        $bills_info->ip_payment_doc                     = $ip_payment_doc;
        $bills_info->ip_client_id                       = $ip_client_id;
        $bills_info->ip_collector_id                    = $ip_collector_id;
        $bills_info->ip_payment_type_id                 = $ip_payment_type_id;
        $bills_info->ip_client_code                     = $ip_client_code;
        $bills_info->ip_client_name                     = $ip_client_name;
        $bills_info->ip_billing_status                     = $ip_billing_status;
        $bills_info->ip_billing_nbr                     = $ip_billing_nbr;
        $bills_info->ip_billing_date                    = $ip_billing_date;
        $bills_info->ip_payment_amount                  = $ip_payment_amount;
        $bills_info->ip_currency_id                     = $ip_currency_id;
        if($ip_billing_status == 1)
            $bills_info->ip_pay_date                     = $ip_pay_date;
        $bills_info->save();

        if($ip_billing_status == 1)
        {
            $client_info = CRMAccounts::find($ip_client_id);
            $account_id = $client_info->ca_accounting_id;





            $payment_type = PaymentTypes::find($ip_payment_type_id);
            $pt_payment_account = $payment_type->pt_payment_account;
            $transaction_id = $bills_info->ip_transaction_id;

            $delete = Transactions::whereAtId($transaction_id)->delete();
            $delete = TransactionMovements::whereFkTranId($transaction_id)->delete();

            $transaction = new Transactions();
            $todays_date = date('Y-m-d');


            $transaction->at_transaction_date   = $todays_date;
            $transaction->at_creation_date      = $todays_date;
            $transaction->at_accounting_doc     = "Transaction For Pay Bill";
            $transaction->fk_acc_journal_id     = 1;
            $transaction->at_currency_id        = $ip_currency_id;
            $transaction->save();
            $at_id = $transaction->at_id;

            $trans_mov= new TransactionMovements();
            $trans_mov->fk_tran_id              = $at_id;
            $trans_mov->tm_ledger_account       = $account_id;
            $trans_mov->tm_sub_ledger_account   = $account_id ;
            $trans_mov->tm_debit                = $ip_payment_amount;
            $trans_mov->tm_credit               = 0;
            $trans_mov->tm_creation_date        = date('Y-m-d');
            $trans_mov->tm_transaction_date        = date('Y-m-d');
            $trans_mov->tm_currency_id          = $ip_currency_id;
            $trans_mov->tm_ledger_label         = "Debit For Client " . $client_info->ca_account_name;
            $trans_mov->save();


            $bills_info->ip_transaction_id   = $at_id;
            $bills_info->save();

            // add comission

            $delete = PayrollsComissions::wherePcDealId($bills_info->ip_id)->delete();

            $payroll_comissions = new PayrollsComissions();
            $payroll_comissions->pc_employee_id = $ip_collector_id;
            $payroll_comissions->pc_company_id = session('company_id');
            $payroll_comissions->pc_comission_value = 1;
            $payroll_comissions->pc_currency_id = $ip_currency_id;
            $payroll_comissions->pc_effective_date = date('Y-m-d');
            $payroll_comissions->pc_deal_id = $bills_info->ip_id;
            $payroll_comissions->pc_comission_label = "Commission on file # "  . $client_info->ca_account_code . " - " . $client_info->ca_account_name;
            $payroll_comissions->save();

        }




        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);

    }




    /**
     * Delete Payment ( Bills ) info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteBillInfo(Request $request)
    {
        $ip_id = $request->input('ip_id');
        $result_array = array();

        $payment_info   = InvoicePayments::find($ip_id);
        $payment_info->ip_is_deleted = 1;
        $payment_info->ip_deleted_by = session('user_id');
        $payment_info->save();


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
}
