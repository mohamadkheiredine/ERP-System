<?php
/***********************************************************
JournalVouchersController.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 21, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :
Controller to Create and manage Journal Vouchers Saving
***********************************************************/


namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
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
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use App\library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\Accounting\ChartAccounts;
use App\models\System\Countries;
use App\models\Accounting\AccountCategories;
use App\models\Billing\PaymentTypes;
use App\models\Billing\PaymentVouchers;
use App\models\System\Currency;
use App\library\AccountingManager;
use App\models\Users\Users;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\models\Billing\VoucherExtensions;
use App\models\Billing\CreditNotes;
use App\models\Billing\JournalVouchers;
use App\models\Accounting\Journaltypes;



class JournalVouchersController extends Controller
{

    /**
     * Page to Manage Journal Vouchers section
     * Database
     *
     * @author Moe mantach
     * @access public
     * @return View
     */
    public function index()
    {
        $lst_chart_accounts = ChartAccounts::whereAaIsDeleted(0)->get();
        $lst_currencies     = Currency::all();

        $data = array(
            "lst_chart_accounts" => $lst_chart_accounts,
            "lst_currencies" => $lst_currencies,
        );
        return Response()->view('billing.journalvouchers',$data);
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
        $jv_account_id              = $request->input('jv_account_id');
        $jv_start_date              = $request->input('jv_start_date');
        $jv_end_date                = $request->input('jv_end_date');
        $jv_currency_id             = $request->input('jv_currency_id');
        $page_number                = $request->input("page_number");
        $nbr_rows_per_pages         = Config::get('appconfig.max_rows_per_page');
        $fisical_year =  $request->cookie('fisical_year')  !== null ? $request->cookie('fisical_year') : date("Y");

        if($fisical_year != 0)
        {
            $strfirstday = 'first day of January ' .$fisical_year;
            $strlastday = 'last day of December ' . $fisical_year;

            $firstday = date("Y-m-d",strtotime($strfirstday));
            $lastday = date("Y-m-d",strtotime($strlastday));
        }
        else
        {
            $firstday = "";
            $lastday = "";
        }

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        $lst_journal_vouchers       = JournalVouchers::wherePjIsDeleted(0);

        // filter items
        if($jv_account_id  > 0)
            $lst_journal_vouchers= $lst_journal_vouchers->where('pj_account_debit',$jv_account_id);
        if(strlen($jv_start_date) > 0)
            $lst_journal_vouchers= $lst_journal_vouchers->where('pj_creation_date','>=',$jv_start_date);
        if(strlen($jv_end_date) > 0)
            $lst_journal_vouchers= $lst_journal_vouchers->where('pj_creation_date','<',$jv_end_date);

        if(strlen($jv_start_date) ==  0 && strlen($jv_end_date) ==  0)
        {
            if($firstday != "" ||   $lastday != "")
                $lst_journal_vouchers= $lst_journal_vouchers->whereBetween('pj_creation_date', [$firstday, $lastday]);
        }


        $jv_count =     $lst_journal_vouchers->count();
        $total_pages = ceil( $jv_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_journal_vouchers   = $lst_journal_vouchers->skip($skip)->take($nbr_rows_per_pages)->orderBy('pj_creation_date','DESC')->get();

        $lst_currency           = Currency::all();
        $currency_array         = CreateDatabaseArrayByIndex($lst_currency,"cc_id");


        $data = array(
            "lst_journal_vouchers" => $lst_journal_vouchers,
            "currency_array" => $currency_array
        );

        $result_array = array();
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("billing.lstjournalvouchers",$data)->render();

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

        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies     = Currency::all();
        $account_management = new AccountingManager();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $jv_code            = $account_management->GetJournalVoucherCode();
        $lst_journals       = Journaltypes::all();

        $data = array(
            'lst_chart_accounts' => $lst_accounts,
            'lst_journals'      => $lst_journals,
            'lst_users' => $lst_users,
            'jv_code' => $jv_code,
            "lst_currencies" => $lst_currencies
        );

        return Response()->view('billing.addjvoucherform',$data);
    }




    /**
     * get information of selected Account and  and open the edit form fields
     * @param unknown $w_id
     * @return \Illuminate\Http\Response
     */
    public function EditForm( $jv_id )
    {

        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies     = Currency::all();
        $account_management = new AccountingManager();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $jv_info            = JournalVouchers::find($jv_id);
        $lst_journals       = Journaltypes::all();

        $data = array(
            'lst_chart_accounts' => $lst_accounts,
            'lst_users' => $lst_users,
            'jv_info' => $jv_info,
            'lst_journals' => $lst_journals,
            "lst_currencies" => $lst_currencies
        );

        return Response()->view('billing.editjvoucherform',$data);

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
    public function SaveJournalVoucherInfo(Request $request)
    {
        $pj_id                  = $request->input('pj_id');
        $pj_user_id             = $request->input('pj_user_id');
        $pj_code                = $request->input('pj_code');
        $pj_voucher_label       = $request->input('pj_voucher_label');
        $pj_voucher_description = $request->input('pj_voucher_description');
        $pj_journal_id          = $request->input('pj_journal_id');
        $pj_account_credit      = $request->input('pj_account_credit');
        $pj_account_debit       = $request->input('pj_account_debit');
        $pj_creation_date       = $request->input('pj_creation_date');
        $pj_creation_date       = date("Y-m-d",strtotime($pj_creation_date));
        $pj_payment_amount      = $request->input('pj_payment_amount');
        $pj_currency_id         = $request->input('pj_currency_id');
        $default_company_id = session('default_company_id');

        $journal_voucher     = new JournalVouchers();
        $is_new = true;
        if( $pj_id  > 0 )
        {
            $journal_voucher    = JournalVouchers::find( $pj_id );
            $is_new = false;
        }

        $journal_voucher->pj_code                   = $pj_code;
        $journal_voucher->pj_company_id                = $default_company_id;
        $journal_voucher->pj_user_id                = $pj_user_id;
        $journal_voucher->pj_account_credit         = $pj_account_credit;
        $journal_voucher->pj_account_debit          = $pj_account_debit;
        $journal_voucher->pj_creation_date          = $pj_creation_date;
        $journal_voucher->pj_voucher_label          = $pj_voucher_label;
        $journal_voucher->pj_voucher_description    = $pj_voucher_description;
        $journal_voucher->pj_payment_amount         = $pj_payment_amount;
        $journal_voucher->pj_currency_id            = $pj_currency_id;
        $journal_voucher->save();
        $pj_id  = $journal_voucher->pj_id;

        {

            // Delete Old Transaction and movment
            $trans_id = $journal_voucher->pj_transaction_id;
            if( $trans_id > 0 )
            {
                $delete_trans = Transactions::where('at_id',$trans_id)->delete();
                $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();

            }

            // add transaction record
            $AccTransaction = new Transactions();
            $AccTransaction->at_transaction_date    = $pj_creation_date;
            $AccTransaction->at_creation_date       = date("Y-m-d");
            $AccTransaction->at_accounting_doc      = $pj_code . " " . $pj_voucher_label;
            $AccTransaction->fk_acc_journal_id      = 3;
            $AccTransaction->save();
            $at_id = $AccTransaction->at_id;


            // add debit record to the transaction
            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_company_id            = $default_company_id;
            $TransactionMovement->tm_ledger_account     = $pj_account_debit;
            $TransactionMovement->tm_sub_ledger_account = $pj_account_debit;
            $TransactionMovement->tm_ledger_label       = $pj_code . " " . $pj_voucher_label;
            $TransactionMovement->tm_debit              = $pj_payment_amount;
            $TransactionMovement->tm_credit             = 0;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_transaction_date   = $pj_creation_date;
            $TransactionMovement->tm_currency_id        = $pj_currency_id;
            $TransactionMovement->save();


            // add debit record to the transaction
            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_company_id            = $default_company_id;
            $TransactionMovement->tm_ledger_account     = $pj_account_credit;
            $TransactionMovement->tm_sub_ledger_account = $pj_account_credit;
            $TransactionMovement->tm_ledger_label       = $pj_code . " " . $pj_voucher_label;
            $TransactionMovement->tm_debit              = 0;
            $TransactionMovement->tm_credit             = $pj_payment_amount;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_transaction_date   = $pj_creation_date;
            $TransactionMovement->tm_currency_id        = $pj_currency_id;
            $TransactionMovement->save();


        }

        $journal_voucher= JournalVouchers::find( $pj_id );
        $journal_voucher->pj_transaction_id =  $at_id;
        $journal_voucher->save();


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);

    }




    /**
     * Delete journal voucher info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteJournalVoucherInfo(Request $request)
    {
        $pj_id = $request->input('pj_id');
        $result_array = array();



        $journal_vouchers   = JournalVouchers::find($pj_id);
        $journal_vouchers->pj_is_deleted = 1;
        $journal_vouchers->ph_deleted_by = session('user_id');
        $journal_vouchers->save();

        $trans_id = $journal_vouchers->pj_transaction_id;

        $delete_trans = Transactions::where('at_id',$trans_id)->delete();
        $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }




}
