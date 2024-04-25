<?php
/***********************************************************
TransactionsController.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 6, 2019
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
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\library\AccountsManager;
use App\library\AccountingManager;
use App\models\System\Currency;



class TransactionsController extends Controller
{

    /**
     * Page to Manage Chart of Accounting Journals for Our System
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function Ledger()
    { 
        
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_journals       = AccountingJournals::whereAjIsDeleted(0)->orderBy('aj_journal_code', 'asc')->orderBy('aj_journal_label', 'asc')->get();
        
        $data = array(
            "lst_accounts" => $lst_accounts,
            "lst_journals" => $lst_journals,
        );
        return Response()->view('accounting.transactions',$data);
    }
    
    
    /**
     * Display list empty transactions that not contains any movements
     * 
     * @author MOe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayListEmptyTransactions(Request $request)
    {
        $result_array           = array(); 
        $start_date             = $request->input("start_date");
        $end_date               = $request->input("end_date");
        $journal_id             = $request->input("journal_id");
        
       $transaction_movements   = TransactionMovements::select('fk_tran_id')->get();
       $lst_journals            = AccountingJournals::whereAjIsDeleted(0)->orderBy('aj_journal_code', 'asc')->orderBy('aj_journal_label', 'asc')->get();
       $journals_array          = CreateDatabaseArrayByIndex($lst_journals, "aj_id");
       
       $trans_array = array();
       
       foreach ( $transaction_movements as $key => $trans_info ) {
           $trans_array[] = $trans_info->fk_tran_id;
       }
        
       
       $transaction_obj = Transactions::whereNotIn("at_id",$trans_array)->get();
      
       $data = array(
           "transaction_obj" => $transaction_obj,
           "journals_array" => $journals_array,
       );
       $result_array['display'] = view("accounting.listemptytransaction",$data)->render();
       
       return Response()->json($result_array);
    }
    
    
    /**
     * Display list of Journals saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function Displaylistmovements(Request $request)
    {            
        $result_array           = array();
        $accounting_manager     = new AccountingManager();
        $start_date             = $request->input("start_date");
        $end_date               = $request->input("end_date");
        $journal_id             = $request->input("journal_id");
        $tm_ledger_account      = $request->input("tm_ledger_account"); 
        $tm_sub_ledger_account  = $request->input("tm_sub_ledger_account"); 
        $page_number            = $request->input("page_number");
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        
        if($page_number > 1)
          $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
          $skip = 0;
        
        $lst_transactions           = Transactions::whereRaw('1 = 1');
        if($journal_id > 0)
            $lst_transactions = $lst_transactions->whereFkAccJournalId($journal_id);
        if($start_date != '')
            $lst_transactions = $lst_transactions->where("at_transaction_date",">=",$start_date);
            
        if($end_date != '')
            $lst_transactions = $lst_transactions->where("at_transaction_date","<",$end_date);
         
        $lst_transactions = $lst_transactions->get();
   
        $trans_array = array();
        
        foreach ($lst_transactions as $key => $tran_info ) {
            $trans_array[] = $tran_info->at_id;
        }
        
        $transactions_array = CreateDatabaseArrayByIndex($lst_transactions, "at_id");
         
        if(count($trans_array) > 0)
            $lst_movements              = TransactionMovements::whereIn("fk_tran_id",$trans_array);
        else 
            $lst_movements              = TransactionMovements::whereRaw('1 = 1');
        
            
        if($tm_ledger_account != '0')
            $lst_movements = $lst_movements->whereTmLedgerAccount($tm_ledger_account);
        if($tm_sub_ledger_account !='0')
            $lst_movements = $lst_movements->whereTmSubLedgerAccount($tm_sub_ledger_account);
            
            $count_movements = $lst_movements->count();
            $lst_movements = $lst_movements->skip($skip)->take($nbr_rows_per_pages)->get();
            $lst_transaction_movements  = $accounting_manager->GenerateTransactionsArray($lst_movements , $transactions_array);
        
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts, "aa_id");
        
        $data = array(
            "lst_transaction_movements" => $lst_transaction_movements,
            "lst_transactions" => $lst_transactions,
            "transactions_array" => $transactions_array,
            "accounts_array" => $accounts_array
        );
        
        
        $total_pages = ceil( $count_movements/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $result_array['display'] = view("accounting.listledger",$data)->render();
        $result_array['total_pages'] = $total_pages;
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Display list of movement for specific transaction
     * 
     *  @author Moe Mantach
     *  @access public 
     *  @param Request $request
     */
    public function DisplayListTransactionmovements(Request $request)
    {
        $at_id = $request->input("at_id");
        
        $lst_movements      = TransactionMovements::whereFkTranId($at_id)->get();

        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts, "aa_id");
        
        // get total debit and credit
        $AccountingManager = new AccountingManager();
        $debit_credits = $AccountingManager->GetTotalDebitCredits($lst_movements);
        
        $data = array(
            "lst_movements" => $lst_movements,
            "accounts_array" => $accounts_array,
        );
        $result_array['display'] = view("accounting.listtransmovements",$data)->render();
        $result_array['debit'] = $debit_credits['debit'];
        $result_array['credit'] = $debit_credits['credit'];
        
        if($debit_credits['debit'] !=  $debit_credits['credit'])
        {
            $result_array['is_error']   = 1;
            $result_array['error_msg']  = "<strong>Error!</strong> Movement not correctly balanced. Debit = " .  $result_array['debit'] . " | Credit = " .  $result_array['credit'];
        }

        unset($AccountingManager);
        return Response()->json($result_array);
    }
    
    /**
     * Display New Row For Transaction mvoement and append it to the Main Table
     * @return unknown
     */
    public function AddNewMovementRows()
    {
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies     = Currency::all();
        
        $data = array(
            "lst_accounts" => $lst_accounts,
            "lst_currencies" => $lst_currencies
        );
        $result_array['display'] = view("accounting.newmovementrow",$data)->render();
        
        return Response()->json($result_array);
        
    }
    
    
    public function DisplayEditMovementRow(Request $request)
    {
        $tm_id = $request->input("tm_id");
        
        $movement_info = TransactionMovements::find($tm_id);
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies     = Currency::all();
        
        $result_array = array();
        
        $data = array(
            'movement_info' => $movement_info, 
            'lst_accounts' => $lst_accounts
        );
        $result_array['display'] = view("accounting.editmovementrow",$data)->render();
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
    public function SaveMovementRowInfo(Request $request)
    {
        $tm_id                  = $request->input("tm_id");
        $at_id                  = $request->input("at_id");
        $tm_ledger_account      = $request->input("tm_ledger_account");
        $tm_sub_ledger_account  = $request->input("tm_sub_ledger_account");
        $tm_ledger_label        = $request->input("tm_ledger_label");
        $tm_debit               = $request->input("tm_debit");
        $tm_credit              = $request->input("tm_credit"); 
        $result_array           = array();
        
        $TransMovements = TransactionMovements::find($tm_id);
        $TransMovements->tm_ledger_account      = $tm_ledger_account;
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
        
        if($debit_credits['debit'] !=  $debit_credits['credit'])
        {
            $result_array['is_error']   = 1;
            $result_array['error_msg']  = "<strong>Error!</strong> Movement not correctly balanced. Debit = " .  $tm_debit . " <b>" . $TransMovements->currency->cc_currency_code. "</b> | Credit = " .  $tm_credit . "<b>" . $TransMovements->currency->cc_currency_code . "</b>";
        } 
        else 
        {
            $result_array['is_error']   = 0;
        }
        
        return Response()->json($result_array);
        
       
    }
    
    /**
     * Edit Form for Transaction and ability to add and edit movement for the current transaction
     * 
     * @author Moe Mantach
     * @access public
     * @param number $at_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm($at_id)
    {
        $transaction_info   = Transactions::find($at_id);
        $lst_trans_movement = TransactionMovements::whereFkTranId($at_id)->get();
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_journals       = AccountingJournals::whereAjIsDeleted(0)->orderBy('aj_journal_code', 'asc')->orderBy('aj_journal_label', 'asc')->get();
        $lst_currencies     = Currency::all();
        
        
        
        $data = array(
            "transaction_info" => $transaction_info,
            "lst_accounts" => $lst_accounts,
            "lst_journals" => $lst_journals,
            "lst_currencies" => $lst_currencies,
            "lst_trans_movement" => $lst_trans_movement,
        );
        
        return Response()->view("accounting.edittransactionform",$data);
    }
    
    /**
     * Edit Transaction movement form for transaction selected
     * 
     * @author Moe Mantach
     * @param Integer $tm_id
     *  @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     * 
     */
    public function EditTransactionMovement( $tm_id )
    {
        $transaction_movement = TransactionMovements::fint($tm_id);
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        
        $data = array(
            "transaction_movement" => $transaction_movement, 
            "lst_accounts" => $lst_accounts, 
        );
        
        return view("accounting.edittransactionmovform",$data);
    }
    
    
    /**
     * Delete Transaction and movememnts Related to this transaction
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteTransactionInfo(Request $request)
    {
        $at_id = $request->input("at_id");
        
        DB::table('acc_transaction_movements')->where('fk_tran_id',$at_id)->delete();
        
        $transaction = Transactions::find($at_id);
        $transaction->delete();
        
        $result_array = array();
        $result_array['is_error'] = 0;
        
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Delete Transaction and check the balance between debit and credit
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteMovementInfo(Request $request)
    {
        $at_id= $request->input("at_id");
        $tm_id = $request->input("tm_id");
        $result_array = array();
        
        
        $transactionMovement = TransactionMovements::find($tm_id);
        $transactionMovement->delete();
        
        $lst_movements      = TransactionMovements::whereFkTranId($at_id)->get();
        
        $AccountingManager = new AccountingManager();
        $debit_credits = $AccountingManager->GetTotalDebitCredits($lst_movements);
        
        $result_array['credit'] = $debit_credits['credit'];
        
        if($debit_credits['debit'] !=  $debit_credits['credit'])
        {
            $result_array['is_error']   = 1;
            $result_array['error_msg']  = "<strong>Error!</strong> Movement not correctly balanced. Debit = " .  $result_array['debit'] . " | Credit = " .  $result_array['credit'];
        }
        else
        {
            $result_array['is_error']   = 0;
        }
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Add New Transaction Form
     * 
     * @author Moe Mantach 
     * @access public
     * @return unknown
     */
    public function AddForm()
    {
        $lst_journals       = AccountingJournals::whereAjIsDeleted(0)->orderBy('aj_journal_code', 'asc')->orderBy('aj_journal_label', 'asc')->get();
        $lst_currencies     = Currency::all();
        $data = array(
            'lst_journals' => $lst_journals,
            'lst_currencies' => $lst_currencies
        );
        return Response()->view("accounting.addtransactionform",$data);
    }
    
    /**
     * Save Transaction Information to the database if in the edit mode we save the updated info for
     * movememnt in the current transaction
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveTransactionInfo(Request $request)
    {
        $at_id                  = $request->input("at_id");
        $at_transaction_date    = $request->input("at_transaction_date");
        $at_accounting_doc      = $request->input("at_accounting_doc");
        $fk_acc_journal_id      = $request->input("fk_acc_journal_id");
        $at_currency_id         = $request->input("at_currency_id");
        $result_array = array();
        
        $tm_ledger_account      = $request->input("tm_ledger_account");
        $tm_sub_ledger_account  = $request->input("tm_sub_ledger_account");
        $tm_ledger_label        = $request->input("tm_ledger_label");
        $tm_debit               = $request->input("tm_debit");
        $tm_credit              = $request->input("tm_credit");
        $tm_currency_id         = $request->input("tm_currency_id");
        
        $total_debit            = 0;
        $total_credit           = 0;
        
        $Accountingtransaction = new Transactions();
        
        if($at_id != null)
        {
            $Accountingtransaction = Transactions::find($at_id);
        }
        else 
        {
            $at_creation_date       = date("Y-m-d");
            $Accountingtransaction->at_creation_date    = $at_creation_date;
        }
        
        $Accountingtransaction->at_transaction_date     = $at_transaction_date;
        $Accountingtransaction->at_accounting_doc       = $at_accounting_doc;
        $Accountingtransaction->fk_acc_journal_id       = $fk_acc_journal_id;
        $Accountingtransaction->at_currency_id          = $at_currency_id;
        
        $Accountingtransaction->save();
        
        $fk_trans_id =  $Accountingtransaction->at_id;
        
        if($tm_ledger_account != null)
        { 
            for ($i = 0; $i < count($tm_ledger_account); $i++) 
            {
                
                if($tm_debit[$i] == 0 && $tm_credit[$i]== 0)
                    continue;
                
                $Movement_obj = new TransactionMovements();
                $Movement_obj->fk_tran_id               = $fk_trans_id;
                $Movement_obj->tm_ledger_account        = $tm_ledger_account[$i];
                $Movement_obj->tm_sub_ledger_account    = $tm_sub_ledger_account[$i];
                $Movement_obj->tm_ledger_label          = $tm_ledger_label[$i];
                $Movement_obj->tm_debit                 = $tm_debit[$i];
                $Movement_obj->tm_credit                = $tm_credit[$i];
                $Movement_obj->tm_currency_id           = $at_currency_id;
                $Movement_obj->save();
                
                $total_debit    = $total_debit  + $tm_debit[$i];
                $total_credit   = $total_credit + $tm_credit[$i];
            }
            
            
            // difference between debit and credit get error message
            if($total_debit != $total_credit)
            {
                $result_array['is_error']   = 1; 
                $result_array['error_msg']  = "<strong>Error!</strong> Movement not correctly balanced. Debit = " . $total_debit . " | Credit = " . $total_credit;
                return Response()->json($result_array);
            }
        }

        $result_array['is_error']   = 0;
        $result_array['at_id']      =  $Accountingtransaction->at_id;
        $result_array['error_msg']  = "Operation Completed Successfully";
        
        return Response()->json($result_array);
    }
    
    
 
}