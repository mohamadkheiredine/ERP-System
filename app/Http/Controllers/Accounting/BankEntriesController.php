<?php
/***********************************************************
BankEntriesController.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 18, 2019
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
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use App\library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\Accounting\ChartAccounts;
use App\models\System\Countries;
use App\models\Accounting\AccountingJournals;
use App\models\Accounting\Journaltypes;
use App\models\Accounting\BankAccounts;
use App\models\System\Currency;
use App\models\Accounting\BankEntries;
use App\models\Billing\PaymentTypes;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;



class BankEntriesController extends Controller
{

    /**
     * Page to Manage Ban Entries for Our System
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    { 
        
        $lst_bank_accounts  = BankAccounts::whereBaIsDeleted(0)->orderBy('ba_bank_name', 'asc')->orderBy('ba_account_number', 'asc')->get();
        
        
        $data = array(
            "lst_bank_accounts" => $lst_bank_accounts
        );
        return Response()->view('banking.bankentries',$data);
    }
    
    
    /**
     * Display list of Banking Entries  saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function displaylistEntries(Request $request)
    {            
       
        $bank_id = $request->input('bank_id');
        $lst_bank_entries  = BankEntries::whereBeIsDeleted(0);
        
        if($bank_id != null && $bank_id > 0 )
            $lst_bank_entries = $lst_bank_entries->whereBeBankId($bank_id);
            
        $lst_bank_entries = $lst_bank_entries->get();
        
        $data = array(
            "lst_bank_entries" => $lst_bank_entries
        );
        
        $result_array = array();
         
        $result_array['display'] = view("banking.listentries",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Open form of add new Bank Entry
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function AddForm()
    {
        $lst_bank_accounts  = BankAccounts::whereBaIsDeleted(0)->orderBy('ba_bank_name', 'asc')->orderBy('ba_account_number', 'asc')->get();
        $lst_currencies = Currency::all();
        $lst_chart_accounts = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_payment_types = PaymentTypes::wherePtIsDeleted(0)->get();
        
        $data = array(
            "lst_bank_accounts" => $lst_bank_accounts,
            "lst_payment_types" => $lst_payment_types,
            "lst_chart_accounts" => $lst_chart_accounts,
            "lst_currencies" => $lst_currencies,
        );
        
        return Response()->view('banking.addentryform',$data);
    }
    
    
    
    /**
     * Page of Edit Form Entry Bank
     * 
     * @author Moe Mantach
     * @access public
     * @param unknown $ba_id
     * @return unknown
     */
    public function EditForm( $be_id )
    {
        
        $entry_info         = BankEntries::find($be_id);
        $lst_bank_accounts  = BankAccounts::whereBaIsDeleted(0)->orderBy('ba_bank_name', 'asc')->orderBy('ba_account_number', 'asc')->get();
        $lst_currencies     = Currency::all();
        $lst_chart_accounts = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_payment_types = PaymentTypes::wherePtIsDeleted(0)->get();
        
        $data = array(
            "lst_bank_accounts" => $lst_bank_accounts,
            "lst_payment_types" => $lst_payment_types,
            "lst_currencies" => $lst_currencies,
            "lst_chart_accounts" => $lst_chart_accounts,
            "entry_info" => $entry_info,
        );
        
        return Response()->view('banking.editentryform',$data);
        
    }
    
    
    /**
     * function to save data of Banking Entry to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveEntryInfo(Request $request)
    {
        $be_id                      = $request->input('be_id');
        $be_bank_id                 = $request->input('be_bank_id'); 
        $be_entry_type              = $request->input('be_entry_type'); 
        $be_transfer_transmitter    = $request->input('be_transfer_transmitter'); 
        $be_bank_transfer           = $request->input('be_bank_transfer'); 
        $be_operation_date          = $request->input('be_operation_date'); 
        $be_value_date              = $request->input('be_value_date'); 
        $be_creation_date           = date("Y-m-d"); 
        $be_entry_label             = $request->input('be_entry_label'); 
        $be_entry_amount            = $request->input('be_entry_amount'); 
        $be_source_account_id       = $request->input('be_source_account_id'); 
        $be_dest_account_id         = $request->input('be_dest_account_id'); 
        $be_entry_description       = $request->input('be_entry_description'); 
        
        $BankEntries = new BankEntries();
        $new_entry = true;
        if($be_id > 0)
        {
            $BankEntries = BankEntries::find( $be_id );
            $new_entry = false;
        }
        else 
        {
            $BankEntries->be_creation_date      = $be_creation_date;
        }
        
        $bank_info = BankAccounts::find($be_bank_id);
        
        $currency_id    = $bank_info->ba_account_currency;
        $journal_id     = $bank_info->ba_accounting_journal;
        
        
        $BankEntries->be_bank_id                = $be_bank_id;
        $BankEntries->be_entry_type             = $be_entry_type;
        $BankEntries->be_transfer_transmitter   = $be_transfer_transmitter;
        $BankEntries->be_bank_transfer          = $be_bank_transfer;
        $BankEntries->be_operation_date         = $be_operation_date;
        $BankEntries->be_value_date             = $be_value_date;
        $BankEntries->be_entry_label            = $be_entry_label;
        $BankEntries->be_entry_amount           = $be_entry_amount;
        $BankEntries->be_source_account_id      = $be_source_account_id;
        $BankEntries->be_dest_account_id        = $be_dest_account_id;
        $BankEntries->be_entry_description      = $be_entry_description;
        $BankEntries->be_currency_id            = $currency_id;
        
        
        if($new_entry == true)
        {
            // Save Entry in the Main Ledger Account
            $transactions = new Transactions();
            $transactions->at_transaction_date  = $be_operation_date;
            $transactions->at_creation_date     = $be_creation_date;
            $transactions->at_accounting_doc    = $be_entry_type;
            $transactions->fk_acc_journal_id    = $bank_info->ba_accounting_journal;
            $transactions->at_currency_id       = $currency_id;
            $transactions->save();
            $at_id = $transactions->at_id;
            
            $trans_movements = New TransactionMovements();
            $trans_movements->fk_tran_id            = $at_id;
            $trans_movements->tm_ledger_account     = $be_source_account_id;
            $trans_movements->tm_sub_ledger_account = $be_dest_account_id;
            $trans_movements->tm_ledger_label       = $be_transfer_transmitter;
            $trans_movements->tm_debit              = 0;
            $trans_movements->tm_credit             = $be_entry_amount;
            $trans_movements->tm_currency_id        = $currency_id;
            $trans_movements->tm_creation_date      = date("Y-m-d");
            $trans_movements->save();
            $tm_id = $trans_movements->tm_id;
            
            
            $BankEntries->be_transaction_id     = $at_id;
            $BankEntries->be_movement_id        = $tm_id;
        }
        else // check existing and change the label with the new entry if existing 
        {
            $tm_id =  $BankEntries->be_movement_id;
            $trans_movements = TransactionMovements::find($tm_id);
            if(count($trans_movements) > 0)
            {
                $trans_movements->tm_credit = $be_entry_amount;
                $trans_movements->save();
            }

        }

        $BankEntries->save();
        
        $result_array = array();
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
        
    }
    
    
    /**
     * Delete Bank Entry info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteEntryInfo(Request $request)
    {
        $be_id  = $request->input('be_id');
        $result_array = array();
        
        
        
        $BankEntries                = BankEntries::find($be_id);
        $BankEntries->be_is_deleted = 1;
        $BankEntries->be_deleted_by = session('user_id');
        $BankEntries->save();
        
        
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
 
}