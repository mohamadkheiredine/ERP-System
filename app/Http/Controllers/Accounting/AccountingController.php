<?php

/***********************************************************
AccountingController.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

 ***********************************************************/


namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use App\library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\Accounting\ChartAccounts;
use App\models\System\Countries;
use App\models\Accounting\AccountingJournals;
use App\models\Accounting\Journaltypes;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\library\AccountsManager;
use App\library\AccountingManager;
use App\models\System\Currency;
use Dompdf\Dompdf;
use App\models\Billing\Invoices;
use App\models\Billing\Receipts;
use App\models\Billing\PaymentVouchers;
use App\models\Billing\InternalTransfers;



class AccountingController extends Controller
{

    /**
     * Page to display Account Balance
     *
     * @author Moe Mantach
     * @access public
     */
    public function AccountBalance()
    {
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();


        $data = array(
            "lst_accounts" => $lst_accounts
        );
        return Response()->view('accounting.accountsbalance', $data);
    }


    /**
     * Display List Account Balance based on parameters selected
     * in the Main Page of Account Balance
     *
     * @author Moe Mantach
     * @access poublic
     * @param Request $request
     */
    public function DisplayListAccountBalance(Request $request)
    {
        $start_date             = $request->input("start_date");
        $end_date               = $request->input("end_date");
        $acc_account_payable    = $request->input("acc_account_payable");
        $acc_account_receivable = $request->input("acc_account_receivable");

        $result_array   = array();

        $lst_movements = TransactionMovements::whereRaw("1 = 1");
        if ($start_date != '')
            $lst_movements = $lst_movements->where("tm_creation_date", ">=", $start_date);
        if ($end_date != '')
            $lst_movements = $lst_movements->where("tm_creation_date", "<", $end_date);
        if ($acc_account_payable > 0)
            $lst_movements = $lst_movements->where("tm_ledger_account", "=", $acc_account_payable);
        if ($acc_account_receivable > 0)
            $lst_movements = $lst_movements->where("tm_sub_ledger_account", "=", $acc_account_receivable);

        $lst_movements = $lst_movements->get();

        $AccountingManager = new AccountingManager();

        $account_balance    = $AccountingManager->GetTotalAccountBalance($lst_movements);
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts, "aa_id");


        $data = array(
            "account_balance" => $account_balance,
            "accounts_array" => $accounts_array
        );
        $result_array['is_error'] = 0;
        $result_array['display'] = view("accounting.lstaccountbalance", $data)->render();

        return Response()->json($result_array);
    }



    /**
     * Page Open Voucher open once a year to save the accounts
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function OpeningVoucher(Request $request)
    {
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();

        $lst_journals       = AccountingJournals::whereAjIsDeleted(0)->orderBy('aj_journal_code', 'asc')->orderBy('aj_journal_label', 'asc')->get();


        $opening_journal = AccountingJournals::whereAjJournalCode("OJ")->get();
        $journal_id = 0;

        foreach ($opening_journal as $key => $journal_info) {
            $journal_id = $journal_info->aj_id;
        }



        $opening_voucher_trans = Transactions::whereFkAccJournalId($journal_id)->get();

        $ov_info = new Transactions();
        if (count($opening_voucher_trans) >= 1) {
            foreach ($opening_voucher_trans as $key => $trans_info) {
                $at_id = $trans_info->at_id;
            }

            $ov_info = Transactions::find($at_id);
        }


        $data = array(
            "lst_accounts" => $lst_accounts,
            "ov_info" => $ov_info,
            "lst_journals" => $lst_journals
        );
        return Response()->view('accounting.openingvoucher', $data);
    }


    public function DisplayListOpeningVouchers(Request $request)
    {
        $at_transaction_date    = $request->input("at_transaction_date");
        $fisical_year = $request->input("fisical_year");
        $lst_journals       = AccountingJournals::whereAjIsDeleted(0)->orderBy('aj_journal_code', 'asc')->orderBy('aj_journal_label', 'asc')->get();


        $opening_journal = AccountingJournals::whereAjJournalCode("OJ")->get();
        $journal_id = 0;

        foreach ($opening_journal as $key => $journal_info) {
            $journal_id = $journal_info->aj_id;
        }

        $opening_voucher_trans = Transactions::whereFkAccJournalId(8)->whereYear("at_transaction_date", $fisical_year)->get();

        $ov_info = new Transactions();
        $at_id = 0;
        if (count($opening_voucher_trans) >= 1) {
            foreach ($opening_voucher_trans as $key => $trans_info) {
                $at_id = $trans_info->at_id;
            }

            $ov_info = Transactions::find($at_id);
        }

        $lst_movements      = TransactionMovements::whereFkTranId($at_id)->whereYear("tm_transaction_date", $fisical_year)->get();

        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts, "aa_id");

        $data = array(
            "lst_movements" => $lst_movements,
            "accounts_array" => $accounts_array,
        );
        $result_array['display'] = view("accounting.listopeningvouchermovements", $data)->render();

        unset($AccountingManager);
        return Response()->json($result_array);
    }


    /**
     * Generate ALl configuration related to the new year if exist change it and reload it
     * 411
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GenerateYearConfiguration(Request $request)
    {

        $fisical_year = $request->input('fisical_year');
        $old_year = intval($fisical_year) - 1;

        $first_day_lyear = $old_year . "-01-01";
        $last_day_lyear  = $old_year . "-12-31";



        $firstday = $fisical_year . "-01-01";
        $lastday =  $fisical_year . "-12-31";


        $query_cond = "";
        $query = "SELECT cc_id,tm_sub_ledger_account,cc_currency_code,accounts.aa_account_ref,accounts.aa_account_label,accounts.aa_id,SUM(tm_debit) as total_debit,SUM(tm_credit) as total_credit, SUM(tm_debit) - SUM(tm_credit) AS total_balance  FROM acc_transaction_movements tm left join acc_accounting_accounts accounts on tm.tm_sub_ledger_account = accounts.aa_id left join currency curr on tm.tm_currency_id = curr.cc_id where (accounts.aa_account_ref LIKE '411%' OR accounts.aa_account_ref LIKE '4011%' )  AND tm_debit != 1  ";


        if ($firstday != "" && $lastday != "") {
            $query .= " AND ( tm.tm_transaction_date BETWEEN '$firstday' AND  '$lastday')";
        }


        $query = $query . " group by tm_sub_ledger_account,tm_currency_id  order by accounts.aa_account_ref,tm_currency_id DESC;";
        $lst_accounts = DB::select($query);

        //         if(count($lst_accounts) == 0)
        //         {
        //             $result_array['is_error'] = 1;
        //             $result_array['error_msg'] = "Number of records not exist";

        //             return Response()->json($result_array);
        //         }

        $transaction_data = Transactions::whereFkAccJournalId(8)->whereAtTransactionDate($firstday)->get();

        $at_creation_date       = date("Y-m-d");
        $at_transaction_date =      $fisical_year . "-01-01";


        $ov_info = new Transactions();
        $at_id = 0;
        if (count($transaction_data) >= 1) {
            foreach ($transaction_data as $key => $trans_info) {
                $at_id = $trans_info->at_id;
            }

            $ov_info = Transactions::find($at_id);
        } else {
            // opening voucher transaction
            $ov_info->at_creation_date    = $at_creation_date;
            $ov_info->at_transaction_date     = $at_transaction_date;
            $ov_info->at_accounting_doc       = "Opening Voucher";
            $ov_info->fk_acc_journal_id       = 8;
            $ov_info->at_currency_id          = 0;
            $ov_info->save();
            $at_id = $ov_info->at_id;
        }


        $result_array['is_error'] = 0;

        return Response()->json($result_array);
    }

    public function AddNewTransMovementRow()
    {
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies     = Currency::all();

        $data = array(
            "lst_accounts" => $lst_accounts,
            "lst_currencies" => $lst_currencies
        );
        $result_array['display'] = view("accounting.newopmovementrow", $data)->render();

        return Response()->json($result_array);
    }


    /**
     * Save Transaction information for open Voucher
     *
     * @author Moe mantach
     * @accecss public
     *
     * @param Request $request
     */
    public function SaveTransactionovInfo(Request $request)
    {
        $at_id                  = $request->input("at_id");
        $at_transaction_date    = $request->input("at_transaction_date");
        $at_accounting_doc      = $request->input("at_accounting_doc");
        $fk_acc_journal_id      = $request->input("fk_acc_journal_id");
        $at_currency_id         = Session("company_currency");
        $result_array = array();

        $tm_sub_ledger_account  = $request->input("tm_sub_ledger_account");
        $tm_ledger_label        = $request->input("tm_ledger_label");
        $tm_debit               = $request->input("tm_debit");
        $tm_credit              = $request->input("tm_credit");
        $tm_currency_id         = $request->input("tm_currency_id");

        $total_debit            = 0;
        $total_credit           = 0;

        $Accountingtransaction = new Transactions();

        if ($at_id != null) {
            $Accountingtransaction = Transactions::find($at_id);
        } else {
            $at_creation_date       = date("Y-m-d");
            $Accountingtransaction->at_creation_date    = $at_creation_date;
        }

        $Accountingtransaction->at_transaction_date     = $at_transaction_date;
        $Accountingtransaction->at_accounting_doc       = $at_accounting_doc;
        $Accountingtransaction->fk_acc_journal_id       = $fk_acc_journal_id;
        $Accountingtransaction->at_currency_id          = $at_currency_id;

        $Accountingtransaction->save();

        $fk_trans_id =  $Accountingtransaction->at_id;

        if ($tm_sub_ledger_account != null) {
            for ($i = 0; $i < count($tm_sub_ledger_account); $i++) {
                $ledger_account     = $tm_sub_ledger_account[$i];
                $ledger_label       = $tm_ledger_label[$i];
                $debit              = $tm_debit[$i];
                $credit              = $tm_credit[$i];
                $currency_id        = $tm_currency_id[$i];

                if ($currency_id == 0)
                    continue;

                $Movement_obj = new TransactionMovements();
                $Movement_obj->fk_tran_id               = $fk_trans_id;
                $Movement_obj->tm_ledger_account        = 0;
                $Movement_obj->tm_sub_ledger_account    = $ledger_account;
                $Movement_obj->tm_ledger_label          = $ledger_label;
                $Movement_obj->tm_debit                 = $debit;
                $Movement_obj->tm_credit                = $credit;
                $Movement_obj->tm_creation_date         = date("Y") . "-01-01";
                $Movement_obj->tm_transaction_date      = date("Y") . "-01-01";
                $Movement_obj->tm_currency_id           = $currency_id;
                $Movement_obj->save();
            }
        } else {
            $lst_movement = TransactionMovements::whereFkTranId($fk_trans_id)->get();

            foreach ($lst_movement as $key => $mov) {
                $mov_info = TransactionMovements::find($mov->tm_id);
                $mov_info->tm_creation_date         = date("Y") . "-01-01";
                $mov_info->tm_transaction_date      = date("Y") . "-01-01";
                $mov_info->save();
            }
        }


        $result_array['is_error']   = 0;
        $result_array['at_id']      = $fk_trans_id;
        $result_array['error_msg']  = "Operation Completed Successfully";

        return Response()->json($result_array);
    }



    /**
     * Save Movement Row information and check if the balance on debit and credit
     * is the same else we return error and error_msg to show the difference
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function SaveMovementRowovInfo(Request $request)
    {
        $tm_id                  = $request->input("tm_id");
        $at_id                  = $request->input("at_id");
        $tm_sub_ledger_account  = $request->input("tm_sub_ledger_account");
        $tm_ledger_label        = $request->input("tm_ledger_label");
        $tm_debit               = $request->input("tm_debit");
        $tm_credit              = $request->input("tm_credit");
        $tm_currency_id         = $request->input("tm_currency_id");
        $result_array           = array();

        $TransMovements = TransactionMovements::find($tm_id);
        $TransMovements->tm_ledger_account      = 0;
        $TransMovements->tm_sub_ledger_account  = $tm_sub_ledger_account;
        $TransMovements->tm_ledger_label        = $tm_ledger_label;
        $TransMovements->tm_debit               = $tm_debit;
        $TransMovements->tm_credit              = $tm_credit;
        $TransMovements->tm_currency_id         = $tm_currency_id;
        $TransMovements->save();

        $lst_movements      = TransactionMovements::whereFkTranId($at_id)->get();

        $AccountingManager = new AccountingManager();
        $debit_credits = $AccountingManager->GetTotalDebitCredits($lst_movements);

        $tm_credit = $debit_credits['credit'];

        $result_array = array();
        $result_array['is_error']   = 0;

        return Response()->json($result_array);
    }


    /**
     * Display Edit Row of Opening Voucher
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayEditOVMovementrow(Request $request)
    {
        $tm_id = $request->input("tm_id");

        $movement_info = TransactionMovements::find($tm_id);
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies     = Currency::all();

        $result_array = array();

        $data = array(
            'movement_info' => $movement_info,
            'lst_currencies' => $lst_currencies,
            'lst_accounts' => $lst_accounts
        );
        $result_array['display'] = view("accounting.editovmovementrow", $data)->render();
        return Response()->json($result_array);
    }


    /**
     * Report Account Statment to show transaction and total transaction for every account
     *
     * @author Moe Mantach
     * @access public
     */
    public function Accountstatment()
    {
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();


        $data = array(
            "lst_accounts" => $lst_accounts
        );
        return Response()->view('accounting.accountstatment', $data);
    }


    public function AccountStatmentDetails()
    {
        $data = array();
        return Response()->view('accounting.accountstatmentdetails', $data);
    }

    /**
     * Display list of Account Statment Report
     * @param Request $request
     * @return unknown
     */
    public function Displaylistaccountstatment(Request $request)
    {
        $start_date     = $request->input("start_date");
        $end_date       = $request->input("end_date");
        $acc_account    = $request->input("acc_account");

        $lst_movements = TransactionMovements::whereRaw("1 = 1");
        if ($start_date != '')
            $lst_movements = $lst_movements->where("tm_creation_date", ">=", $start_date);
        if ($end_date != '')
            $lst_movements = $lst_movements->where("tm_creation_date", "<", $end_date);
        if ($acc_account > 0)
            $lst_movements = $lst_movements->where("tm_sub_ledger_account", "=", $acc_account);
        $lst_movements = $lst_movements->OrderBy('acc_accounting_accounts.aa_account_ref', 'ASC')->get();

        $AccountingManager = new AccountingManager();
        $account_balance    = $AccountingManager->GetListAccountBalanceDetails($lst_movements);
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts, "aa_id");

        $data = array(
            "account_balance" => $account_balance,
            "accounts_array" => $accounts_array
        );
        $result_array['is_error'] = 0;
        $result_array['display'] = view("accounting.lstaccountstatment", $data)->render();

        return Response()->json($result_array);
    }


    /**
     * show transaction Details of selected account
     * @param Request $request
     * @return unknown
     */
    public function ShowTransactionAccountDetails(Request $request)
    {
        $account_id         = $request->input("account_id");
        $currency_id        = $request->input("currency_id");
        $search_query       = $request->input("search_query");
        $ck_include_before  = $request->input("ck_include_before");
        $start_date         = $request->input("start_date");
        $end_date           = $request->input("end_date");

        $currency_info = Currency::find($currency_id);
        $fisical_year =  $request->input('fisical_year')  !== null ? $request->input('fisical_year') : date("Y");

        $strfirstday = 'first day of January ' . $fisical_year;
        $strlastday = 'last day of December ' . $fisical_year;

        $firstday = date("Y-m-d", strtotime($strfirstday));
        $lastday = date("Y-m-d", strtotime($strlastday));


        $lst_movements = TransactionMovements::where('tm_sub_ledger_account', $account_id);

        if (strlen($search_query) > 0) {
            $lst_movements = $lst_movements->where('tm_ledger_label', 'LIKE', "%" . $search_query . "%");
        }
        if (strlen($start_date) == 0 && strlen($end_date) == 0)
            $lst_movements = $lst_movements->whereBetween('tm_transaction_date', [$firstday, $lastday]);
        else if ((strlen($start_date) > 0 && strlen($end_date) == 0))
            $lst_movements = $lst_movements->whereBetween('tm_transaction_date', [$start_date, $lastday]);
        else if (strlen($start_date) == 0 && strlen($end_date) > 0)
            $lst_movements = $lst_movements->whereBetween('tm_transaction_date', [$firstday, $end_date]);
        else
            $lst_movements = $lst_movements->whereBetween('tm_transaction_date', [$start_date, $end_date]);

        $lst_movements = $lst_movements->where('tm_currency_id', '=', $currency_id);

        $lst_movements = $lst_movements->orderby('tm_sub_ledger_account', "ASC")->orderby('tm_transaction_date', "ASC")->get();


        $before_date = $start_date . " - 1 day";
        $before_date = strtotime($before_date);
        $before_date = date("Y-m-d", $before_date);


        $after_date = $firstday . " + 1 day";
        $after_date = strtotime($after_date);
        $after_date = date("Y-m-d", $after_date);

        $movement_data_array = array();

        $total_balance = 0; // total balance for including before
        // if including before checked we calculate the cumilative from start date to current date
        $inc_before_total = array();
        if ($ck_include_before == 1) {
            DB::connection()->enableQueryLog();
            $lst_movement_incs = TransactionMovements::where('tm_sub_ledger_account', $account_id);

            if (strlen($search_query) > 0)
                $lst_movement_incs = $lst_movement_incs->where('tm_ledger_label', 'LIKE', "%" . $search_query . "%");

            if (strlen($start_date) > 0) {
                $lst_movement_incs = $lst_movement_incs->where('tm_transaction_date', '>=', $firstday);
                $lst_movement_incs = $lst_movement_incs->where('tm_transaction_date', '<', $start_date);
            }


            $lst_movement_incs = $lst_movement_incs->where('tm_currency_id', '=', $currency_id);

            $lst_movement_incs = $lst_movement_incs->orderby('tm_sub_ledger_account', "ASC")->orderby('tm_transaction_date', "ASC")->get();



            $previews_balance = 0;
            $debit = 0;
            $credit = 0;
            foreach ($lst_movement_incs as $key => $mv_incs_info) {
                $debit =  $mv_incs_info->tm_debit;
                $credit =  $mv_incs_info->tm_credit;
                if ($previews_balance == 0) {
                    $total_balance = floatval($debit) - floatval($credit);
                    $previews_balance = floatval($debit) - floatval($credit);
                } else {
                    $total_balance = floatval($total_balance)  + floatval($debit) - floatval($credit);
                    $previews_balance = floatval($total_balance)  + floatval($debit) - floatval($credit);
                }
            }

            foreach ($lst_movement_incs as $key => $mv_incs_info) {
                $currency_code =  $mv_incs_info->currency->cc_currency_code;

                $currency_id    = $mv_incs_info->currency->cc_id;
                $account_id     = $mv_incs_info->tm_sub_ledger_account;
                if (isset($inc_before_total[$currency_id])) {
                    $inc_before_total[$currency_id]['debit']                        = $inc_before_total[$currency_id]['debit'] + $mv_incs_info->tm_debit;
                    $inc_before_total[$currency_id]['credit']                       = $inc_before_total[$currency_id]['credit']  + $mv_incs_info->tm_credit;

                    $inc_before_total[$currency_id]['balance'] = $inc_before_total[$currency_id]['balance']  + ($mv_incs_info->tm_debit - $mv_incs_info->tm_credit);

                    $movement_data_array[$currency_id][0][0]['debit']                =  $movement_data_array[$currency_id][0][0]['debit'] + $mv_incs_info->tm_debit;
                    $movement_data_array[$currency_id][0][0]['credit']              =  $movement_data_array[$currency_id][0][0]['credit'] + $mv_incs_info->tm_credit;
                    $movement_data_array[$currency_id][0][0]['balance']           =  $movement_data_array[$currency_id][0][0]['balance'] + ($mv_incs_info->tm_debit - $mv_incs_info->tm_credit);
                } else {
                    $inc_before_total[$currency_id]['debit']                        = $mv_incs_info->tm_debit;
                    $inc_before_total[$currency_id]['credit']                       = $mv_incs_info->tm_credit;

                    $inc_before_total[$currency_id]['balance'] = ($mv_incs_info->tm_debit - $mv_incs_info->tm_credit);


                    $movement_data_array[$currency_id] = array();
                    $movement_data_array[$currency_id][0] = array();
                    $movement_data_array[$currency_id][0][0] = array();
                    $movement_data_array[$currency_id][0][0]['debit']                = $mv_incs_info->tm_debit;
                    $movement_data_array[$currency_id][0][0]['credit']              = $mv_incs_info->tm_credit;
                    $movement_data_array[$currency_id][0][0]['balance']           = $mv_incs_info->tm_debit - $mv_incs_info->tm_credit;
                }




                $inc_before_total[$currency_id]['currency']                     = $mv_incs_info->currency->cc_currency_code;
                $inc_before_total[$currency_id]['date_creation']                = "";
                $inc_before_total[$currency_id]['account_payable']              = "";
                $inc_before_total[$currency_id]['account_receivable']           = "";
                $inc_before_total[$currency_id]['mov_desc']                     = "Revert Back " . $currency_code;
                $inc_before_total[$currency_id]['code']                         = $currency_code;

                $movement_data_array[$currency_id][0][0]['tm_id']                        = 0;
                $movement_data_array[$currency_id][0][0]['trans_id']                     = 0;
                $movement_data_array[$currency_id][0][0]['currency']                     = $mv_incs_info->currency->cc_currency_code;
                $movement_data_array[$currency_id][0][0]['date_creation']                = "";
                $movement_data_array[$currency_id][0][0]['account_payable']              = "";
                $movement_data_array[$currency_id][0][0]['account_receivable']           = "";
                $movement_data_array[$currency_id][0][0]['mov_desc']                     =  "Revert Back " . $currency_code;
                $movement_data_array[$currency_id][0][0]['code']                         = $currency_code;
            }
        }



        $cumulative_credit = array();
        $cumulative_debit =  array();
        $index = 0;
        foreach ($lst_movements as $key => $movement_info) {
            $tm_id      = $movement_info->tm_id;
            $trans_id   = $movement_info->fk_tran_id;

            $invoice_info = Invoices::whereBiTransactionId($trans_id);
            if ($search_query != null) {
                $invoice_info->where(function ($query) use ($search_query) {
                    $query->where("bi_invoice_label", "LIKE", "%" . $search_query . "%")->orWhere("bi_invoice_note", "LIKE", "%" . $search_query . "%")->orWhere("bi_invoice_code", "LIKE", "%" . $search_query . "%");
                });
            }
            $invoice_info = $invoice_info->whereBiIsDeleted(0)->get();

            $receipt_info = Receipts::whereBrTransId($trans_id);
            if ($search_query != null) {
                $receipt_info->where(function ($query) use ($search_query) {
                    $query->where("br_receipt_number", "LIKE", "%" . $search_query . "%")->orWhere("br_receipt_label", "LIKE", "%" . $search_query . "%")->orWhere("br_receipt_note", "LIKE", "%" . $search_query . "%");
                });
            }
            $receipt_info = $receipt_info->get();

            $currency_code =  $movement_info->currency->cc_currency_code;
            $transaction_info = Transactions::find($trans_id);
            $voucher_info = PaymentVouchers::wherePvTransactionId($trans_id);
            if ($search_query != null) {
                $voucher_info->where(function ($query) use ($search_query) {
                    $query->where("pv_voucher_label", "LIKE", "%" . $search_query . "%")->orWhere("pv_code", "LIKE", "%" . $search_query . "%")->orWhere("pv_voucher_description", "LIKE", "%" . $search_query . "%");
                });
            }
            $voucher_info = $voucher_info->get();


            $internal_info = InternalTransfers::whereFkTransId($trans_id);
            if ($search_query != null) {
                $internal_info->where(function ($query) use ($search_query) {
                    $query->where("in_transfer_code", "LIKE", "%" . $search_query . "%")->orWhere("in_transfert_label", "LIKE", "%" . $search_query . "%")->orWhere("in_transfer_notes", "LIKE", "%" . $search_query . "%");
                });
            }
            $internal_info = $internal_info->get();


            if (count($invoice_info) > 0) {
                foreach ($invoice_info as $key => $inv) {
                    $transaction_description    = strip_tags($inv->bi_invoice_note);
                    $transaction_description = str_replace("&nbsp;", " ", $transaction_description);
                    $transaction_date           = $inv->bi_invoice_date;
                    $transaction_code           = $inv->bi_invoice_code;
                }
            } elseif (count($receipt_info) > 0) {

                $receipts_info = Receipts::whereBrTransId($trans_id)->get();

                if (count($receipts_info) > 0) {
                    foreach ($receipts_info as $key => $receipt) {
                        $transaction_description = strip_tags($receipt->br_receipt_note);
                        $transaction_description = str_replace("&nbsp;", " ", $transaction_description);
                        $transaction_date           = $receipt->br_receipt_date;
                        $transaction_code           = $receipt->br_receipt_number;
                    }
                }
            } elseif (count($voucher_info) > 0) {
                foreach ($voucher_info as $key => $voucher) {
                    $transaction_description = strip_tags($voucher->pv_voucher_description);
                    $transaction_description = str_replace("&nbsp;", " ", $transaction_description);
                    $transaction_date           = $voucher->pv_creation_date;
                    $transaction_code           = $voucher->pv_code;
                }
            } elseif (count($internal_info) > 0) {
                foreach ($internal_info as $key => $internal) {
                    $transaction_description = strip_tags($internal->in_transfer_notes);
                    $transaction_description = str_replace("&nbsp;", " ", $transaction_description);
                    $transaction_date           = $internal->in_transfer_date;
                    $transaction_code           = $internal->in_transfer_code;
                }
            } else {
                //elseif( $transaction_info->fk_acc_journal_id == 8 )
                $transaction_description = $movement_info->tm_ledger_label;
                $transaction_date           = $movement_info->tm_creation_date;
                $transaction_code           = $transaction_description;
            }


            if (!array_key_exists($currency_code, $cumulative_credit)) {
                $cumulative_credit[$currency_code] =  $total_balance + $movement_info->tm_credit;
            } else {
                $cumulative_credit[$currency_code] =  $cumulative_credit[$currency_code]  + $movement_info->tm_credit;
            }


            if (!array_key_exists($currency_code, $cumulative_debit)) {
                $cumulative_debit[$currency_code] =  $total_balance + $movement_info->tm_debit;
            } else {
                $cumulative_debit[$currency_code] =  $cumulative_debit[$currency_code]  + $movement_info->tm_debit;
            }


            $currency_id    = $movement_info->currency->cc_id;
            $account_id     = $movement_info->tm_sub_ledger_account;

            if (isset($movement_data_array[$currency_id][$account_id])) {
                $index = count($movement_data_array[$currency_id][$account_id]);
                $movement_data_array[$currency_id][$account_id][$index] = array();
            } else {
                $index = 0;
                $movement_data_array[$currency_id][$account_id][$index] = array();
            }

            $movement_data_array[$currency_id][$account_id][$index]['debit']                        = $movement_info->tm_debit;
            $movement_data_array[$currency_id][$account_id][$index]['credit']                       = $movement_info->tm_credit;

            $movement_data_array[$currency_id][$account_id][$index]['balance'] = $movement_info->tm_debit - $movement_info->tm_credit;

            if ($index == 0) {
                $movement_data_array[$currency_id][$account_id][$index]['balance'] = $total_balance + $movement_info->tm_debit - $movement_info->tm_credit;
            } else {


                if (isset($movement_data_array[$currency_id][$account_id][$index - 1]))
                    $prev_balance =  $movement_data_array[$currency_id][$account_id][$index - 1]['balance'];
                else
                    $prev_balance = 0;

                $movement_data_array[$currency_id][$account_id][$index]['balance'] = $prev_balance + $movement_info->tm_debit - $movement_info->tm_credit;
            }


            $movement_data_array[$currency_id][$account_id][$index]['tm_id']                        = $tm_id;
            $movement_data_array[$currency_id][$account_id][$index]['trans_id']                     = $trans_id;
            $movement_data_array[$currency_id][$account_id][$index]['currency']                     = $movement_info->currency->cc_currency_code;
            $movement_data_array[$currency_id][$account_id][$index]['date_creation']                = $movement_info->tm_transaction_date;
            $movement_data_array[$currency_id][$account_id][$index]['account_payable']              = $movement_info->tm_ledger_account;
            $movement_data_array[$currency_id][$account_id][$index]['account_receivable']           = $movement_info->tm_sub_ledger_account;
            $movement_data_array[$currency_id][$account_id][$index]['mov_desc']                     = $transaction_description;
            $movement_data_array[$currency_id][$account_id][$index]['code']                         = $transaction_code;
        }




        $AccountingManager = new AccountingManager();
        //$account_balance    = $AccountingManager->GetListAccountBalanceDetails($lst_movements , $search_query);
        $account_balance    = $movement_data_array;
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts, "aa_id");
        $data = array(
            "account_balance" => $account_balance,
            "account_id" => $account_id,
            "currency_info" => $currency_info,
            "inc_before_total" => $inc_before_total,
            "show_back" => 1,
            "accounts_array" => $accounts_array
        );

        $result_array['is_error'] = 0;
        $result_array['display'] = view("accounting.lstaccountstatment", $data)->render();

        return Response()->json($result_array);
    }

    /**
     * Display Transaction details
     *
     * @author Moe Mantach
     * @access public
     * @param integer $tran_id
     * @param integer $tm_id
     */
    public function TransactionDetails($tran_id, $tm_id, Request $request)
    {
        $fisical_year = $request->input('fisical_year');
        $lst_movments = TransactionMovements::whereFkTranId($tran_id)->whereYear("tm_transaction_date", $fisical_year)->get();

        $data = array(
            "lst_movements" => $lst_movments,
        );

        return Response()->view("accounting.lsttransactiondetails", $data);
    }

    /**
     * Display List of accounts with totals transaction for debit and credit
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function Displaylistaccounttotals(Request $request)
    {
        $start_date     = $request->input("start_date");
        $end_date       = $request->input("end_date");
        $include_before = $request->input("include_before");
        $search_query   = $request->input("search_query");
        $fisical_year =  $request->input('fisical_year')  !== null ? $request->input('fisical_year') : date("Y");
        $acc_account = $request->input("acc_account");

        $strfirstday = 'first day of January ' . $fisical_year;
        $strlastday = 'last day of December ' . $fisical_year;

        $firstday = date("Y-m-d", strtotime($strfirstday));
        $lastday = date("Y-m-d", strtotime($strlastday));

        if ($start_date != "" && $end_date != "") {
            $firstday = $start_date;
            $lastday = $end_date;
        }

        $query = "
                SELECT
                    curr.cc_id,
                    accounts.aa_id AS tm_sub_ledger_account,
                    curr.cc_currency_code,
                    accounts.aa_account_ref,
                    accounts.aa_account_label,
                    COALESCE(SUM(tm.tm_debit), 0) AS total_debit,
                    COALESCE(SUM(tm.tm_credit), 0) AS total_credit,
                    COALESCE(SUM(tm.tm_debit) - SUM(tm.tm_credit), 0) AS total_balance
                FROM acc_accounting_accounts accounts
                LEFT JOIN acc_transaction_movements tm
                    ON tm.tm_sub_ledger_account = accounts.aa_id
                LEFT JOIN currency curr
                    ON tm.tm_currency_id = curr.cc_id
                WHERE 1
                ";


        if (strlen($search_query) > 0) {
            $query .= " AND ( tm.tm_ledger_label LIKE '%" . $search_query . "%' OR accounts.aa_account_ref LIKE '%" . $search_query . "%' OR accounts.aa_account_label LIKE '%" . $search_query . "%' )";
        }

        if ($start_date != "" && $end_date != "") {
            $query .= " AND ( tm.tm_transaction_date BETWEEN '$start_date' AND  '$end_date')";
        } elseif ($start_date != "") {
            $query .= " AND tm.tm_transaction_date >= '$start_date' ";
        } elseif ($end_date != "") {
            $query .= " AND tm.tm_transaction_date <= '$end_date' ";
        } else {
            $query .= " AND ( tm.tm_transaction_date BETWEEN '$firstday' AND  '$lastday')";
        }

        if ($acc_account > 0) {
            $query .= " AND tm.tm_sub_ledger_account = $acc_account";
        }

        $query .= "
                    GROUP BY
                        accounts.aa_id,
                        curr.cc_id,
                        curr.cc_currency_code,
                        accounts.aa_account_ref,
                        accounts.aa_account_label
                    ORDER BY
                        accounts.aa_account_ref ASC;
                    ";


        $lst_accounts = DB::select($query);

        $data = array(
            "lst_accounts" => $lst_accounts,
        );
        $result_array['is_error'] = 0;
        $result_array['display'] = view("accounting.lstaccountstatmentgroup", $data)->render();

        return Response()->json($result_array);
    }



    public function Printaccountstatment(Request $request)
    {

        $start_date     = $request->input("start_date");
        $end_date       = $request->input("end_date");
        $acc_account    = $request->input("acc_account");
        $currency_id    = $request->input("currency_id");
        $fisical_year   = $request->input("fisical_year");
        $ck_include_before  = $request->input("ck_include_before");


        $strfirstday = 'first day of January ' . $fisical_year;
        $strlastday = 'last day of December ' . $fisical_year;

        $firstday = date("Y-m-d", strtotime($strfirstday));
        $lastday = date("Y-m-d", strtotime($strlastday));

        $before_date = $start_date . " - 1 day";
        $before_date = strtotime($before_date);
        $before_date = date("Y-m-d", $before_date);


        $lst_movements = TransactionMovements::whereRaw("1 = 1");

        if (strlen($start_date) == 0 && strlen($end_date) == 0)
            $lst_movements = $lst_movements->whereBetween('tm_transaction_date', [$firstday, $lastday]);
        else if ((strlen($start_date) > 0 && strlen($end_date) == 0))
            $lst_movements = $lst_movements->whereBetween('tm_transaction_date', [$start_date, $lastday]);
        else if (strlen($start_date) == 0 && strlen($end_date) > 0)
            $lst_movements = $lst_movements->whereBetween('tm_transaction_date', [$firstday, $end_date]);
        else
            $lst_movements = $lst_movements->whereBetween('tm_transaction_date', [$start_date, $end_date]);



        if ($acc_account > 0)
            $lst_movements = $lst_movements->where("tm_sub_ledger_account", "=", $acc_account);


        // $lst_movements = TransactionMovements::where('tm_sub_ledger_account',$acc_account);

        if ($currency_id != null) {
            $lst_movements = $lst_movements->where('tm_currency_id', $currency_id);
        }

        if ($fisical_year == null) {
            $fisical_year = date("Y");
        }

        $lst_movements = $lst_movements->orderby('tm_transaction_date', "ASC")->orderby('tm_id', "ASC")->get();



        $movement_data_array = array();

        $total_balance = 0; // total balance for including before
        // if including before checked we calculate the cumilative from start date to current date
        $inc_before_total = array();
        if ($ck_include_before == 1) {
            $lst_movement_incs = TransactionMovements::where('tm_sub_ledger_account', $acc_account);


            if (strlen($start_date) > 0) {
                $lst_movement_incs = $lst_movement_incs->where('tm_transaction_date', '>=', $firstday);
                $lst_movement_incs = $lst_movement_incs->where('tm_transaction_date', '<', $start_date);
            }


            $lst_movement_incs = $lst_movement_incs->where('tm_currency_id', '=', $currency_id);

            $lst_movement_incs = $lst_movement_incs->orderby('tm_sub_ledger_account', "ASC")->orderby('tm_transaction_date', "ASC")->get();



            $previews_balance = 0;
            $debit = 0;
            $credit = 0;
            foreach ($lst_movement_incs as $key => $mv_incs_info) {
                $debit =  $mv_incs_info->tm_debit;
                $credit =  $mv_incs_info->tm_credit;
                if ($previews_balance == 0) {
                    $total_balance = floatval($debit) - floatval($credit);
                    $previews_balance = floatval($debit) - floatval($credit);
                } else {
                    $total_balance = floatval($total_balance)  + floatval($debit) - floatval($credit);
                    $previews_balance = floatval($total_balance)  + floatval($debit) - floatval($credit);
                }
            }

            foreach ($lst_movement_incs as $key => $mv_incs_info) {
                $currency_code =  $mv_incs_info->currency->cc_currency_code;

                $currency_id    = $mv_incs_info->currency->cc_id;
                $account_id     = $mv_incs_info->tm_sub_ledger_account;
                if (isset($inc_before_total[$currency_id])) {
                    $inc_before_total[$currency_id]['debit']                        = $inc_before_total[$currency_id]['debit'] + $mv_incs_info->tm_debit;
                    $inc_before_total[$currency_id]['credit']                       = $inc_before_total[$currency_id]['credit']  + $mv_incs_info->tm_credit;

                    $inc_before_total[$currency_id]['balance'] = $inc_before_total[$currency_id]['balance']  + ($mv_incs_info->tm_debit - $mv_incs_info->tm_credit);

                    $movement_data_array[$currency_id][0][0]['debit']                =  $movement_data_array[$currency_id][0][0]['debit'] + $mv_incs_info->tm_debit;
                    $movement_data_array[$currency_id][0][0]['credit']              =  $movement_data_array[$currency_id][0][0]['credit'] + $mv_incs_info->tm_credit;
                    $movement_data_array[$currency_id][0][0]['balance']           =  $movement_data_array[$currency_id][0][0]['balance'] + ($mv_incs_info->tm_debit - $mv_incs_info->tm_credit);
                } else {
                    $inc_before_total[$currency_id]['debit']                        = $mv_incs_info->tm_debit;
                    $inc_before_total[$currency_id]['credit']                       = $mv_incs_info->tm_credit;

                    $inc_before_total[$currency_id]['balance'] = ($mv_incs_info->tm_debit - $mv_incs_info->tm_credit);


                    $movement_data_array[$currency_id] = array();
                    $movement_data_array[$currency_id][0] = array();
                    $movement_data_array[$currency_id][0][0] = array();
                    $movement_data_array[$currency_id][0][0]['debit']                = $mv_incs_info->tm_debit;
                    $movement_data_array[$currency_id][0][0]['credit']              = $mv_incs_info->tm_credit;
                    $movement_data_array[$currency_id][0][0]['balance']           = $mv_incs_info->tm_debit - $mv_incs_info->tm_credit;
                }




                $inc_before_total[$currency_id]['currency']                     = $mv_incs_info->currency->cc_currency_code;
                $inc_before_total[$currency_id]['date_creation']                = "";
                $inc_before_total[$currency_id]['account_payable']              = "";
                $inc_before_total[$currency_id]['account_receivable']           = "";
                $inc_before_total[$currency_id]['mov_desc']                     = "Revert Back " . $currency_code;
                $inc_before_total[$currency_id]['code']                         = $currency_code;

                $movement_data_array[$currency_id][0][0]['tm_id']                        = 0;
                $movement_data_array[$currency_id][0][0]['trans_id']                     = 0;
                $movement_data_array[$currency_id][0][0]['currency']                     = $mv_incs_info->currency->cc_currency_code;
                $movement_data_array[$currency_id][0][0]['date_creation']                = "";
                $movement_data_array[$currency_id][0][0]['account_payable']              = "";
                $movement_data_array[$currency_id][0][0]['account_receivable']           = "";
                $movement_data_array[$currency_id][0][0]['mov_desc']                     =  "Revert Back " . $currency_code;
                $movement_data_array[$currency_id][0][0]['code']                         = $currency_code;
            }
        }




        $AccountingManager = new AccountingManager();
        $params_arrays = array(
            "ck_include_before" => $ck_include_before,
            "start_date" => $start_date,
            "end_date" => $end_date
        );
        $account_balance    = $AccountingManager->GetListAccountBalanceDetails($lst_movements, "", $params_arrays);
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->get();
        $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts, "aa_id");

        $data = array(
            "account_balance" => $account_balance,
            "accounts_array" => $accounts_array,
            "inc_before_total" => $inc_before_total
        );
        $display = view("templates.statment", $data)->render();


        $pdf = new Dompdf();
        $pdf->loadHTML($display);
        $pdf->render();
        return $pdf->stream('statment-' . date("Y-m-dH:i:s") . '.pdf');
    }
}
