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
use App\models\CallCenter\CallResults;
use App\models\CallCenter\CallResultsWorkflow;
use App\models\CallCenter\InboundCall;
use App\models\PayRolls\PayrollsComissions;
use App\models\System\Areas;
use App\models\System\Regions;
use League\Csv\Writer;
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
use App\models\Billing\BillsRvs;
use App\models\CRM\CRMDeals;
use App\models\Billing\BillsResultsWorkflow;
use Dompdf\Dompdf;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;



class InvoicePaymentsController extends Controller
{
    public function index()
    {

        $lst_results = CallResults::whereCrIsDeleted(0)->get();
        $lst_collecters = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_COLLECTOR)->get();
        $lst_admins = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_ADMIN)->get();
        $lst_areas              = Areas::all();
        $lst_regions             = Regions::all();

        $data = array(
            "lst_results" => $lst_results,
            "lst_collecters" => $lst_collecters,
            "lst_areas" => $lst_areas,
            "lst_regions" => $lst_regions,
            "lst_admins" => $lst_admins
        );
        return Response()->view('billing.bills',$data);
    }

    /**
     * Save result for call bill
     *
     * @author  Moe Mantach
     * @access public
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SaveBillResultInfo(Request $request)
    {
        $ip_bill_id           = $request->input('ip_bill_id');
        $bw_id           = $request->input('bw_id');
        $bw_result_id           = $request->input('bw_result_id');
        $bw_creation_date           = $request->input('bw_creation_date');
        $bw_callback_date           = $request->input('bw_callback_date');
        $bw_assigned_to           = $request->input('bw_assigned_to');
        $bw_result_note           = $request->input('bw_result_note');

        $result_array = array();
        if($bw_id == 0)
            $result_info = new BillsResultsWorkflow();
        else
            $result_info = BillsResultsWorkflow::find($bw_id);
        $result_info->bw_bill_id = $ip_bill_id;
        $result_info->bw_result_id = $bw_result_id;
        $result_info->bw_creation_date = $bw_creation_date;
        $result_info->bw_assigned_to = $bw_assigned_to;
        $result_info->bw_result_note = $bw_result_note;
        if($bw_result_id == 1)
            $result_info->bw_callback_date = $bw_callback_date;
        $result_info->save();


        $bill_info = InvoicePayments::find($ip_bill_id);
        $bill_info->ip_call_result_id = $bw_result_id;
        $bill_info->ip_result_notes = $bw_result_note;
        $bill_info->save();

        $result_array['is_error'] = 0;

        return Response()->json($result_array);
    }


    public function GetRegionArea(Request $request)
    {
        $bill_area = $request->input('bill_area');



        $lst_regions = Regions::whereLrArea($bill_area)->get();
        $regions_array = array();

        foreach ($lst_regions as $key => $value)
        {
            $regions_array[$value->lr_region] = $value->lr_region;
        }


        $data = array(
            "html_array" => $regions_array,
            "name" => 'bill_region',
            "id" => 'BILL_REGION'
        );

        $result_array['dropdown'] = view('html.dropdown',$data)->render();


        return Response()->json($result_array);
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
        $general_search             = $request->input('general_search');
        $pi_start_date              = $request->input('pi_start_date');
        $pi_end_date                = $request->input('pi_end_date');
        $pi_upto_date               = $request->input('pi_upto_date');
        $ip_payment_status          = $request->input('ip_payment_status');
        $page_number                = $request->input("page_number");
        $bill_area                = $request->input("bill_area");
        $bill_region                = $request->input("bill_region");
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

        if(strlen($ip_payment_status) > 0)
            $bills_cond = $bills_cond->where('ip_payment_status','=',$ip_payment_status);


        if($bill_area > 0)
        {
            $bills_cond = $bills_cond->whereHas('Client', function($query)  use ($bill_area) {
                $query->where('cl_area', $bill_area);
            });
        }

        if($bill_region > 0)
        {
            $bills_cond = $bills_cond->whereHas('Client', function($query)  use ($bill_region) {
                $query->where('cl_region', $bill_region);
            });
        }


        $bills_count =     $bills_cond->count();
        $total_pages = ceil( $bills_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_bills_info   = $bills_cond->skip($skip)->take($nbr_rows_per_pages)->get();

        $lst_currency           = Currency::all();
        $currency_array         = CreateDatabaseArrayByIndex($lst_currency,"cc_id");

        // calculate total amount
        $total_amount = 0;

        foreach ($lst_bills_info as $index => $bill_info) {
            $total_amount = $total_amount + $bill_info->ip_payment_amount;
        }



        $data = array(
            "lst_bills_info" => $lst_bills_info,
            "currency_array" => $currency_array
        );

        $result_array = array();
        $result_array['total_pages'] = $total_pages;
        $result_array['total_amount'] = number_format($total_amount);
        $result_array['display'] = view("billing.lstbills",$data)->render();

        return Response()->json($result_array);
    }


    public function CSVDownloadBillsReport(Request $request)
    {
        $general_search              = $request->input('general_search');
        $pi_start_date              = $request->input('pi_start_date');
        $pi_end_date                = $request->input('pi_end_date');
        $pi_upto_date                = $request->input('pi_upto_date');
        $ip_payment_status                = $request->input('ip_payment_status');




        $bills_cond = InvoicePayments::whereIpIsDeleted(0);



        if(strlen($pi_start_date) > 0)
            $bills_cond = $bills_cond->where('ip_billing_date','>=',$pi_start_date);
        if(strlen($pi_end_date) > 0)
            $bills_cond = $bills_cond->where('ip_billing_date','<',$pi_end_date);

        if(strlen($pi_upto_date) > 0)
            $bills_cond = $bills_cond->where('ip_billing_date','<=',$pi_upto_date);

        if(strlen($ip_payment_status) > 0)
            $bills_cond = $bills_cond->where('ip_payment_status','=',$ip_payment_status);



        $bills_count =     $bills_cond->count();

        $lst_bills_info   = $bills_cond->get();

        $data = array();
        $data[] = [
            ' Ref',
            ' Bill Nbr',
            ' Date',
            ' Client Code',
            ' Client Name',
            ' Region',
            ' Area',
            ' Phone',
            ' Full Address',
            ' Bill Amount',
            ' Remaining',
            ' Bill Status',
        ];

        foreach ($lst_bills_info as $index => $bill_info)
        {
            $data[] = [
                $bill_info->ip_payment_doc,
                $bill_info->ip_billing_nbr,
                $bill_info->ip_billing_date,
                ($bill_info->Client ? $bill_info->Client->ca_account_code : "-"),
                ($bill_info->Client ? $bill_info->Client->ca_account_name : "-"),
                ($bill_info->Client ? $bill_info->Client->ca_billing_region : "-"),
                ( $bill_info->Client ? $bill_info->Client->ca_billing_area : "-" ),
                ( $bill_info->Client ? $bill_info->Client->ca_account_mobile : "-" ),
                (  $bill_info->Client ? $bill_info->Client->ca_billing_address : "-" ),
                ( $bill_info->ip_payment_amount . " ".  ($bill_info->Currency ? $bill_info->Currency->cc_currency_code : "-") ),
                ( $bill_info->ip_remaining_amount . " ".  ($bill_info->Currency ? $bill_info->Currency->cc_currency_code : "-") ),
                ( $bill_info->ip_payment_status == 1 ? "Partial Paid" : ( $bill_info->ip_payment_status == 2 ? "Paid" : "Not Paid" ) ),
            ];
        }


        $csv = Writer::createFromFileObject(new \SplTempFileObject());

// Ensure BOM for UTF-8 (important for Excel)
        $csv->setOutputBOM(Writer::BOM_UTF8);

// Insert your data
        $csv->insertAll($data);

// Return response with correct headers
        return response((string) $csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="bills-reports.csv"');

    }


    public function GetListBillResult(Request $request)
    {
        $ip_id = $request->input('ip_id');
        $result_array = array();

        $lst_billresult_workflow = BillsResultsWorkflow::whereBwIsDeleted(0)->whereBwBillId($ip_id)->orderBy('bw_creation_date','DESC')->get();
        $data = array(
            "lst_billresult_workflow" => $lst_billresult_workflow
        );
        $result_array['is_error'] = 0;
        $result_array['display'] = view('billing.listbillresults',$data)->render();
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
        $lst_rvc_payments        =BillsRvs::whereBrIsDeleted(0)->whereBrBillId($bi_id)->get();

        $bill_info = InvoicePayments::find($bi_id);

        $data = array(
            'lst_collectors' => $lst_collectors,
            'lst_technicians' => $lst_technicians,
            'lst_admins' => $lst_admins,
            'lst_currency' => $lst_currencies,
            'lst_rvc_payments' => $lst_rvc_payments,
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
        $ip_collector_id                    = $request->input('ip_collector_id');
        $ip_payment_type_id                 = $request->input('ip_payment_type_id');
        $ip_billing_nbr                     = $request->input('ip_billing_nbr');
        $ip_payment_amount                  = $request->input('ip_payment_amount');
        $ip_currency_id                     = $request->input('ip_currency_id');
        $ip_pay_date                        = $request->input('ip_pay_date');
        $ip_paid_amount                        = $request->input('ip_paid_amount');
        $ip_remaining_amount                        = $request->input('ip_remaining_amount');
        $ip_extra_amount                        = $request->input('ip_extra_amount');
        $ip_updated_by                      = session('user_id');
        $ip_updated_date                    = date("Y-m-d");
        $ip_is_update                       = $request->has('ip_is_update') ? 1 : 0;
        $ip_billing_status                  = $request->has('ip_billing_status') ? 1 : 0;
        $ip_payment_status                  = 0;

        $bills_info     = new InvoicePayments();
        $is_new = true;
        $ip_client_id = 0;
        if( $ip_id  > 0 )
        {
            $bills_info    = InvoicePayments::find( $ip_id );
            $bills_info->ip_updated_by = $ip_updated_by;
            $bills_info->ip_updated_date = $ip_updated_date;
            $ip_client_id = $bills_info->ip_client_id;
            $is_new = false;
        }

        if($ip_client_id == null)
        {
            $client_code = $bills_info->ip_client_code;
            $client_info = CRMAccounts::whereCaAccountCode($client_code)->first();

            $ip_client_id = $client_info->ca_id;
        }


        if($ip_remaining_amount > 0) // partial payment
        {
            $ip_payment_status = 1;
        }
        else if($ip_remaining_amount == 0) // full payment
        {
            $ip_payment_status = 2;
        }


        $client_info = CRMAccounts::find($ip_client_id);
        $account_id = $client_info->ca_accounting_id;
        $payment_type = PaymentTypes::find($ip_payment_type_id);
        $pt_payment_account = $payment_type->pt_payment_account;
        $transaction_id = $bills_info->ip_transaction_id;

        $bills_info->ip_payment_doc                     = $ip_payment_doc;
        $bills_info->ip_client_id                       = $ip_client_id;
        $bills_info->ip_collector_id                    = $ip_collector_id;
        $bills_info->ip_payment_type_id                 = $ip_payment_type_id;
        $bills_info->ip_billing_status                  = $ip_billing_status;
        $bills_info->ip_paid_amount                     = $ip_paid_amount;
        $bills_info->ip_remaining_amount                = $ip_remaining_amount;
        $bills_info->ip_billing_nbr                     = $ip_billing_nbr;
      //  $bills_info->ip_payment_amount                  = $ip_payment_amount;
        $bills_info->ip_currency_id                     = $ip_currency_id;
        $bills_info->ip_payment_status                  = $ip_payment_status;
        if($ip_billing_status == 1)
            $bills_info->ip_pay_date                     = $ip_pay_date;
        $bills_info->save();


        // create rvs record for bills
        {


            $rvs_payment = new BillsRvs();
            $rvs_payment->br_bill_id = $bills_info->ip_id;
            $rvs_payment->br_deal_id = $bills_info->ip_deal_id;
            $rvs_payment->br_client_id = $ip_client_id;
            $rvs_payment->br_client_code = $client_info->ca_account_code;
            $rvs_payment->br_client_name = $client_info->ca_account_name;
            $rvs_payment->br_bill_amount = $ip_paid_amount + $ip_remaining_amount;
            $rvs_payment->br_paid_amount = $ip_paid_amount;
            $rvs_payment->br_remaining_amount = $ip_remaining_amount;
            $rvs_payment->br_currency_id = $ip_currency_id;
            $rvs_payment->br_notes = "";
            $rvs_payment->save();

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
            $mv_id = $trans_mov->tm_id;

            $rvs_payment->br_trans_id   = $at_id;
            $rvs_payment->br_mov_id   = $mv_id;
            $rvs_payment->save();

        }



        if($ip_billing_status == 1)
        {

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


            // check if we have comission in payment bill
            $ip_sales_comission = $bills_info->ip_sales_comission;
            if($ip_sales_comission > 0)
            {
                $deal_id = $bills_info->ip_deal_id;

                $deal_info = CRMDeals::find($deal_id);
                if($deal_info != null)
                {
                    $payroll_comissions = new PayrollsComissions();
                    $payroll_comissions->pc_employee_id = $deal_info->fk_sales_id;
                    $payroll_comissions->pc_company_id = session('company_id');
                    $payroll_comissions->pc_comission_value = $deal_info->ad_sales_comm;
                    $payroll_comissions->pc_currency_id = $deal_info->ad_currency_id;
                    $payroll_comissions->pc_effective_date = date('Y-m-d');
                    $payroll_comissions->pc_comission_label = "Commission on file # "  . $deal_info->Account->ca_account_code . " - " . $deal_info->Account->ca_account_name;
                    $payroll_comissions->pc_deal_id = $deal_id;
                    $payroll_comissions->save();

                }
            }
        }


        // if extra amount greater then 0 create a rvs for next bill and change the status of bill to partial paied
        if($ip_extra_amount > 0)
        {
            // get the next bill
            $ip_client_id           = $bills_info->ip_client_id;
            $ip_billing_nbr         = $bills_info->ip_billing_nbr;
            $lst_bills_remaining    = InvoicePayments::whereIpIsDeleted(0)->whereIpClientId($ip_client_id)->where('ip_billing_date','>',$bills_info->ip_billing_date)->orderby('ip_id')->get();

            foreach ( $lst_bills_remaining as $index => $bremaining_info )
            {
                if($ip_extra_amount == 0)
                    break;
                $rip_id = $bremaining_info->ip_id;
                $ip_payment_amount = $bremaining_info->ip_payment_amount;
                $pbill_info = InvoicePayments::find($rip_id);
                $pbill_info->ip_paid_amount = $ip_extra_amount;
                $total = $ip_payment_amount - $ip_extra_amount;
                $pbill_info->ip_remaining_amount = ($total < 0) ? 0 : $total;
                $pbill_info->ip_payment_status = ($total > 0) ? 1 : 2;
                $pbill_info->ip_billing_status = ($total < 0) ? 1 : 0;
                $pbill_info->save();




                $rvs_payment = new BillsRvs();
                $rvs_payment->br_deal_id = $bremaining_info->ip_deal_id;
                $rvs_payment->br_client_id = $bremaining_info->Client->ca_id;
                $rvs_payment->br_client_code = $bremaining_info->Client->ca_account_code;
                $rvs_payment->br_client_name = $bremaining_info->Client->ca_account_name;
                $rvs_payment->br_bill_amount = $ip_extra_amount;
                $rvs_payment->br_paid_amount = $ip_extra_amount;
                $rvs_payment->br_bill_id = $bremaining_info->ip_id;
                $rvs_payment->br_remaining_amount = ($total < 0) ? 0 : $total;
                $rvs_payment->br_currency_id = $pbill_info->ip_currency_id;
                $rvs_payment->br_notes = "";
                $rvs_payment->save();

                // rvs for the next bill
                $ip_extra_amount = ( $ip_extra_amount - $ip_payment_amount ) > 0 ? $ip_extra_amount : 0;
            }

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
