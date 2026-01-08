<?php
/***********************************************************
 PaymentVouchersController.php
 Product :
 Version : 1.0
 Release : 1
 Date Created : Mar 21, 2020
 Developed By  : Mohamad Mantach   PHP Department itm Solutions
 All Rights Reserved ,   itm Solutions COPYRIGHT 2020

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
use App\models\System\Companies;
use Dompdf\Dompdf;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;


class PaymentVouchersController extends Controller
{

    /**
     * Page to Manage Payment vouchers added to the
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
        $default_company_id = session('default_company_id');

        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->whereFkCompanyId($default_company_id)->get();
        $data = array(
            "lst_chart_accounts" => $lst_chart_accounts,
            "lst_users" => $lst_users,
            "lst_currencies" => $lst_currencies,
        );
        //billing_pv_one_page

        $billing_pv_one_page = Config::get('appconfig.billing_pv_one_page');

        if($billing_pv_one_page == 0)
            return Response()->view('billing.paymentvouchers',$data);
        else
            return Response()->view('billing.pvmanagement',$data);
    }



    /**
     * Display list of Payment Vouchers saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $pv_account_payable         = $request->input('pv_account_payable');
        $pv_account_receivable      = $request->input('pv_account_receivable');
        $pv_start_date              = $request->input('pv_start_date');
        $pv_end_date                = $request->input('pv_end_date');
        $page_number                = $request->input("page_number");
        $general_search             = $request->input("general_search");
        $default_company_id = session('default_company_id');
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


        $voucher_cond   = PaymentVouchers::wherePvIsDeleted(0)->wherePvCompanyId($default_company_id);

        // filter items
        if($pv_account_payable  > 0)
            $voucher_cond= $voucher_cond->wherePvAccountPayable($pv_account_payable);
        if($pv_account_receivable > 0)
            $voucher_cond= $voucher_cond->wherePvAccountReceivable($pv_account_receivable);
        if(strlen($pv_start_date) > 0)
            $voucher_cond= $voucher_cond->where('pv_creation_date','>=',$pv_start_date);
        if(strlen($pv_end_date) > 0)
            $voucher_cond= $voucher_cond->where('pv_creation_date','<',$pv_end_date);

        if(strlen($pv_start_date) ==  0 && strlen($pv_end_date) ==  0)
        {
            if($firstday != "" ||   $lastday != "")
                $voucher_cond= $voucher_cond->whereBetween('pv_creation_date', [$firstday, $lastday]);
        }

        if(strlen($general_search) > 0)
        {
            $voucher_cond= $voucher_cond->where('pv_code','LIKE','%' . $general_search. '%');
            $voucher_cond= $voucher_cond->orWhere('pv_voucher_label','LIKE','%' . $general_search. '%');
            $voucher_cond= $voucher_cond->orWhere('pv_voucher_description','LIKE','%' . $general_search. '%');
        }


        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;

        $count_vouchers =     $voucher_cond->count();
        $total_pages = ceil( $count_vouchers/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_vouchers= $voucher_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('pv_creation_date','DESC')->get();


        //->orderBy('pv_id', 'asc')->get()
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts , "aa_id");

        $lst_currencies = Currency::all();
        $currency_array =  CreateDatabaseArrayByIndex($lst_currencies, "cc_id");
        $data = array(
            "lst_vouchers" => $lst_vouchers,
            "total_pages" => $total_pages,
            "accounts_array" => $accounts_array,
            "currency_array" => $currency_array
        );

        $result_array = array();

        $result_array['display'] = view("billing.listvouchers",$data)->render();
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }


    public static function numberToWords($number) {
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = [
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'forty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion',
        1000000000000 => 'trillion'
    ];

    if (!is_numeric($number)) {
        return false;
    }

    if ($number < 0) {
        return $negative . self::numberToWords(abs($number));
    }

    $string = '';

    if ($number < 21) {
        $string = $dictionary[$number];
    } elseif ($number < 100) {
        $tens = ((int) ($number / 10)) * 10;
        $units = $number % 10;
        $string = $dictionary[$tens];
        if ($units) {
            $string .= $hyphen . $dictionary[$units];
        }
    } elseif ($number < 1000) {
        $hundreds = (int) ($number / 100);
        $remainder = $number % 100;
        $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
        if ($remainder) {
            $string .= $conjunction . self::numberToWords($remainder);
        }
    } else {
        foreach ([1000, 1000000, 1000000000, 1000000000000] as $unit) {
            if ($number < $unit * 1000) {
                $baseUnit = (int) ($number / $unit);
                $remainder = $number % $unit;
                $string = self::numberToWords($baseUnit) . ' ' . $dictionary[$unit];
                if ($remainder) {
                    $string .= $separator . self::numberToWords($remainder);
                }
                break;
            }
        }
    }

    return $string;
}


    public function DownloadPaymentVoucher($pv_id)
    {
        $voucher_info = PaymentVouchers::find( $pv_id );
        $br_company_id  = Session('company_id');

        $company_info = Companies::find($br_company_id);

        $lst_currencies = Currency::all();
        $currencies_array = CreateDatabaseArrayByIndex($lst_currencies, "cc_id");


        $display = view("templates.pvoucher",array())->render();


        $display = str_replace("%company_name%",$company_info->cd_company_name, $display);
        $display = str_replace("%company_name_translation%",$company_info->cd_company_name_translation, $display);
        $display = str_replace("%account_to%",$voucher_info->AccountReceivable->aa_account_ref, $display);
        $display = str_replace("%account_ledger_to%",$voucher_info->AccountReceivable->aa_account_label, $display);
        $display = str_replace("%voucher_from%",$voucher_info->AccountPayable->aa_account_label, $display);
        $display = str_replace("%company_address%",$company_info->cd_company_address, $display);
        $display = str_replace("%company_phone%",$company_info->cd_company_phone, $display);
        $display = str_replace("%voucher_code%",$voucher_info->pv_code, $display);
        $display = str_replace("%payment_date%",$voucher_info->pv_creation_date, $display);
        $display = str_replace("%voucher_description%",$voucher_info->pv_voucher_label, $display);
        $display = str_replace("%voucher_amount%",$voucher_info->pv_payment_amount, $display);
        $display = str_replace("%voucher_amount_letters%",self::numberToWords($voucher_info->pv_payment_amount), $display);
        $display = str_replace("%voucher_currency%",$voucher_info->currency->cc_currency_code, $display);
        $display = str_replace("%CREATED_BY%",$voucher_info->User->u_fullname, $display);
        $display = str_replace("%PRINTED_BY%",Session('user_fullname'), $display);
        $display = str_replace("%PRINT_DATE%",date('d-m-Y H:i:s'), $display);


        return PDF::loadHTML($display)
                 ->setPaper('a4')->setOption('encoding', 'UTF-8')
                 ->download("voucher-" . strtolower($voucher_info->pv_code) . ".pdf");
    }

    public function DisplayListOnepage(Request $request)
    {
        $pv_account_payable         = $request->input('account_payable');
        $pv_account_receivable      = $request->input('account_receivable');
        $default_company_id = session('default_company_id');
        $pv_start_date              = $request->input('start_date');
        $pv_end_date                = $request->input('end_date');
        $page_number                = $request->input("page_number");
        $general_search             = $request->input("general_search");
        $fisical_year =  $request->input('fisical_year')  !== null ? $request->input('fisical_year') : date("Y");
        $nbr_rows_per_pages         = Config::get('appconfig.max_rows_per_page');

        $strfirstday = 'first day of January ' . $fisical_year;
        $strlastday = 'last day of December ' . $fisical_year;

        $firstday = date("Y-m-d",strtotime($strfirstday));
        $lastday = date("Y-m-d",strtotime($strlastday));


        $voucher_cond       = PaymentVouchers::wherePvIsDeleted(0)->wherePvCompanyId($default_company_id);

        // filter items
        if($pv_account_payable  > 0)
            $voucher_cond= $voucher_cond->wherePvAccountPayable($pv_account_payable);
        if($pv_account_receivable > 0)
            $voucher_cond= $voucher_cond->wherePvAccountReceivable($pv_account_receivable);
        if(strlen($pv_start_date) > 0)
            $voucher_cond= $voucher_cond->where('pv_creation_date','>=',$pv_start_date);
        if(strlen($pv_end_date) > 0)
            $voucher_cond= $voucher_cond->where('pv_creation_date','<',$pv_end_date);

        if(strlen($pv_start_date) ==  0 && strlen($pv_end_date) ==  0)
        {
            $voucher_cond= $voucher_cond->whereBetween('pv_creation_date', [$firstday, $lastday]);
        }

        if(strlen($general_search) > 0)
        {
            $voucher_cond= $voucher_cond->where('pv_code','LIKE','%' . $general_search. '%');
            $voucher_cond= $voucher_cond->orWhere('pv_voucher_label','LIKE','%' . $general_search. '%');
            $voucher_cond= $voucher_cond->orWhere('pv_voucher_description','LIKE','%' . $general_search. '%');
        }


        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;

        $count_vouchers =     $voucher_cond->count();
        $total_pages = ceil( $count_vouchers/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_vouchers= $voucher_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('pv_creation_date','ASC')->get();


        //->orderBy('pv_id', 'asc')->get()
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts , "aa_id");

        $lst_currencies = Currency::all();
        $currency_array =  CreateDatabaseArrayByIndex($lst_currencies, "cc_id");
        $data = array(
            "lst_vouchers" => $lst_vouchers,
            "total_pages" => $total_pages,
            "accounts_array" => $accounts_array,
            "currency_array" => $currency_array
        );

        $result_array = array();

        $result_array['display'] = view("billing.listpagervouchers",$data)->render();
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }


    /**
     * Get Information for selected voucher
     *
     * @author Moe Mantach
     * @param Request $request
     */
    public function GetSelectedVoucher(Request $request)
    {
        $pv_id = $request->input('pv_id');

        $result_array = array();
        $voucher_info = PaymentVouchers::find($pv_id);

        $result_array['is_error'] = 0;
        $result_array['voucher_obj'] = array(
            'pv_id' => $voucher_info->pv_id,
            'pv_code' => $voucher_info->pv_code,
            'pv_voucher_label' => $voucher_info->pv_voucher_label,
            'pv_creation_date' => $voucher_info->pv_creation_date,
            'pv_account_payable' => $voucher_info->pv_account_payable,
            'pv_account_receivable' => $voucher_info->pv_account_receivable,
            'pv_payment_amount' => $voucher_info->pv_payment_amount,
            'pv_currency_id' => $voucher_info->pv_currency_id,
            'pv_sec_currency_id' => $voucher_info->pv_sec_currency_id,
            'pv_exchange_rate' => $voucher_info->pv_exchange_rate,
            'pv_amount_secondary_amount' => $voucher_info->pv_amount_secondary_amount,
            'pv_voucher_description' => $voucher_info->pv_voucher_description,
        );


        return Response()->json($result_array);

    }


    public function ViewExtensionRow(Request $request)
    {
        $ve_id = $request->input('ve_id');

        $voucher_extension = VoucherExtensions::find($ve_id);
        $result_array = array();
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies     = Currency::all();


        $data= array(
            "voucher_extension" => $voucher_extension,
            "lst_chart_accounts" => $lst_accounts,
            "lst_currencies" => $lst_currencies
        );
        $result_array['display'] = view("billing.viewextensionrow",$data)->render();

        return Response()->json($result_array);
    }

    /**
     * Generate Voucher Code
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return type
     */
    public function GenerateVoucherCode(Request $request)
    {
         $account_management = new AccountingManager();
        $voucher_code       = $account_management->GetVoucherCode();
        $result_array = array();



        $result_array['is_error'] = 0;
        $result_array['voucher_code'] = $voucher_code;
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
        $voucher_code       = $account_management->GetVoucherCode();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();


        $data = array(
            'lst_chart_accounts' => $lst_accounts,
            'lst_users' => $lst_users,
            "voucher_code" => $voucher_code,
            "lst_currencies" => $lst_currencies
        );

        return Response()->view('billing.addvoucherform',$data);
    }


    /**
     * Save Extension Row of payment voucher
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveExtensionRow(Request $request)
    {
        $ve_id                  = $request->input('ve_id');
        $ve_extention_account_id= $request->input('ve_extention_account_id');
        $ve_extension_currency  = $request->input('ve_extension_currency');
        $ve_extension_amount    = $request->input('ve_extension_amount');
        $ve_extension_notes     = $request->input('ve_extension_notes');
        $result_array           = array();

        $voucher_extension = VoucherExtensions::find($ve_id);
        $voucher_extension->ve_extention_account_id = $ve_extention_account_id;
        $voucher_extension->ve_extension_currency   = $ve_extension_currency;
        $voucher_extension->ve_extension_amount     = $ve_extension_amount;
        $voucher_extension->ve_extension_notes      = $ve_extension_notes;
        $voucher_extension->save();


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }

    /**
     * get information of selected Account and  and open the edit form fields
     * @param unknown $w_id
     * @return \Illuminate\Http\Response
     */
    public function EditForm( $pv_id )
    {
        $payment_vouchers   = PaymentVouchers::find( $pv_id );
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies     = Currency::all();
        $default_company_id = session('default_company_id');
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->whereFkCompanyId($default_company_id)->get();

        $data = array(
            'payment_vouchers' => $payment_vouchers,
            'lst_chart_accounts' => $lst_accounts,
            'lst_users' => $lst_users,
            "lst_currencies" => $lst_currencies
        );

        return Response()->view('billing.editvoucherform',$data);

    }


    /**
     * Display new Extension Row in the voucher Page
     * @param Request $request
     */
    public function DisplayNewExtensionRow(Request $request)
    {
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies     = Currency::all();

        $result_array = array();

        $display = array(
            "lst_chart_accounts" => $lst_accounts,
            "lst_currencies" => $lst_currencies
        );
        $result_array['display'] = view('billing.extensionrow',$display)->render();

        return Response()->json($result_array);
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
    public function SavePaymentVoucherInfo(Request $request)
    {
        $pv_id                      = $request->input('pv_id');
        $pv_user_id                 = $request->input('pv_user_id');
        $pv_code                    = $request->input('pv_code');
        $pv_voucher_label           = $request->input('pv_voucher_label');
        $pv_voucher_description     = $request->input('pv_voucher_description');
        $pv_account_payable         = $request->input('pv_account_payable');
        $pv_account_receivable      = $request->input('pv_account_receivable');
        $pv_creation_date           = $request->input('pv_creation_date');
        $pv_amount_secondary_amount           = $request->input('pv_amount_secondary_amount');
        $pv_creation_date           = date("Y-m-d",strtotime($pv_creation_date));
        $default_company_id = session('default_company_id');

        $pv_payment_amount          = $request->input('pv_payment_amount');
        $pv_currency_id             = $request->input('pv_currency_id');
        $pv_sec_currency_id         = $request->input('pv_sec_currency_id');
        $pv_exchange_rate           = $request->input('pv_exchange_rate');

        $pv_extension_account       = $request->input('pv_extension_account');
        $pv_extension_value         = $request->input('pv_extension_value');
        $pv_ext_currency_id         = $request->input('pv_ext_currency_id');
        $pv_extension_notes         = $request->input('pv_extension_notes');

        $payment_vouchers = new PaymentVouchers();
        $is_new = true;
        if( $pv_id > 0 )
        {
            $payment_vouchers= PaymentVouchers::find( $pv_id);
            $is_new = false;
        }

        $payment_vouchers->pv_user_id   = $pv_user_id;

        $payment_vouchers->pv_voucher_label         = $pv_voucher_label;
        $payment_vouchers->pv_voucher_description   = $pv_voucher_description;
        $payment_vouchers->pv_account_payable       = $pv_account_payable;
        $payment_vouchers->pv_account_receivable    = $pv_account_receivable;
        $payment_vouchers->pv_creation_date         = $pv_creation_date;
        $payment_vouchers->pv_sec_currency_id       = $pv_sec_currency_id;
        $payment_vouchers->pv_exchange_rate         = $pv_exchange_rate;
        $payment_vouchers->pv_amount_secondary_amount   = $pv_amount_secondary_amount;
        $payment_vouchers->pv_company_id   = $default_company_id;



        {

            // Delete Old Transaction and movment
            $trans_id = $payment_vouchers->pv_transaction_id;
            if( $trans_id > 0 )
            {
                $delete_trans = Transactions::where('at_id',$trans_id)->delete();
                $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();

            }

            $payment_vouchers->pv_code      = $pv_code;

            // add transaction record
            $AccTransaction = new Transactions();
            $AccTransaction->at_transaction_date    = $pv_creation_date;
            $AccTransaction->at_creation_date       = date("Y-m-d");
            $AccTransaction->at_accounting_doc      = $pv_code;
            $AccTransaction->fk_acc_journal_id      = 3;
            $AccTransaction->save();
            $at_id = $AccTransaction->at_id;

            $org_payment_amount = $pv_payment_amount;
            $payment_currency = $pv_currency_id;
            $payment_amount = $org_payment_amount;


            // add debit record to the transaction
            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_trans_code         = $pv_code;
            $TransactionMovement->tm_ledger_account     = $pv_account_payable;
            $TransactionMovement->tm_sub_ledger_account = $pv_account_payable;
            $TransactionMovement->tm_ledger_label       = $pv_voucher_label;
            $TransactionMovement->tm_trans_code         = $pv_code;
            $TransactionMovement->tm_debit              = 0;
            $TransactionMovement->tm_credit             = $payment_amount;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_transaction_date   = $pv_creation_date;
            $TransactionMovement->tm_currency_id        = $payment_currency;
            $TransactionMovement->tm_company_id            = $default_company_id;
            $TransactionMovement->save();


        }


        $payment_vouchers->pv_transaction_id    = $at_id;
        $payment_vouchers->pv_payment_amount        = $pv_payment_amount;
        $payment_vouchers->pv_currency_id           = $pv_currency_id;


        $payment_vouchers->save();

        $pv_id = $payment_vouchers->pv_id;


        $pv_extension_account       = $request->input('pv_extension_account');
        $pv_extension_value         = $request->input('pv_extension_value');
        $pv_ext_currency_id         = $request->input('pv_ext_currency_id');
        $pv_extension_notes         = $request->input('pv_extension_notes');

        $extra_amount = 0;
        if(is_array($pv_extension_account))
        {
            for ($i = 0; $i < count($pv_extension_account); $i++) {
                $voucheer_extension = new VoucherExtensions();
                $voucheer_extension->fk_voucher_id = $pv_id;
                $voucheer_extension->ve_extention_account_id    = $pv_extension_account[$i];
                $voucheer_extension->ve_extension_currency      = $pv_ext_currency_id[$i];
                $voucheer_extension->ve_extension_amount        = $pv_extension_value[$i];
                $voucheer_extension->ve_extension_notes         = $pv_extension_notes[$i];
                $voucheer_extension->save();

                // add extension value to total

                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_company_id            = $default_company_id;
                $TransactionMovement->tm_trans_code         = $pv_code;
                $TransactionMovement->tm_ledger_account     = $pv_account_payable;
                $TransactionMovement->tm_sub_ledger_account = $pv_extension_account[$i];
                $TransactionMovement->tm_ledger_label       = $pv_voucher_label;
                $TransactionMovement->tm_debit              =  $pv_extension_value[$i];
                $TransactionMovement->tm_credit             =0;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_transaction_date   = $pv_creation_date;
                $TransactionMovement->tm_currency_id        = $pv_ext_currency_id[$i];
                $TransactionMovement->save();

                $extra_amount = $extra_amount + floatval( $pv_extension_value[$i] );

            }
        }
        else
        {
            $lst_extension_rows = VoucherExtensions::whereFkVoucherId($pv_id)->get();

            foreach ($lst_extension_rows as $key => $extension){

                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_trans_code   = $pv_code;
                $TransactionMovement->tm_ledger_account     = $pv_account_payable;
                $TransactionMovement->tm_company_id            = $default_company_id;
                $TransactionMovement->tm_sub_ledger_account =  $extension->ve_extention_account_id;
                $TransactionMovement->tm_ledger_label       =  $extension->ve_extension_notes;
                $TransactionMovement->tm_debit              =   $extension->ve_extension_amount;
                $TransactionMovement->tm_credit             =0;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_transaction_date   = $pv_creation_date;
                $TransactionMovement->tm_currency_id        = $extension->ve_extension_currency;
                $TransactionMovement->save();

                $extra_amount = $extra_amount + floatval($extension->ve_extension_amount);

            }

        }


        $org_payment_amount = $pv_payment_amount + $extra_amount;
        $payment_currency = $pv_currency_id;
        $payment_amount = $org_payment_amount;


        $TransactionMovement = new TransactionMovements();
        $TransactionMovement->fk_tran_id            = $at_id;
        $TransactionMovement->tm_trans_code         = $pv_code;
        $TransactionMovement->tm_ledger_account     = $pv_account_receivable;
        $TransactionMovement->tm_sub_ledger_account = $pv_account_receivable;
        $TransactionMovement->tm_company_id            = $default_company_id;
        $TransactionMovement->tm_ledger_label       = $pv_voucher_label;
        $TransactionMovement->tm_debit              = $payment_amount;
        $TransactionMovement->tm_credit             = 0;
        $TransactionMovement->tm_creation_date      = date("Y-m-d");
        $TransactionMovement->tm_transaction_date   = $pv_creation_date;
        $TransactionMovement->tm_currency_id        = $payment_currency;
        $TransactionMovement->save();


        $voucher_info = PaymentVouchers::find($pv_id);
        $voucher_info->pv_extra_amount  = $extra_amount;
        $voucher_info->save();
        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);

    }


    /**
     * Display list of extensions saved in the currenct voucher
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayListExtensions(Request $request)
    {
        $pv_id = $request->input('pv_id');
        $list_voucher_extensions = VoucherExtensions::whereFkVoucherId($pv_id)->get();


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";

        $data = array(
            "list_voucher_extensions" => $list_voucher_extensions
        );
        $result_array['display'] = view('billing.lstvoucherextensions',$data)->render();
        return Response()->json($result_array);
    }

    /**
     * Delete Payment Voucher info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteVoucherInfo(Request $request)
    {
        $pv_id  = $request->input('pv_id');
        $result_array = array();



        $payment_vouchers   = PaymentVouchers::find($pv_id);
        $payment_vouchers->pv_is_deleted = 1;
        $payment_vouchers->pv_deleted_by = session('user_id');
        $payment_vouchers->save();

        $trans_id = $payment_vouchers->pv_transaction_id;

        $delete_trans = Transactions::where('at_id',$trans_id)->delete();
        $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
}
