<?php
/***********************************************************
DebitNotesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 30, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

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
use App\Library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\Accounting\ChartAccounts;
use App\models\System\Countries;
use App\models\Accounting\AccountCategories;
use App\models\Billing\PaymentTypes;
use App\models\Billing\PaymentVouchers;
use App\models\System\Currency;
use App\Library\AccountingManager;
use App\models\Users\Users;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\models\Billing\VoucherExtensions;
use App\models\Billing\DebitNotes;



class DebitNotesController extends Controller
{
    
    /**
     * Page to Manage Credit Notes added to the
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
        return Response()->view('billing.debitnotes',$data);
    }
    
    
    
    /**
     * Display list of Credit Notes saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $dn_account_payable         = $request->input('dn_account_payable');
        $dn_account_receivable      = $request->input('dn_account_receivable');
        $dn_start_date              = $request->input('dn_start_date');
        $dn_end_date                = $request->input('dn_end_date');
        $dn_currency_id             = $request->input('dn_currency_id');
        $page_number                = $request->input("page_number");
        $nbr_rows_per_pages         = Config::get('appconfig.max_rows_per_page');
        
        if($page_number > 1)
           $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
           $skip = 0;
                
                
                
        $lst_debit_notes       = DebitNotes::whereDnIsDeleted(0);
        
        // filter items
        if($dn_account_payable > 0)
            $lst_debit_notes= $lst_debit_notes->where('dn_account_sender',$dn_account_payable);
        if($dn_account_receivable> 0)
            $lst_debit_notes= $lst_debit_notes->where('dn_account_receivable',$dn_account_receivable);
        if(strlen($dn_start_date) > 0)
            $lst_debit_notes= $lst_debit_notes->where('dn_creation_date','>=',$dn_start_date);
        if(strlen($dn_end_date) > 0)
            $lst_debit_notes= $lst_debit_notes->where('dn_creation_date','<',$dn_end_date);
            
        $dn_count =     $lst_debit_notes->count();
        $total_pages = ceil( $dn_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $lst_debit_notes   = $lst_debit_notes->skip($skip)->take($nbr_rows_per_pages)->get();
        
        
        $lst_currency           = Currency::all();
        $currency_array         = CreateDatabaseArrayByIndex($lst_currency,"cc_id");
        
        
        $data = array( 
            "lst_debit_notes" => $lst_debit_notes,
            "currency_array" => $currency_array
        );
        
        $result_array = array();
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("billing.listDebitNotes",$data)->render();
        
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
        $lst_currencies     = Currency::all();
        $account_management = new AccountingManager();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $accounting_obj     = new AccountingManager();
        $debit_code = $accounting_obj->GenerateDNCode();
        
        $data = array(
            'lst_chart_accounts' => $lst_accounts,
            'lst_users' => $lst_users,
            'dn_code' => $debit_code,
            "lst_currencies" => $lst_currencies
        );
        
        return Response()->view('billing.adddebitnote',$data);
    }
    
    
    
    
    /**
     * get information of selected Account and  and open the edit form fields
     * @param unknown $w_id
     * @return \Illuminate\Http\Response
     */
    public function EditForm( $dn_id )
    {
        
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies     = Currency::all();
        $account_management = new AccountingManager();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $dn_info = DebitNotes::find($dn_id);
        
        $data = array(
            'lst_chart_accounts' => $lst_accounts,
            'lst_users' => $lst_users,
            'dn_info' => $dn_info,
            "lst_currencies" => $lst_currencies
        );
        
        return Response()->view('billing.editdebitnote',$data);
        
    }
    
    
    
    
    /**
     * Save information of new payment voucher and
     * add a transaction and movement records in the banks
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Json $result_array
     */
    public function SaveDebitNote(Request $request)
    {
        $dn_id                      = $request->input('dn_id');
        $dn_code                    = $request->input('dn_code');
        $dn_account_sender          = $request->input('dn_account_sender');
        $dn_account_receivable      = $request->input('dn_account_receivable');
        //$fk_trans_id                = $request->input('fk_trans_id');
        //$fk_mov_id                  = $request->input('fk_mov_id');
        $dn_creation_date           = $request->input('dn_creation_date');
        $dn_creation_date           = date("Y-m-d",strtotime($dn_creation_date));
        $dn_created_by              = $request->input('dn_created_by');
        $dn_debit_label            = $request->input('dn_debit_label');
        $dn_debit_notes            = $request->input('dn_debit_notes');
        $dn_debit_value            = $request->input('dn_debit_value');
        $dn_debit_currency         = $request->input('dn_debit_currency');
        $dn_second_currency         = $request->input('dn_second_currency');
        $dn_exchange_rate           = $request->input('dn_exchange_rate');
        $debit_notes = new DebitNotes();
        $is_new = true;
        if( $dn_id  > 0 )
        {
            $debit_notes       = DebitNotes::find( $dn_id);
            $is_new = false;
        }
        
        $debit_notes->dn_created_by                = $dn_created_by;
        $debit_notes->dn_account_sender            = $dn_account_sender;
        $debit_notes->dn_account_receivable        = $dn_account_receivable;
        $debit_notes->dn_debit_date                 = $dn_creation_date;
        $debit_notes->dn_debit_label              = $dn_debit_label;
        $debit_notes->dn_debit_notes              = $dn_debit_notes;
        $debit_notes->dn_debit_value              = $dn_debit_value;
        $debit_notes->dn_debit_currency           = $dn_debit_currency;
        $debit_notes->dn_second_currency           = $dn_second_currency;
        $debit_notes->dn_exchange_rate             = $dn_exchange_rate;
        $debit_notes->dn_code                       = $dn_code;
        $debit_notes->save();
        
        $dn_id = $debit_notes->dn_id;
        
        {
            
            // Delete Old Transaction and movment
            $trans_id = $debit_notes->fk_trans_id;
            if( $trans_id > 0 )
            {
                $delete_trans = Transactions::where('at_id',$trans_id)->delete();
                $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();
                
            }
            
            // add transaction record
            $AccTransaction = new Transactions();
            $AccTransaction->at_transaction_date    = $dn_creation_date;
            $AccTransaction->at_creation_date       = date("Y-m-d");
            $AccTransaction->at_accounting_doc      = $dn_debit_label;
            $AccTransaction->fk_acc_journal_id      = 3;
            $AccTransaction->save();
            $at_id = $AccTransaction->at_id;
            
            $org_payment_amount = $dn_debit_value;
            $payment_currency   = $dn_debit_currency;
            $payment_amount     = $org_payment_amount;
            if(strlen($dn_second_currency) > 0)
            {
                $payment_amount    = $org_payment_amount * math_eval($dn_exchange_rate);
                $payment_currency  = $dn_second_currency;
            }
            
            // add debit record to the transaction
            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $dn_account_sender;
            $TransactionMovement->tm_sub_ledger_account = $dn_account_receivable;
            $TransactionMovement->tm_ledger_label       = $dn_debit_label;
            $TransactionMovement->tm_debit              = $payment_amount;
            $TransactionMovement->tm_credit             = 0;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_transaction_date   = $dn_creation_date;
            $TransactionMovement->tm_currency_id        = $payment_currency;
            $TransactionMovement->save();
            
            
        }
        
        $debit_notes = DebitNotes::find( $dn_id);
        $debit_notes->fk_trans_id= $at_id;
        $debit_notes->save();
        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
        
    }
    
    
    
    
    /**
     * Delete Credit Notes info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteDebitNoteInfo(Request $request)
    {
        $dn_id  = $request->input('dn_id');
        $result_array = array();
        
        
        
        $debit_notes= DebitNotes::find($dn_id);
        $debit_notes->dn_is_deleted = 1;
        $debit_notes->dn_deleted_by = session('user_id');
        $debit_notes->save();
        
        $trans_id = $debit_notes->dn_transaction_id;
        
        $delete_trans = Transactions::where('at_id',$trans_id)->delete();
        $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();
        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
    
    
    
}