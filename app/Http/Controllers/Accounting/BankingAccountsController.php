<?php
/***********************************************************
BankingAccountsController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 22, 2019
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
use App\library\BankingManager;



class BankingAccountsController extends Controller
{

    /**
     * Page to Manage Chart of Accounting Journals for Our System
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    { 
        $data = array();
        return Response()->view('banking.bankingaccounts',$data);
    }
    
    
    /**
     * Display list of Journals saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {            
       
 
        $lst_bank_accounts  = BankAccounts::whereBaIsDeleted(0)->orderBy('ba_bank_name', 'asc')->orderBy('ba_account_number', 'asc')->get();
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_journals       = AccountingJournals::whereAjIsDeleted(0)->orderBy('aj_journal_code', 'asc')->orderBy('aj_journal_label', 'asc')->get();
        
        $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts , "aa_id");
        $journals_array     = CreateDatabaseArrayByIndex($lst_journals, "aj_id");
        
        $data = array(
            "lst_bank_accounts" => $lst_bank_accounts, 
            "lst_accounts" => $lst_accounts, 
            "accounts_array" => $accounts_array,
            "journals_array" => $journals_array,
        );
        
        $result_array = array();
         
        $result_array['display'] = view("banking.listbanking",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Open form of add new Bank Account
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function AddForm()
    {
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_journals       = AccountingJournals::whereAjIsDeleted(0)->orderBy('aj_journal_code', 'asc')->orderBy('aj_journal_label', 'asc')->get();
        $lst_countries      = Countries::all();
        $lst_currencies     = Currency::all();
        $account_management = new BankingManager();
        $account_code       = $account_management->GetAccountCode();
        
        
        
        $data = array(
            'lst_accounts' => $lst_accounts,
            'lst_journals' => $lst_journals,
            "lst_countries" => $lst_countries, 
            "account_code" => $account_code, 
            "lst_currencies" => $lst_currencies
        );
        
        return Response()->view('banking.addaccountform',$data);
    }
    
    /**
     * get information of selected Account and  and open the edit form fields
     * @param unknown $w_id
     * @return \Illuminate\Http\Response
     */
    public function EditForm( $ba_id )
    {
        $BankingAccount     = BankAccounts::find( $ba_id );
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_journals       = AccountingJournals::whereAjIsDeleted(0)->orderBy('aj_journal_code', 'asc')->orderBy('aj_journal_label', 'asc')->get();
        $lst_countries      = Countries::all();
        $lst_currencies     = Currency::all();
        
        $data = array(
            'BankingAccount' => $BankingAccount,
            'lst_accounts' => $lst_accounts,
            'lst_journals' => $lst_journals,
            "lst_countries" => $lst_countries,
            "lst_currencies" => $lst_currencies
        );
        
        return Response()->view('banking.editaccountform',$data);
        
    }
    
    
    /**
     * function to save data of warehouse to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveAccountInfo(Request $request)
    {
        $ba_id                      = $request->input('ba_id');
        $ba_account_label           = $request->input('ba_account_label');
        $ba_account_ref             = $request->input('ba_account_ref');
        $ba_account_type            = $request->input('ba_account_type');
        $ba_account_currency        = $request->input('ba_account_currency');
        $ba_account_country         = $request->input('ba_account_country');
        $ba_account_city            = $request->input('ba_account_city');
        $ba_account_comment         = $request->input('ba_account_comment');
        $ba_initial_balance         = $request->input('ba_initial_balance');
        $ba_min_allowed_balance     = $request->input('ba_min_allowed_balance');
        $ba_min_desired_balance     = $request->input('ba_min_desired_balance');
        $ba_bank_name               = $request->input('ba_bank_name');
        $ba_account_number          = $request->input('ba_account_number');
        $ba_account_iban            = $request->input('ba_account_iban');
        $ba_account_swift           = $request->input('ba_account_swift');
        $ba_account_address         = $request->input('ba_account_address');
        $ba_account_owner_name      = $request->input('ba_account_owner_name');
        $ba_account_owner_address   = $request->input('ba_account_owner_address');
        $ba_accounting_account      = $request->input('ba_accounting_account');
        $ba_accounting_journal      = $request->input('ba_accounting_journal');
        
        $BankAccount = new BankAccounts();
        
        if($ba_id > 0)
        {
            $BankAccount = BankAccounts::find( $ba_id );
        }
        
        $BankAccount->ba_account_label          = $ba_account_label;
        $BankAccount->ba_account_ref            = $ba_account_ref;
        $BankAccount->ba_account_type           = $ba_account_type;
        $BankAccount->ba_account_currency       = $ba_account_currency;
        $BankAccount->ba_account_country        = $ba_account_country;
        $BankAccount->ba_account_city           = $ba_account_city;
        $BankAccount->ba_account_city           = $ba_account_city;
        $BankAccount->ba_account_comment        = $ba_account_comment;
        $BankAccount->ba_initial_balance        = $ba_initial_balance;
        $BankAccount->ba_min_allowed_balance    = $ba_min_allowed_balance;
        $BankAccount->ba_min_desired_balance    = $ba_min_desired_balance;
        $BankAccount->ba_bank_name              = $ba_bank_name;
        $BankAccount->ba_account_number         = $ba_account_number;
        $BankAccount->ba_account_iban           = $ba_account_iban;
        $BankAccount->ba_account_swift          = $ba_account_swift;
        $BankAccount->ba_account_address        = $ba_account_address;
        $BankAccount->ba_account_address        = $ba_account_owner_name;
        $BankAccount->ba_account_owner_address  = $ba_account_owner_address;
        $BankAccount->ba_accounting_account     = $ba_accounting_account;
        $BankAccount->ba_accounting_journal     = $ba_accounting_journal;
        $BankAccount->ba_creation_date          = date('Y-m-d H:i:s');
        $BankAccount->save();
        
 
        
        $result_array = array();
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
        
    }
    
    
    /**
     * Delete Bank Account info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteAccountInfo(Request $request)
    {
        $ba_id  = $request->input('ba_id');
        $result_array = array();
        
        
        
        $BankAccounts   = BankAccounts::find($ba_id);
        $BankAccounts->ba_is_deleted = 1;
        $BankAccounts->ba_deleted_by = session('user_id');
        $BankAccounts->save();
        
        
        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
    
    /**
     * Get Currency for specific bank send by id ( bank_id ) in request
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetBankCurrency(Request $request)
    {
        $bank_id        = $request->input('bank_id');
        $bank_info      = BankAccounts::find($bank_id);
        $result_array   = array();
        
        $result_array['is_error'] = 0;
        $result_array['currency_code'] = $bank_info->currency->cc_currency_code;
        
        return Response()->json($result_array);
    }
 
}