<?php
/***********************************************************
InternalTransfersController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 6, 2021
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
use App\models\Billing\InternalTransfers;



class InternalTransfersController extends Controller
{

    /**
     * Page to Manage Internal Transfers added to the
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
        return Response()->view('billing.internaltransfers',$data);
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
        $in_account_payable         = $request->input('in_account_payable');
        $in_account_receivable      = $request->input('in_account_receivable');
        $in_start_date              = $request->input('in_start_date');
        $in_start_date              = date("Y-m-d",strtotime($in_start_date));
        $in_end_date                = $request->input('in_end_date');
        $in_end_date                = date("Y-m-d",strtotime($in_end_date));
        $in_currency_id             = $request->input('in_currency_id');
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



        $lst_internal_notes       = InternalTransfers::whereInIsDeleted(0);

        // filter items
        if($in_account_payable > 0)
            $lst_internal_notes= $lst_internal_notes->where('in_account_sender',$in_account_payable);
        if($in_account_receivable> 0)
            $lst_internal_notes= $lst_internal_notes->where('in_account_receivable',$in_account_receivable);
        if(strlen($in_start_date) > 0)
            $lst_internal_notes= $lst_internal_notes->where('in_transfer_date','>=',$in_start_date);
        if(strlen($in_end_date) > 0)
            $lst_internal_notes= $lst_internal_notes->where('in_transfer_date','<',$in_end_date);


            if(strlen($in_start_date) ==  0 && strlen($in_end_date) ==  0)
            {
                if($firstday != "" || $lastday != "")
                    $lst_internal_notes= $lst_internal_notes->whereBetween('in_transfer_date', [$firstday, $lastday]);
            }

        $in_count =     $lst_internal_notes->count();
        $total_pages = ceil( $in_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_internal_notes = $lst_internal_notes->skip($skip)->take($nbr_rows_per_pages)->get();


        $lst_currency           = Currency::all();
        $currency_array         = CreateDatabaseArrayByIndex($lst_currency,"cc_id");


        $data = array(
            "lst_internal_notes" => $lst_internal_notes,
            "currency_array" => $currency_array
        );

        $result_array = array();
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("billing.listinternalnotes",$data)->render();

        return Response()->json($result_array);
    }



    /**
     * Open form of add new Internal Transfer
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
        $internal_code = $account_management->GenerateINCode();

        $data = array(
            'lst_chart_accounts' => $lst_accounts,
            'lst_users' => $lst_users,
            'internal_code' => $internal_code,
            "lst_currencies" => $lst_currencies
        );

        return Response()->view('billing.addinternaltransfer',$data);
    }




    /**
     * Edit Form Internal Transfer Code
     * @param unknown $w_id
     * @return \Illuminate\Http\Response
     */
    public function EditForm( $in_id )
    {

        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies     = Currency::all();
        $account_management = new AccountingManager();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $in_info = InternalTransfers::find($in_id);

        $data = array(
            'lst_chart_accounts' => $lst_accounts,
            'lst_users' => $lst_users,
            'in_info' => $in_info,
            "lst_currencies" => $lst_currencies
        );

        return Response()->view('billing.editinternaltransfer',$data);

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
    public function SaveINInfo(Request $request)
    {
        $in_id                      = $request->input('in_id');
        $in_code                    = $request->input('transfer_code');
        $in_account_sender          = $request->input('in_account_sender');
        $in_account_receivable      = $request->input('in_account_receivable');
        $in_transfer_date           = $request->input('in_transfer_date');
        $in_transfer_date           = date("Y-m-d",strtotime($in_transfer_date));
        $in_created_by              = $request->input('in_created_by');
        $in_transfert_label         = $request->input('in_transfert_label');
        $in_transfer_notes          = $request->input('in_transfer_notes');
        $in_credit_value            = $request->input('in_credit_value');
        $in_debit_value             = $request->input('in_debit_value');
        $in_credit_currency         = $request->input('in_credit_currency');
        $in_second_currency         = $request->input('in_second_currency');
        $in_exchange_rate           = $request->input('in_exchange_rate');
        $internal_notes = new InternalTransfers();
        $result_array = array();
        if( $in_id> 0 )
        {
            $internal_notes         = InternalTransfers::find( $in_id);
        }

        $internal_notes->in_transfer_code       = $in_code;
        $internal_notes->in_account_sender      = $in_account_sender;
        $internal_notes->in_account_receivable  = $in_account_receivable;
        $internal_notes->in_transfer_date       = $in_transfer_date;
        $internal_notes->in_created_by          = $in_created_by;
        $internal_notes->in_transfert_label     = $in_transfert_label;
        $internal_notes->in_transfer_notes      = $in_transfer_notes;
        $internal_notes->in_credit_value        = $in_credit_value;
        $internal_notes->in_debit_value         = $in_debit_value;
        $internal_notes->in_credit_currency     = $in_credit_currency;
        $internal_notes->in_second_currency     = $in_second_currency;
        $internal_notes->in_exchange_rate       = $in_exchange_rate;
        $internal_notes->save();
        $in_id = $internal_notes->in_id;

        {

            // get internal transfer account info 580
            $internal_account = ChartAccounts::where('aa_account', 'LIKE', "%58%")->get();
            if(count($internal_account) == 0)
            {
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = "No middle Account";
                return Response()->json($result_array);
            }

            $int_account_id = $internal_account[0]->aa_id;

            // Delete Old Transaction and movment
            $trans_id = $internal_notes->fk_trans_id;
            if( $trans_id > 0 )
            {
                $delete_trans = Transactions::where('at_id',$trans_id)->delete();
                $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();

            }

            // add transaction record
            $AccTransaction = new Transactions();
            $AccTransaction->at_transaction_date    = $in_transfer_date;
            $AccTransaction->at_creation_date       = date("Y-m-d");
            $AccTransaction->at_accounting_doc      = $in_transfert_label;
            $AccTransaction->fk_acc_journal_id      = 3;
            $AccTransaction->save();
            $at_id = $AccTransaction->at_id;


            if($in_credit_value > 0)
            {
                // add debit record to the transaction
                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_ledger_account     = $in_account_sender;
                $TransactionMovement->tm_sub_ledger_account = $in_account_sender;
                $TransactionMovement->tm_ledger_label       = $in_transfert_label;
                $TransactionMovement->tm_debit              = 0;
                $TransactionMovement->tm_credit             = $in_credit_value;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_transaction_date   = $in_transfer_date;
                $TransactionMovement->tm_currency_id        = $in_credit_currency;
                $TransactionMovement->save();

                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_ledger_account     = $in_account_sender;
                $TransactionMovement->tm_sub_ledger_account = $int_account_id;
                $TransactionMovement->tm_ledger_label       = $in_transfert_label;
                $TransactionMovement->tm_debit              = $in_credit_value;
                $TransactionMovement->tm_credit             = 0;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_transaction_date   = $in_transfer_date;
                $TransactionMovement->tm_currency_id        = $in_credit_currency;
                $TransactionMovement->save();


                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_ledger_account     = $int_account_id;
                $TransactionMovement->tm_sub_ledger_account = $in_account_receivable;
                $TransactionMovement->tm_ledger_label       = $in_transfert_label;
                $TransactionMovement->tm_debit              = $in_credit_value;
                $TransactionMovement->tm_credit             = 0;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_transaction_date   = $in_transfer_date;
                $TransactionMovement->tm_currency_id        = $in_credit_currency;
                $TransactionMovement->save();


                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_ledger_account     = $int_account_id;
                $TransactionMovement->tm_sub_ledger_account = $int_account_id;
                $TransactionMovement->tm_ledger_label       = $in_transfert_label;
                $TransactionMovement->tm_debit              = 0;
                $TransactionMovement->tm_credit             = $in_credit_value;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_transaction_date   = $in_transfer_date;
                $TransactionMovement->tm_currency_id        = $in_credit_currency;
                $TransactionMovement->save();

            }
            elseif ($in_debit_value > 0)
            {
                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_ledger_account     = $in_account_sender;
                $TransactionMovement->tm_sub_ledger_account = $int_account_id;
                $TransactionMovement->tm_ledger_label       = $in_transfert_label;
                $TransactionMovement->tm_debit              = 0;
                $TransactionMovement->tm_credit             = $in_debit_value;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_transaction_date   = $in_transfer_date;
                $TransactionMovement->tm_currency_id        = $in_credit_currency;
                $TransactionMovement->save();

                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_ledger_account     = $in_account_sender;
                $TransactionMovement->tm_sub_ledger_account = $in_account_sender;
                $TransactionMovement->tm_ledger_label       = $in_transfert_label;
                $TransactionMovement->tm_debit              = $in_debit_value;
                $TransactionMovement->tm_credit             = 0;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_transaction_date   = $in_transfer_date;
                $TransactionMovement->tm_currency_id        = $in_credit_currency;
                $TransactionMovement->save();

                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_ledger_account     = $int_account_id;
                $TransactionMovement->tm_sub_ledger_account = $int_account_id;
                $TransactionMovement->tm_ledger_label       = $in_transfert_label;
                $TransactionMovement->tm_debit              = $in_debit_value;
                $TransactionMovement->tm_credit             = 0;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_transaction_date   = $in_transfer_date;
                $TransactionMovement->tm_currency_id        = $in_credit_currency;
                $TransactionMovement->save();

                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_ledger_account     = $int_account_id;
                $TransactionMovement->tm_sub_ledger_account = $in_account_receivable;
                $TransactionMovement->tm_ledger_label       = $in_transfert_label;
                $TransactionMovement->tm_debit              = 0;
                $TransactionMovement->tm_credit             = $in_debit_value;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_transaction_date   = $in_transfer_date;
                $TransactionMovement->tm_currency_id        = $in_credit_currency;
                $TransactionMovement->save();
            }





        }

        $internal_notes= InternalTransfers::find( $in_id);
        $internal_notes->fk_trans_id =  $at_id;
        $internal_notes->save();


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);

    }




    /**
     * Delete Internal Notes info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteINInfo(Request $request)
    {
        $in_id  = $request->input('in_id');
        $result_array = array();



        $internal_notes= InternalTransfers::find($in_id);
        $internal_notes->in_is_deleted = 1;
        $internal_notes->in_deleted_by = session('user_id');
        $internal_notes->save();

        $trans_id = $internal_notes->fk_trans_id;

        $delete_trans = Transactions::where('at_id',$trans_id)->delete();
        $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }




}
