<?php
/***********************************************************
 InvoicesController.php
 Product :
 Version : 1.0
 Release : 1
 Date Created : Oct 8, 2019
 Developed By  : Mohamad Mantach   PHP Department itm Solutions
 All Rights Reserved ,   itm Solutions COPYRIGHT 2019

 Page Description :

 ***********************************************************/

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\models\CallCenter\InboundCallProducts;
use App\models\Sales\OrderProducts;
use App\models\SRM\SupplierProducts;
use Validator;
use App;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
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
use PHPUnit\Framework\MockObject\Matcher\Parameters;
use App\models\System\Companies;
use App\models\Inventory\Customers;
use App\models\CRM\CRMServices;
use App\models\SRM\Suppliers;
use App\models\Users\Users;
use App\models\Users\UserTypes;
use App\models\Inventory\StockIds;
use App\models\Inventory\Stocks;
use Dompdf\Dompdf;
use App\models\CRM\CRMServicesPaymentTypes;
use App\models\Inventory\WareHouses;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use App\models\Inventory\WareHouseMovement;
use App\models\SRM\SupplierQuotations;
use App\models\CRM\CRMDeals;


class InvoicesController extends Controller
{

    /**
     * Page for invoice Management
     *
     * @author Moe Mantach
     * @access public
     */
    public function index()
    {
        $default_company_id = session('default_company_id');

        $list_accounts      = CRMAccounts::whereCaIsDeleted(0)->get();
        $list_customers     = Customers::whereIcIsDeleted(0)->whereIcCompanyId($default_company_id)->get();
        $lst_banks_info     = BankAccounts::whereBaIsDeleted(0)->get();

        $crm_telemarketing    = Config::get('appconfig.crm_telemarketing');
        $data = array(
            "list_accounts"     => $list_accounts,
            "list_customers"    => $list_customers,
            "crm_telemarketing"    => $crm_telemarketing,
            "lst_banks_info"    => $lst_banks_info
        );
        return Response()->view("billing.invoices",$data);
    }


    /**
     * Display list of products in the selected invoice
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayListProductsInvoice(Request $request)
    {
        $bi_id          = $request->input("bi_id");
        $invoice_info   = Invoices::find($bi_id);

        $result_array   = array();

        // save total invoice value in the database
        $AccountingManager = new AccountingManager();
        $params_array = array(
            "invoice_info" => $invoice_info
        );
        $total_array = $AccountingManager->CalculateTotalCostInvoice( $params_array );


        $data = array(
            "items_array" => $total_array['items_array'],
            "total_cost" => $total_array['total_cost'],
            "total_discount" => $total_array['total_discount'],
            "total_tax" => $total_array['total_tax'],
            "total_price" => $total_array['total_price'],
            "currency" => $total_array['currency'],
            "invoice_info" => $invoice_info
        );
        $result_array['display'] = view('billing.listproducts',$data)->render();

        unset($AccountingManager);
        return Response()->json($result_array);
    }


    /**
     * get product data information
     * @param Request $request
     */
    public function GetProductDataInfo(Request $request)
    {
        $product_id = $request->input('product_id');

        $product_info = Products::find($product_id);
        $result_array = array();


        $result_array['is_error'] = 0;
        $result_array['product_data'] = array(
            "p_product_selling_price" => $product_info->p_product_selling_price,
            "p_id" => $product_info->p_id,
            "p_product_ref" => $product_info->p_product_ref,
            "p_product_name" => $product_info->p_product_name,
            "p_product_description" => $product_info->p_product_description,
            "p_product_selling_price" => $product_info->p_product_selling_price,
            "p_product_cost_price" => $product_info->p_product_cost_price,
            "p_use_serialnumber" => $product_info->Category->pc_use_serial_number,
        );

        return Response()->json($result_array);
    }

    /**
     *
     * @param Request $request
     */
    public function DeleteInvoiceItems(Request $request)
    {
        $item_id    = $request->input('item_id');
        $bi_id      = $request->input('bi_id');

        $delete_item = InvoiceProducts::find($item_id);
        $delete_item->delete();

        $result_array = array();
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }


    /**
     * Get information of the invoice item
     *
     * @author Moe Mantach
     * @access  public
     * @param Request $request
     */
    public function GetInvoiceItemInfo(Request $request)
    {
        $item_id        = $request->input('item_id');

        $item_info      =  InvoiceProducts::find($item_id);
        $result_array   = array();
        $item_type = $item_info->ii_item_type;
        $ii_item_id = $item_info->ii_item_id;
        $section_id = 0;
        if($item_type == 2){
            $section_id = $ii_item_id;
        }
        $item_array = array(
            'id' => $item_info->ii_id,
            'ii_item_id' => $item_info->ii_item_id,
            'invoice_id' => $item_info->fk_invoice_id,
            'item_type' => $item_info->ii_item_type,
            'item_type_id' => $section_id,
            'item_label' => $item_info->ii_item_label,
            'item_cost' => $item_info->ii_cost_price,
            'item_supplier' => $item_info->ii_supplier_id,
            'item_price' => $item_info->ii_item_price,
            'item_qyt'  => $item_info->ii_item_qyt,
            'item_currency'  => $item_info->ii_price_currency
        );


        $result_array['is_error'] = 0;
        $result_array['item_array'] = $item_array;

        return Response()->json($result_array);
    }


    /**
     * Display list of payments for the invoice
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     */
    public function DisplayListPaymentsInvoice(Request $request)
    {
        $bi_id                  = $request->input("bi_id");
        $result_array           = array();
        $lst_payments_invoice   = InvoicePayments::whereFkInvoiceId($bi_id)->get();
        $invoice_info           = Invoices::find($bi_id);
        $lst_currency           = Currency::all();
        $currency_array         = CreateDatabaseArrayByIndex($lst_currency,"cc_id");
        $lst_payments = PaymentTypes::wherePtIsDeleted(0)->get();


        $result_array['invoice_info']   = $invoice_info;
        $result_array['records']        = count($lst_payments_invoice);
        $data = array(
            "lst_payments_invoice" => $lst_payments_invoice,
            "invoice_info" => $invoice_info,
            "lst_payments" => $lst_payments,
            "currency" => $currency_array[ $invoice_info->bi_invoice_currency ]['cc_currency_code'],
        );
        $result_array['display'] = view('billing.listpaymentsinvoice',$data)->render();
        return Response()->json($result_array);

    }


    /**
     * Download Invoice after fill all required variables
     *
     * @author Moe Mantach
     * @access public
     * @param number $bi_id
     *
     * template usage Parameters
     * %company_url%
     %logo_image_url%
     %company_name%
     %company_address%
     %company_phone%
     %company_email%
     %client_name%
     %client_address%
     %client_email%
     %invoice_code%
     %creation_date%
     %due_date%
     %bank_name%
     %bank_address%
     %bank_account_number%
     %bank_swift_code%
     %bank_account_name%
     %bank_iban%
     %contact_name%
     %contact_phone%
     %contact_email%
     *
     */
    public function DownloadInvoice( $bi_id )
    {
        $invoice_info   = Invoices::find($bi_id);
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $bank_id        = $invoice_info->fk_bankaccount_id;
        $bi_client_id   = $invoice_info->bi_client_id;
        $fk_customer_id = $invoice_info->fk_customer_id;
        if($bank_id != 0 ) $bank_info      = BankAccounts::find($bank_id);

        if($bi_client_id != 0) $crm_account    = CRMAccounts::find($bi_client_id);
        if($fk_customer_id != 0) $crm_customer = Customers::find($fk_customer_id);

        $crm_telemarketing     = Config::get('appconfig.crm_telemarketing');
        $display = "";

        if($crm_telemarketing == "0")
        {
            $data = array();
            $display = view("templates.invoices",$data)->render();

            $AccountingManager = new AccountingManager();
            $params_array = array(
                "invoice_info" => $invoice_info
            );
            $total_array = $AccountingManager->CalculateTotalCostInvoice( $params_array );

            $data = array(
                "items_array" => $total_array['items_array'],
                "total_cost" => $total_array['total_cost'],
                "total_discount" => $total_array['total_discount'],
                "total_tax" => $total_array['total_tax'],
                "total_price" => $total_array['total_price'],
                "currency" => $total_array['currency']
            );
            $item_table = view('billing.invoiceproducts',$data)->render();




            $profile_path     = public_path().'/'.Config::get('constants.COMPANY_PATH') . $company_info->cd_logo_base_src. $company_info->cd_logo_file_name. "." . $company_info->cd_logo_file_extension;
            $profile_url = url('/').'/'.Config::get('constants.COMPANY_PATH') . $company_info->cd_logo_base_src. $company_info->cd_logo_file_name. "." . $company_info->cd_logo_file_extension;
            if(!is_file($profile_path))
            {
                $profile_url= url('images/NoImageAvailable.jpg');
            }

            $display = str_replace("%company_url%", $company_info->cd_company_website, $display);
            $display = str_replace("%logo_image_url%",$profile_url, $display);
            $display = str_replace("%company_name%",$company_info->cd_company_name, $display);
            $display = str_replace("%company_address%",$company_info->cd_company_address, $display);
            $display = str_replace("%company_phone%",$company_info->cd_company_phone, $display);
            $display = str_replace("%company_email%",$company_info->cd_company_email, $display);
            $display = str_replace("%invoice_code%",$invoice_info->bi_invoice_ref, $display);
            $display = str_replace("%invoice_description%",$invoice_info->bi_invoice_note, $display);
            $display = str_replace("%creation_date%",$invoice_info->bi_invoice_date, $display);
            $display = str_replace("%due_date%",$invoice_info->bi_due_date, $display);
            $display = str_replace("%registration_number%",$company_info->cd_register_number, $display);
            if($bank_id != 0 )
            {
                $display = str_replace("%bank_name%",$bank_info->ba_bank_name, $display);
                $display = str_replace("%bank_address%",$bank_info->ba_account_address, $display);
                $display = str_replace("%bank_account_number%",$bank_info->ba_account_number, $display);
                $display = str_replace("%bank_swift_code%",$bank_info->ba_account_swift, $display);
                $display = str_replace("%bank_account_name%",$bank_info->ba_account_owner_name, $display);
                $display = str_replace("%bank_iban%",$bank_info->ba_account_iban, $display);

            }
            else
            {
                $display = str_replace("%bank_name%","", $display);
                $display = str_replace("%bank_address%","", $display);
                $display = str_replace("%bank_account_number%","", $display);
                $display = str_replace("%bank_swift_code%","", $display);
                $display = str_replace("%bank_account_name%","", $display);
                $display = str_replace("%bank_iban%","", $display);
            }


            $display = str_replace("%item_table%",$item_table, $display);

            if($bi_client_id != 0)
            {
                $display = str_replace("%client_name%",$crm_account->ca_account_name, $display);
                $display = str_replace("%client_address%",$crm_account->ca_billing_city . " " . $crm_account->ca_billing_street, $display);
                $display = str_replace("%client_email%",$crm_account->ca_account_email, $display);
                $display = str_replace("%client_phone%",$crm_account->ca_account_phone, $display);
            }
            else if($fk_customer_id != 0)
            {
                $display = str_replace("%client_name%",$crm_customer->ic_customer_name , $display);
                $display = str_replace("%client_address%",$crm_customer->ic_customer_address, $display);
                $display = str_replace("%client_email%",$crm_customer->ic_customer_email, $display);
                $display = str_replace("%client_phone%",$crm_customer->ic_customer_phone, $display);
            }


            $display = str_replace("%contact_name%",$company_info->cd_contact_name, $display);
            $display = str_replace("%contact_phone%",$company_info->cd_contact_mobile, $display);
            $display = str_replace("%contact_email%",$company_info->cd_contact_email, $display);
        }
        else
        {
             $data = array();
            $display = view("templates.contractinvoice",$data)->render();

                        $params_array = array(
                "invoice_info" => $invoice_info
            );
            $AccountingManager = new AccountingManager();
            $total_array = $AccountingManager->CalculateTotalCostInvoice( $params_array );

             $data_array = array(
                "items_array" => $total_array['items_array'],
                "total_cost" => $total_array['total_cost'],
                "total_discount" => $total_array['total_discount'],
                "total_tax" => $total_array['total_tax'],
                "total_price" => $total_array['total_price'],
                "currency" => $total_array['currency']
            );
            $item_table = view('billing.invoicecontractproducts',$data_array)->render();


            $display = str_replace("%company_name%",$company_info->cd_company_name, $display);
            $display = str_replace("%company_address%",$company_info->cd_company_address, $display);
            $display = str_replace("%company_phone%",$company_info->cd_company_phone, $display);
            $display = str_replace("%INVOICE_NUMBER%",$invoice_info->bi_invoice_ref, $display);
            $display = str_replace("%INVOICE_DATE%",$invoice_info->bi_invoice_date, $display);
            $display = str_replace("%LST_CONTRACT_INVOICES%",$item_table, $display);
            $display = str_replace("%registration_number%",$company_info->cd_register_number, $display);
            $display = str_replace("%CREATED_BY%",$invoice_info->CreatedUser->u_fullname, $display);
            $display = str_replace("%PRINTED_BY%",Session('user_fullname'), $display);
            $display = str_replace("%PRINT_DATE%",date('d-m-Y H:i:s'), $display);

            $profile_path     = public_path().'/'.Config::get('constants.COMPANY_PATH') . $company_info->cd_logo_base_src. $company_info->cd_logo_file_name. "." . $company_info->cd_logo_file_extension;
            $profile_url = url('/').'/'.Config::get('constants.COMPANY_PATH') . $company_info->cd_logo_base_src. $company_info->cd_logo_file_name. "." . $company_info->cd_logo_file_extension;
            if(!is_file($profile_path))
            {
                $profile_url= url('images/NoImageAvailable.jpg');
            }

            $display = str_replace("%company_url%", $company_info->cd_company_website, $display);
            $display = str_replace("%logo_image_url%",$profile_url, $display);

            if(isset($crm_account) == true)
            {
                $display = str_replace("%CLIENT_NAME%",$crm_account->ca_account_name, $display);
                $display = str_replace("%CLIENT_ADDRESS%",$crm_account->ca_billing_address, $display);
                $display = str_replace("%CLIENT_PHONE%",$crm_account->ca_account_mobile, $display);
                $display = str_replace("%ACCOUNT_NUMBER%",$invoice_info->Account->aa_account_ref, $display);
                $display = str_replace("%CONTRACT_TYPE%",($crm_account->ca_contract_type == 1 ? "WTS" : "RK"), $display);
            }

            $display = str_replace("%INVOICE_CURRENCY%",$invoice_info->Currency->cc_currency_code, $display);
            $display = str_replace("%INVOICE_COST%",$invoice_info->bi_total_cost, $display);
            $display = str_replace("%INVOICE_TOTAL%",$invoice_info->bi_total_price, $display);
            $display = str_replace("%INVOICE_TOTAL_LETTERS%",self::numberToWords($invoice_info->bi_total_price), $display);

        }




        return PDF::loadHTML($display)
            ->setPaper('a4')
            ->setOption('encoding', 'UTF-8')
            ->download('invoice-' . strtolower($invoice_info->bi_invoice_ref) . '.pdf');
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


    public function GetCompanySupplier(Request $request)
    {
        $bi_company_to = $request->input('bi_company_to');
        $internal_supplier_target = $request->input('internal_supplier_target');
        $internal_warehouse_target = $request->input('internal_warehouse_target');
        $lst_suppliers = Suppliers::whereSsCompanyId($bi_company_to)->whereSsIsDeleted(0)->get();
        $lst_warehouses = WareHouses::whereWCompanyId($bi_company_to)->whereWIsDeleted(0)->get();
        $result_array = array();
        $suppliers_array = array();
        $warehouses_array = array();
        foreach ($lst_suppliers as $key => $value)
        {
            $suppliers_array[$value->ss_id] = $value->ss_supplier_code . " " . $value->ss_supplier_name;
        }


        $data = array(
            "html_array" => $suppliers_array,
            "name" => 'bi_target_supplier',
            "value" => $internal_supplier_target,
            "is_required" => 1,
            "id" => 'BI_TARGET_SUPPLIER'
        );

        $result_array['supplier_dropdown'] = view('html.dropdown',$data)->render();


        foreach ($lst_warehouses as $key => $value)
        {
            $warehouses_array[$value->w_id] = $value->w_warehouse_name;
        }


        $data = array(
            "html_array" => $warehouses_array,
            "name" => 'bi_target_warehouse_id',
            "value" => $internal_warehouse_target,
            "is_required" => 1,
            "id" => 'BI_TARGET_WAREHOUSE_ID'
        );

        $result_array['warehouse_dropdown'] = view('html.dropdown',$data)->render();


        return Response()->json($result_array);
    }

    /**
     * Display List of invoices saved in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayListInvoices(Request $request)
    {

        $general_search     = $request->input("general_search");
        $invoice_customer   = $request->input("invoice_customer");
        $invoice_bank       = $request->input("invoice_bank");
        $start_date         = $request->input("start_date");
        $end_date           = $request->input("end_date");
        $page_number        = $request->input("page_number");
        $general_search         = $request->input("general_search");
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        $lst_customers    = Customers::whereIcIsDeleted(0)->get();
        $customers_array  = CreateDatabaseArrayByIndex($lst_customers, "ic_id");
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


        $default_company_id = session('default_company_id');

        $lst_invoices   = Invoices::whereBiIsDeleted(0)->where('bi_company_id','=',$default_company_id);

        if($invoice_customer!= 0)
            $lst_invoices = $lst_invoices->whereFkCustomerId($invoice_customer);
            if($invoice_bank!= 0)
                $lst_invoices = $lst_invoices->whereFkBankaccountId($invoice_bank);

            if(strlen($start_date) > 0)
                $lst_invoices = $lst_invoices->where('bi_invoice_date','>=',$start_date);

            if(strlen($end_date) > 0)
                $lst_invoices = $lst_invoices->where('bi_invoice_date','<',$end_date);


            if(strlen($start_date) ==  0 && strlen($end_date) ==  0)
            {
                if($firstday != '' || $lastday != '')
                    $lst_invoices = $lst_invoices->whereBetween('bi_invoice_date', [$firstday, $lastday]);
            }

            if(strlen($general_search) > 0)
            {
                $lst_invoices = $lst_invoices->where('bi_invoice_note','LIKE',"%" . $general_search. "%");
                $lst_invoices = $lst_invoices->orWhere('bi_contract_number','LIKE',"%" . $general_search. "%");
                $lst_invoices = $lst_invoices->orWhere('bi_account_number','LIKE',"%" . $general_search. "%");
            }

            $count_invoices =     $lst_invoices->count();
            $total_pages = ceil( $count_invoices/$nbr_rows_per_pages );
            $total_pages = intval($total_pages);

            $lst_invoices = $lst_invoices->skip($skip)->take($nbr_rows_per_pages)->orderby('bi_id',"DESC")->get();

            $lst_currency           = Currency::all();
            $currency_array         = CreateDatabaseArrayByIndex($lst_currency,"cc_id");


            $data = array(
                "customers_array" => $customers_array,
                "lst_invoices" => $lst_invoices,
                "currency_array" => $currency_array
            );

            $result_array = array();
            $result_array['total_pages'] = $total_pages;
            $result_array['display'] = view("billing.listinvoices",$data)->render();

            return Response()->json($result_array);
    }


    public function AddForm()
    {
        $AccountingManager = new AccountingManager();

        $invoice_code = $AccountingManager->GenerateInvoiceCode();

        $default_company_id = session('default_company_id');

        $list_accounts      = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_banks_info     = BankAccounts::whereBaIsDeleted(0)->get();
        $lst_payment_types  = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_payment_terms  = PaymentTerms::wherePtIsDeleted(0)->get();
        $lst_vat_accounts   = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_currencies     = Currency::all();
        $list_customers     = Customers::whereIcIsDeleted(0)->whereIcCompanyId($default_company_id)->get();
        $list_services      = CRMServices::whereCsIsDeleted(0)->get();

        $company_id = session('company_id');
        $lst_companies = Companies::whereCdIsDeleted(0)->whereNotIn('cd_id',[$company_id])->get();

        $data = array(
            "invoice_code" => $invoice_code,
            "list_accounts" => $list_accounts,
            "list_customers" => $list_customers,
            "lst_banks_info" => $lst_banks_info,
            "lst_payment_types" => $lst_payment_types,
            "lst_payment_terms" => $lst_payment_terms,
            "lst_currencies" => $lst_currencies,
            "list_services" => $list_services,
            "lst_companies" => $lst_companies,
            "lst_vat_accounts" => $lst_vat_accounts
        );
        return Response()->view("billing.addinvoice",$data);

    }

    public function AddOficialForm()
    {
        $AccountingManager = new AccountingManager();

        $invoice_code = $AccountingManager->GenerateOfficialInvoiceCode();
        $default_company_id = session('default_company_id');

        $list_accounts      = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_banks_info     = BankAccounts::whereBaIsDeleted(0)->get();
        $lst_payment_types  = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_payment_terms  = PaymentTerms::wherePtIsDeleted(0)->get();
        $lst_vat_accounts   = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_currencies     = Currency::all();
        $list_customers     = Customers::whereIcIsDeleted(0)->whereIcCompanyId($default_company_id)->get();
        $list_services      = CRMServices::whereCsIsDeleted(0)->get();

        $company_id = session('company_id');
        $lst_companies = Companies::whereCdIsDeleted(0)->whereNotIn('cd_id',[$company_id])->get();

        $data = array(
            "invoice_code" => $invoice_code,
            "list_accounts" => $list_accounts,
            "list_customers" => $list_customers,
            "lst_banks_info" => $lst_banks_info,
            "lst_payment_types" => $lst_payment_types,
            "lst_payment_terms" => $lst_payment_terms,
            "lst_currencies" => $lst_currencies,
            "list_services" => $list_services,
            "lst_companies" => $lst_companies,
            "lst_vat_accounts" => $lst_vat_accounts
        );
        return Response()->view("billing.addofinvoice",$data);

    }

    /**
     * Display Edit Receipt Form From
     * @param unknown $br_id
     */
    public function EditIReceiptForm( $br_id )
    {

        $receipt_info = Receipts::find($br_id);
        $default_company_id = session('default_company_id');

        $lst_invoices = Invoices::whereBiIsDeleted(0)->whereBiCompanyId($default_company_id)->get();
        $lst_customers = Customers::whereIcIsDeleted(0)->whereIcCompanyId($default_company_id)->get();
        $lst_payment_types = PaymentTypes::all();
        $lst_currencies= Currency::all();
        $lst_accounts = ChartAccounts::whereAaIsDeleted(0)->get();

        $AccountingManager = new AccountingManager();

        $receipt_code = $AccountingManager->GenerateReceiptCode();

        $params_array = array(
            "receipt_code" => $receipt_code,
            "receipt_info" => $receipt_info,
            "lst_payment_types" => $lst_payment_types,
            "lst_invoices" => $lst_invoices,
            "lst_currencies" => $lst_currencies,
            "lst_accounts" => $lst_accounts,
            "lst_customers" => $lst_customers
        );
        return Response()->view("billing.editireceipt",$params_array);
    }

    public function EditForm( $bi_id )
    {
        $invoice_info       = Invoices::find($bi_id);
        $default_company_id = session('default_company_id');

        $list_accounts      = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_banks_info     = BankAccounts::whereBaIsDeleted(0)->get();
        $lst_payment_types  = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_payment_terms  = PaymentTerms::wherePtIsDeleted(0)->get();
        $lst_products       = Products::wherePProductIsDeleted(0)->get();
        $lst_vat_accounts   = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_warehouses   = WareHouses::whereWIsDeleted(0)->whereWCompanyId($default_company_id)->get();
        $lst_currencies     = Currency::all();
        $currencies_array = CreateDatabaseArrayByIndex($lst_currencies, "cc_id");
        $list_customers     = Customers::whereIcIsDeleted(0)->whereIcCompanyId($default_company_id)->get();
        $list_services      = CRMServices::whereCsIsDeleted(0)->get();
        $lst_suppliers      = Suppliers::whereSsIsDeleted(0)->whereSsCompanyId($default_company_id)->get();
        $lst_technicians = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereFkCompanyId($default_company_id)->whereUUserType(UserTypes::USER_TYPE_TECHNICIAN)->get();
        $lst_collectors = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereFkCompanyId($default_company_id)->whereUUserType(UserTypes::USER_TYPE_COLLECTOR)->get();
        $company_id = session('company_id');
        $lst_companies = Companies::whereCdIsDeleted(0)->whereNotIn('cd_id',[$company_id])->get();

        $data = array(
            "invoice_info" => $invoice_info,
            "lst_warehouses" => $lst_warehouses,
            "list_accounts" => $list_accounts,
            "lst_banks_info" => $lst_banks_info,
            "lst_products" => $lst_products,
            "lst_payment_types" => $lst_payment_types,
            "lst_payment_terms" => $lst_payment_terms,
            "lst_currencies" => $lst_currencies,
            "currencies_array" => $currencies_array,
            "list_customers" => $list_customers,
            "list_services" => $list_services,
            "lst_companies" => $lst_companies,
            "lst_suppliers" => $lst_suppliers,
            "lst_technicians" => $lst_technicians,
            "lst_collectors" => $lst_collectors,
            "lst_vat_accounts" => $lst_vat_accounts
        );
        return Response()->view("billing.editinvoice",$data);
    }

    /**
     * Get Payment Bills Information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetPaymentBillsInfo(Request $request)
    {
        $ip_id = $request->input('ip_id');

        $payment_info = InvoicePayments::find($ip_id);

        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['payment_info'] = array(
            'ip_id' => $payment_info->ip_id,
            'ip_billing_nbr' => $payment_info->ip_billing_nbr,
            'ip_billing_date' => $payment_info->ip_billing_date,
            'ip_updated_by' => $payment_info->ip_updated_by,
            'ip_updated_date' => $payment_info->ip_updated_date,
            'ip_payment_doc' => $payment_info->ip_payment_doc,
            'ip_collector_id' => $payment_info->ip_collector_id,
            'ip_payment_type' => $payment_info->ip_payment_type,
        );

        return Response()->json($result_array);

    }


    /**
     * Save Invoice Bill Record
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveInvoicePayment(Request $request)
    {
        $ip_id = $request->input('ip_id');
        $ip_billing_nbr = $request->input('ip_billing_nbr');
        $ip_billing_date = $request->input('ip_billing_date');
        $ip_updated_date = $request->input('ip_updated_date');
        $ip_payment_doc = $request->input('ip_payment_doc');
        $ip_collector_id = $request->input('ip_collector_id');
        $ip_payment_type = $request->input('ip_payment_type');
        $ip_billing_status = $request->has('ip_billing_status') ? 1 : 0;
        $ip_updated_by = session('user_id');

         $payment_info = InvoicePayments::find($ip_id);

        $result_array = array();

        $payment_info->ip_billing_nbr = $ip_billing_nbr;
        $payment_info->ip_billing_date = $ip_billing_date;
        $payment_info->ip_updated_date = $ip_updated_date;
        $payment_info->ip_payment_doc = $ip_payment_doc;
        $payment_info->ip_collector_id = $ip_collector_id;
        $payment_info->ip_payment_type = $ip_payment_type;
        $payment_info->ip_billing_status = $ip_billing_status;
        $payment_info->ip_updated_by = session('user_id');
        $payment_info->save();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = 'Operation Completed Successfully';
        return Response()->json($result_array);

    }

    /**
     * Insert Invoice items and save it into the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function InsertInvoiceItems(Request $request)
    {
        $item_id            = $request->input("item_id");
        $invoice_id         = $request->input("invoice_id");
        $bi_product         = $request->input("bi_product");
        $bi_quanity         = $request->input("bi_quanity");
        $invoice_type_item  = $request->input("invoice_type_item");
        $ii_product_serial_number  = $request->input("ii_product_serial_number");
        $ii_payment_type    = $request->input("ii_payment_type");
        $bi_item_price    = $request->input("bi_item_price");
        $ii_warehouse_id    = $request->input("ii_warehouse_id");
        $currency_id    = $request->input("currency_id");
        $product_info       = Products::find($bi_product);
        $invoice_info       = Invoices::find($invoice_id);
        $result_array       = array();

        $warehouse_info = WareHouses::find($ii_warehouse_id);

        if($item_id == "")
            $invoice_product = new InvoiceProducts();
        else
            $invoice_product = InvoiceProducts::find($item_id);



        $warehouse_movement = new WareHouseMovement();
        $warehouse_movement->wm_warehouse_id = $ii_warehouse_id;
        $warehouse_movement->wm_product_id = $bi_product;
        $warehouse_movement->wm_quantity = $bi_quanity;
        $warehouse_movement->wm_action_date = date('Y-m-d');
        $warehouse_movement->wm_action_type = "STOCK_OUT";
        $warehouse_movement->wm_action_description = "Stock Out " . $bi_quanity . " of " . $product_info->mp_product_name . " From " . $warehouse_info->w_warehouse_name . " using Invoice Number #" . $invoice_info->bi_invoice_code;
        $warehouse_movement->save();




        if($product_info->Category->pc_use_serial_number == 1)
        {
            $stock_serial_info = StockIds::whereSiStockUid($ii_product_serial_number)->whereSiStockSold(0)->get();

            if(count($stock_serial_info) == 0)
            {
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = "Serial Number Not Found In";
                return Response()->json($result_array);
            }

            $invoice_product->fk_invoice_id     = $invoice_id;
            $invoice_product->ii_item_id        = $bi_product;
            $invoice_product->ii_item_type      = $invoice_type_item;
            $invoice_product->ii_product_serial_number      = $ii_product_serial_number;
            $invoice_product->ii_item_label     = $product_info->p_product_name;
            $invoice_product->ii_item_qyt       = $bi_quanity;
            $invoice_product->ii_payment_type   = $ii_payment_type;
            $invoice_product->ii_item_price     = $bi_item_price;
            $invoice_product->ii_price_currency     = $currency_id;
            $invoice_product->ii_total_price    =$bi_item_price * $bi_quanity;
            $invoice_product->save();


            // update product for serial number to be 1
            $stockid_obj = StockIds::whereSiStockUid($ii_product_serial_number)->first();
            $stockid_obj->si_stock_sold = 1;
            $fk_stock_id = $stockid_obj->fk_stock_id;
            $stockid_obj->save();

            $stock_info = Stocks::find($fk_stock_id);
            $stock_info->is_quanity = $stock_info->is_quanity - $bi_quanity;
            $stock_info->save();
        }
        else
        {
            // Variable for the quantity we still need to fulfill.
            $quantity_to_fulfill = $bi_quanity;

// Wrap the entire operation in a database transaction.
// This ensures that if any part fails, all database changes are rolled back.
            try {
                DB::transaction(function () use (
                    $bi_product,
                    $ii_warehouse_id,
                    &$quantity_to_fulfill, // Pass by reference to modify it
                    $bi_quanity,
                    $invoice_id,
                    $bi_item_price,
                    $currency_id,
                    $ii_payment_type,
                    $product_info
                ) {

                    // 1. Get all available stock for the product, oldest first.
                    $available_stock = Stocks::where('fk_product_id', $bi_product)
                        ->where('fk_warehouse_id', $ii_warehouse_id)
                        ->where('is_quanity', '>', 0) // Find any record with stock
                        ->orderBy('is_id', 'asc')
                        ->lockForUpdate() // Lock rows to prevent race conditions
                        ->get();

                    // 2. Check if the TOTAL available stock is sufficient.
                    $total_stock = $available_stock->sum('is_quanity');
                    if ($total_stock < $quantity_to_fulfill) {
                        // Use an exception to automatically trigger the transaction rollback.
                        throw new \Exception("We don't have enough total stock for this product. Required: $quantity_to_fulfill, Available: $total_stock");
                    }

                    // 3. Loop through each stock record to fulfill the order.
                    foreach ($available_stock as $stock_batch) {
                        if ($quantity_to_fulfill <= 0) {
                            break; // Stop if the order is already fulfilled.
                        }

                        // Determine how much to take from this specific batch.
                        $quantity_to_take = min($stock_batch->is_quanity, $quantity_to_fulfill);
                        $invoice_product = new InvoiceProducts();
                        $invoice_product->fk_invoice_id     = $invoice_id;
                        $invoice_product->ii_item_id        = $bi_product;
                        $invoice_product->ii_item_type      = 1;
                        $invoice_product->ii_product_serial_number      = "";
                        $invoice_product->ii_item_label     = $product_info->p_product_name;
                        $invoice_product->ii_price_currency = $product_info->p_product_currency;
                        $invoice_product->ii_item_qyt       = $quantity_to_fulfill;
                        $invoice_product->ii_payment_type   = $ii_payment_type;
                        $invoice_product->ii_item_price     = $bi_item_price;
                        $invoice_product->ii_price_currency     = $currency_id;
                        $invoice_product->ii_total_price    =$bi_item_price * $bi_quanity;
                        $invoice_product->save();

                        // Decrease the stock quantity for this batch.
                        $stock_batch->decrement('is_quanity', $quantity_to_take);

                        // Update the remaining quantity we need to fulfill.
                        $quantity_to_fulfill -= $quantity_to_take;
                    }
                });
            } catch (\Exception $e) {
                // If the transaction failed, return the error message.
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = $e->getMessage();
                return response()->json($result_array);
            }
        }

        // save total invoice value in the database
        $AccountingManager = new AccountingManager();
        $params_array = array(
            "invoice_info" => $invoice_info
        );
        $total_array = $AccountingManager->CalculateTotalCostInvoice( $params_array );

        // save the updated total cost and price to the database
        $invoice_info->bi_total_cost = $total_array['total_cost'];
        $invoice_info->bi_total_price   = $total_array['total_price'];
        $invoice_info->save();

        unset($AccountingManager);

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        return Response()->json($result_array);
    }


    public function LinkInvoiceItems(Request $request)
    {
        $item_id            = $request->input("item_id");
        $invoice_id         = $request->input("bi_invoice_id");
        $ii_warehouse_id    = $request->input("ii_warehouse_id");
        $bi_product         = $request->input("bi_product_id");
        $bi_quanity         = $request->input("bi_quanity");
        $invoice_type_item  = $request->input("invoice_type_item");
        $ii_product_serial_number  = $request->input("ii_product_serial_number");
        $ii_payment_type    = $request->input("ii_payment_type");
        $bi_item_price    = $request->input("bi_item_price");
        $product_info       = Products::find($bi_product);
        $invoice_info       = Invoices::find($invoice_id);
        $result_array       = array();

        // validate existing of serial number


        if($ii_product_serial_number != "")
        {
            if($product_info->Category->pc_use_serial_number == 0)
            {
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = "Please use a Product Need Serial Number";
                return Response()->json($result_array);
            }

            $stock = StockIds::whereSiStockUid($ii_product_serial_number)->whereSiStockSold(0)->get();

            if(count($stock) == 0)
            {
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = "this Serial Number Not Found";
                return Response()->json($result_array);
            }

            $invoice_product = new InvoiceProducts();
            $invoice_product->fk_invoice_id     = $invoice_id;
            $invoice_product->ii_item_id        = $bi_product;
            $invoice_product->ii_item_type      = 1;
            $invoice_product->ii_product_serial_number      = $ii_product_serial_number;
            $invoice_product->ii_item_label     = $product_info->p_product_name;
            $invoice_product->ii_price_currency = $product_info->p_product_currency;
            $invoice_product->ii_item_qyt       = $bi_quanity;
            $invoice_product->ii_payment_type   = $ii_payment_type;
            $invoice_product->ii_item_price     = $bi_item_price;
            $invoice_product->ii_total_price    =$bi_item_price * $bi_quanity;
            $invoice_product->save();
        }
        else
        {
            $stock_info = Stocks::whereFkProductId($bi_product)->whereFkWarehouseId($ii_warehouse_id)->get();

            if(count($stock_info) == 0)
            {
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = "we dont have any stock for this product in warehouse";
                return Response()->json($result_array);
            }

            // check if serial number is is used
            $stock_used = StockIds::where('si_stock_uid','=',$ii_product_serial_number)->where('si_stock_sold','=',1)->get();

            if(count($stock_used) == 1)
            {
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = "This product with this serial number is already used";
                return Response()->json($result_array);
            }



            $invoice_product = new InvoiceProducts();
            $invoice_product->fk_invoice_id     = $invoice_id;
            $invoice_product->ii_item_id        = $bi_product;
            $invoice_product->ii_item_type      = 1;
            $invoice_product->ii_product_serial_number      = $ii_product_serial_number;
            $invoice_product->ii_item_label     = $product_info->p_product_name;
            $invoice_product->ii_price_currency = $product_info->p_product_currency;
            $invoice_product->ii_item_qyt       = $bi_quanity;
            $invoice_product->ii_payment_type   = $ii_payment_type;
            $invoice_product->ii_item_price     = $bi_item_price;
            $invoice_product->ii_total_price    =$bi_item_price * $bi_quanity;
            $invoice_product->save();
        }


            // save total invoice value in the database
            $AccountingManager = new AccountingManager();
            $params_array = array(
                "invoice_info" => $invoice_info
            );
            $total_array = $AccountingManager->CalculateTotalCostInvoice( $params_array );

            // save the updated total cost and price to the database
            $invoice_info->bi_total_cost = $total_array['total_cost'];
            $invoice_info->bi_total_price   = $total_array['total_price'];
            $invoice_info->save();

            unset($AccountingManager);

            $result_array['is_error'] = 0;
            $result_array['error_msg'] = "Operation Completed Successfully";
            return Response()->json($result_array);
    }

    /**
     * Insert Service to the Invoice Items and change
     * @param Request $request
     */
    public function InsertInvoiceServices(Request $request)
    {
        $item_id            = $request->input("item_id");
        $invoice_id         = $request->input("invoice_id");
        $bi_service_id      = $request->input("bi_service_id");
        $ii_supplier_id     = $request->input("ii_supplier_id");
        $ii_cost_price      = $request->input("ii_cost_price");
        $ii_payment_type    = $request->input("ii_payment_type");
        $bi_service_price   = $request->input("bi_service_price");
        $invoice_type_item  = $request->input("invoice_type_item");
        $invoice_payment_type   = $request->input("invoice_payment_type");
        $ii_cost_price= $request->input("ii_cost_price");
        $service_info       = CRMServices::find($bi_service_id);
        $invoice_info       = Invoices::find($invoice_id);
        $result_array       = array();

        $invoice_product = new InvoiceProducts();
        if($item_id > 0)
        {
            $invoice_product = InvoiceProducts::find($item_id);
        }



        $invoice_product->fk_invoice_id             = $invoice_id;
        $invoice_product->ii_item_id                = $bi_service_id;
        $invoice_product->ii_item_type              = $invoice_type_item;
        $invoice_product->ii_item_label             = $service_info->cs_service_title;
        $invoice_product->ii_price_currency         = $invoice_info->bi_invoice_currency;
        $invoice_product->ii_item_qyt               = 1;
        $invoice_product->ii_item_price             = $bi_service_price;
        $invoice_product->ii_total_price            = $bi_service_price;
        $invoice_product->ii_cost_price             = $ii_cost_price;
        $invoice_product->ii_supplier_id            = $ii_supplier_id;
        $invoice_product->ii_sales_account_id       = $service_info->cs_sale_accounting_code;
        $invoice_product->ii_purchase_account_id    = $service_info->cs_purchase_accounting_code;
        $invoice_product->ii_payment_type_id        = $invoice_payment_type;
        $invoice_product->save();

        // save total invoice value in the database
        $AccountingManager = new AccountingManager();
        $params_array = array(
            "invoice_info" => $invoice_info
        );
        $total_array = $AccountingManager->CalculateTotalCostInvoice( $params_array );

        // save the updated total cost and price to the database
        $invoice_info->bi_total_cost = $total_array['total_cost'];
        $invoice_info->bi_total_price   = $total_array['total_price'];
        $invoice_info->save();
        $AccountingManager = null;
        unset($AccountingManager);

        $result_array['is_error'] = 0;
        return Response()->json($result_array);

    }

    public function SaveSplitPayments(Request $request)
    {
        $bi_id                  = $request->input('bi_id');
        $ip_payment_type        = $request->input('ip_payment_type');
        $ip_payment_percentage  = $request->input('ip_payment_percentage');
        $ip_payment_label       = $request->input('ip_payment_label');

        InvoicePayments::whereFkInvoiceId($bi_id)->delete();
        if($ip_payment_label != null)
        {
            for ($i = 0; $i < count($ip_payment_label); $i++)
            {
                $payment_label      = $ip_payment_label[$i];
                if($payment_label == null)
                    continue;
                    $payment_percentage     = $ip_payment_percentage[$i];
                    $payment_type           = $ip_payment_type[$i];

                    if(strlen($payment_label) == 0 || strlen($payment_percentage) == 0)
                        continue;

                        $invoicePayment = new InvoicePayments();
                        $invoicePayment->fk_invoice_id          = $bi_id;
                        $invoicePayment->ip_payment_percentage  = $payment_percentage;
                        $invoicePayment->ip_payment_type        = $payment_type;
                        $invoicePayment->ip_payment_label       = $payment_label;
                        $invoicePayment->save();
            }
        }

        $lst_payments_invoice   = InvoicePayments::whereFkInvoiceId($bi_id)->get();
        $invoice_info           = Invoices::find($bi_id);
        $lst_currency           = Currency::all();
        $currency_array         = CreateDatabaseArrayByIndex($lst_currency,"cc_id");
        $lst_payments = PaymentTypes::wherePtIsDeleted(0)->get();


        $result_array['invoice_info']   = $invoice_info;
        $result_array['records']        = count($lst_payments_invoice);
        $data = array(
            "lst_payments_invoice" => $lst_payments_invoice,
            "invoice_info" => $invoice_info,
            "lst_payments" => $lst_payments,
            "currency" => $currency_array[ $invoice_info->bi_invoice_currency ]['cc_currency_code'],
        );
        $result_array['display'] = view('billing.listpaymentsinvoice',$data)->render();


        $result_array['is_error'] = 0;
        $result_array['bi_id'] = $bi_id;
        $result_array['error_msg'] = "Operation Completed Successfully";
        return Response()->json($result_array);
    }


    /**
     * Return Invoice
     * @param Request $request
     * @return void
     */
    public function ReturnInvoice(Request $request)
    {
        $bi_id = $request->input('bi_id');
        $result_array = array();
        $invoice_info = Invoices::find($bi_id);
        $default_company_id = session('default_company_id');

        // get transaciton id and save accounting records
        $trans_id = $invoice_info->bi_transaction_id;

        $transaction_info = Transactions::find($trans_id);


        $account_id = 0;
        if($invoice_info->fk_customer_id != null)
        {
            $customer_info = Customers::find($invoice_info->fk_customer_id);
            $account_id = $customer_info->ic_account_number;
        }
        else
        {

            $client_info = CRMAccounts::find($invoice_info->bi_client_id);
            $account_id = $client_info->ca_accounting_id;
        }


        $TransactionMovement = new TransactionMovements();
        $TransactionMovement->fk_tran_id            = $trans_id;
        $TransactionMovement->tm_company_id            = $default_company_id;
        $TransactionMovement->tm_trans_code         = $invoice_info->bi_invoice_code;
        $TransactionMovement->tm_ledger_account     = $account_id;
        $TransactionMovement->tm_sub_ledger_account = $account_id;
        $TransactionMovement->tm_ledger_label       = $invoice_info->bi_invoice_code . " " . $invoice_info->bi_invoice_note;
        $TransactionMovement->tm_debit              = 0;
        $TransactionMovement->tm_credit             = $invoice_info->bi_total_price;
        $TransactionMovement->tm_creation_date      = date("Y-m-d");
        $TransactionMovement->tm_transaction_date   = $invoice_info->bi_invoice_date;
        $TransactionMovement->tm_currency_id        =$invoice_info->bi_invoice_currency;
        $TransactionMovement->save();


        $TransactionMovement = new TransactionMovements();
        $TransactionMovement->fk_tran_id            = $trans_id;
        $TransactionMovement->tm_company_id            = $default_company_id;
        $TransactionMovement->tm_trans_code         = $invoice_info->bi_invoice_code;
        $TransactionMovement->tm_ledger_account     = 701;
        $TransactionMovement->tm_sub_ledger_account = 701;
        $TransactionMovement->tm_ledger_label       = $invoice_info->bi_invoice_code . " " . $invoice_info->bi_invoice_note;
        $TransactionMovement->tm_debit              = $invoice_info->bi_total_price;
        $TransactionMovement->tm_credit             = 0;
        $TransactionMovement->tm_creation_date      = date("Y-m-d");
        $TransactionMovement->tm_transaction_date   = $invoice_info->bi_invoice_date;
        $TransactionMovement->tm_currency_id        = $invoice_info->bi_invoice_currency;
        $TransactionMovement->save();

        // get deal info

        $client_id  =  $invoice_info->bi_client_id;

        $deal_info = CRMDeals::whereFkAccountId($client_id)->first();
        $deal_id = $deal_info->ad_id;



        // delete bills not paied
        $bills_info = InvoicePayments::whereIpIsDeleted(0)->whereIpDealId($deal_id)->whereIpPaymentStatus(0)->delete();


        // change status of deal to be cancel
        $deal_info = CRMDeals::find($deal_id);
        $deal_info->ad_is_approved = 3;
        $deal_info->save();


        $invoice_info->bi_invoice_status = 0;
        $invoice_info->save();



        // return stock to product stock management



        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";

        return Response()->json($result_array);
    }


    /**
     * Save invoice info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveInvoiceInfo( Request $request)
    {
        $bi_invoice_ref         = $request->input("bi_invoice_ref");
        $bi_invoice_code        = $request->input("bi_invoice_code");
        $invoice_account        = $request->input("invoice_account");
        $bi_invoice_date        = $request->input("bi_invoice_date");
        $bi_official_invoice        = $request->input("bi_official_invoice");
        $bi_invoice_date        = date("Y-m-d",strtotime($bi_invoice_date));
        $cyear                  = date('Y', strtotime($bi_invoice_date));
        $default_company_id = session('default_company_id');


      //  $fk_bankaccount_id      = $request->input("fk_bankaccount_id");
        $bi_payment_type        = $request->input("bi_payment_type");
        $bi_payment_terms       = $request->input("bi_payment_terms");
        $bi_invoice_note        = $request->input("bi_invoice_note");
        $bi_invoice_currency    = $request->input("bi_invoice_currency");
        $bi_invoice_items_type  = $request->input("bi_invoice_items_type");
        $bi_id                  = $request->input("bi_id");
        $bi_vat_id              = $request->input("bi_vat_id");
        $ip_payment_label       = $request->input("ip_payment_label");
        $ip_payment_type        = $request->input("ip_payment_type");
        //$ip_payment_label       = $ip_payment_label[0];
        $ip_payment_percentage  = $request->input("ip_payment_percentage");
        $bi_discount            = $request->input("bi_discount");
        $ini_invoice_type       = $request->input("ini_invoice_type");
        $fk_customer_id         = $request->input("fk_customer_id");
        $bi_second_currency     = $request->input("bi_second_currency");
        $bi_exchange_rate       = $request->input("bi_exchange_rate");
        $bi_contract_number       = $request->input("bi_contract_number");
        $bi_account_number       = $request->input("bi_account_number");
        $bi_company_to       = $request->input("bi_company_to");
        $bi_target_warehouse_id     = $request->input("bi_target_warehouse_id");
        $bi_target_supplier         = $request->input("bi_target_supplier");
        $bi_internal_invoice        = $request->has("bi_internal_invoice") ? 1 : 0;
        $invoice_info           =  new Invoices();
        $result_array           = array();
        $action = "add";
        if($bi_id != null)
        {
            $invoice_info = Invoices::find($bi_id);

            // check if the user change the invoice items type and return error message
            /**if($ini_invoice_type != $bi_invoice_items_type)
             {
             $invoice_items_count = InvoiceProducts::whereFkInvoiceId($bi_id)->count();
             if($invoice_items_count > 0)
             {
             $result_array['is_error']   = 1;
             $result_array['error_msg']  = "you cannot change the item type once you add an item";

             return Response()->json($result_array);
             }

             }*/

            $action = "edit";
            $invoice_info->bi_last_updated_by   = session('user_id');
        }
        else
        {
            $invoice_info->bi_created_by        = session('user_id');
        }

        $client_info = CRMAccounts::find($invoice_account);

        $item_type = is_numeric($bi_invoice_items_type ) ? $bi_invoice_items_type :  $ini_invoice_type;



        $invoice_info->bi_invoice_ref       = $bi_invoice_ref;
        $invoice_info->bi_company_id       = $default_company_id;
        $invoice_info->bi_invoice_code      = $bi_invoice_code;
        $invoice_info->bi_client_id         = $invoice_account;
        $invoice_info->fk_account_id         = $client_info ? $client_info->ca_accounting_id : 0;
        $invoice_info->fk_customer_id       = $fk_customer_id;
        $invoice_info->bi_invoice_date      = $bi_invoice_date;
        //$invoice_info->fk_bankaccount_id    = $fk_bankaccount_id;
        $invoice_info->bi_payment_type      = $bi_payment_type;
        $invoice_info->bi_payment_terms     = $bi_payment_terms;
        $invoice_info->bi_invoice_note      = $bi_invoice_note;
        $invoice_info->bi_invoice_type      = $item_type;
        $invoice_info->bi_invoice_currency  = $bi_invoice_currency;
        $invoice_info->bi_vat_id            = $bi_vat_id;
        $invoice_info->bi_discount          = $bi_discount;
        $invoice_info->bi_second_currency   = $bi_second_currency;
        $invoice_info->bi_exchange_rate     = $bi_exchange_rate;
        $invoice_info->bi_contract_number       = $bi_contract_number;
        $invoice_info->bi_account_number        = $bi_account_number;
        $invoice_info->bi_internal_invoice      = $bi_internal_invoice;
        $invoice_info->bi_target_supplier      = $bi_target_supplier;
        $invoice_info->bi_target_warehouse_id      = $bi_target_warehouse_id;
        $invoice_info->bi_official_invoice      = $bi_official_invoice;
        $invoice_info->bi_company_to            = $bi_company_to;
        $invoice_info->save();



        $bi_id = $invoice_info->bi_id;
        // delete all invoice steps and create the new one
        InvoicePayments::whereFkInvoiceId($bi_id)->delete();

        if($ip_payment_label != null)
        {
            for ($i = 0; $i < count($ip_payment_label); $i++)
            {
                $payment_label      = $ip_payment_label[$i];
                if($payment_label == null)
                    continue;
                    $payment_percentage     = $ip_payment_percentage[$i];
                    $payment_type           = $ip_payment_type[$i];

                    if(strlen($payment_label) == 0 || strlen($payment_percentage) == 0)
                        continue;

                        $invoicePayment = new InvoicePayments();
                        $invoicePayment->fk_invoice_id          = $bi_id;
                        $invoicePayment->ip_payment_percentage  = $payment_percentage;
                        $invoicePayment->ip_payment_type        = $payment_type;
                        $invoicePayment->ip_payment_label       = $payment_label;
                        $invoicePayment->save();
            }
        }

        //else
        {
            if($action == "edit")
            {
                $invoice_info = Invoices::find($bi_id);



                $lst_invoice_items =  InvoiceProducts::whereFkInvoiceId($bi_id)->get();
                $total_price = 0;
                foreach ( $lst_invoice_items as $key => $ii_info )
                {
                    $total_price = $total_price + $ii_info->ii_total_price;
                }

                $trans_id = $invoice_info->bi_transaction_id;
                $invoice_info->bi_transaction_id = 0;
                $invoice_info->bi_invoice_status    = 0;
                $invoice_info->save();

                unset($invoice_info);


                if( $trans_id > 0 )
                {
                    $delete_trans = Transactions::where('at_id',$trans_id)->delete();
                    $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();

                }

                // remove tags from description
                $bi_invoice_note = strip_tags($bi_invoice_note);


                $invoice_info = Invoices::find($bi_id);


                // add transaction record
                $AccTransaction = new Transactions();
                $AccTransaction->at_transaction_date    = $invoice_info->bi_invoice_date;
                $AccTransaction->at_creation_date       = date("Y-m-d");
                $AccTransaction->at_accounting_doc      = $invoice_info->bi_invoice_code;
                $AccTransaction->fk_acc_journal_id      = 3;
                $AccTransaction->save();
                $trans_id= $AccTransaction->at_id;
                $at_id = $trans_id;
                $invoice_info->bi_transaction_id = $trans_id;
                $invoice_info->bi_total_price= $total_price;
                $invoice_info->save();


                if(is_numeric($total_price))
                {
                    //get information of the customer
                    if($invoice_account != null)
                        $customer_info = CRMAccounts::find( $invoice_info->bi_client_id );
                    else
                        $customer_info = Customers::find( $invoice_info->fk_customer_id );

                    $invoice_payment_type   = $invoice_info->bi_payment_type;
                    $payment_type_info      = PaymentTypes::find($invoice_payment_type);
                    $pt_payment_account     = $payment_type_info->pt_payment_account;


                    $vat_amount = 0;
                    if($bi_vat_id > 0)
                    {
                        $vat_info = VatAccounts::find($bi_vat_id);
                        $vat_amount = $vat_info->av_vat_rate * $total_price/100;

                        $TransactionMovement = new TransactionMovements();
                        $TransactionMovement->fk_tran_id            = $at_id;
                        $TransactionMovement->tm_company_id            = $default_company_id;
                        $TransactionMovement->tm_trans_code         = $bi_invoice_code;
                        $TransactionMovement->tm_ledger_account     = 4427;
                        $TransactionMovement->tm_sub_ledger_account =  4427;
                        $TransactionMovement->tm_ledger_label       = $bi_invoice_code;
                        $TransactionMovement->tm_debit              = 0;
                        $TransactionMovement->tm_credit             = $vat_amount;
                        $TransactionMovement->tm_creation_date      = date("Y-m-d");
                        $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                        $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                        $TransactionMovement->save();


                        $TransactionMovement = new TransactionMovements();
                        $TransactionMovement->fk_tran_id            = $at_id;
                        $TransactionMovement->tm_company_id            = $default_company_id;
                        $TransactionMovement->tm_trans_code         = $bi_invoice_code;
                        $TransactionMovement->tm_ledger_account     = 701;
                        $TransactionMovement->tm_sub_ledger_account =  701;
                        $TransactionMovement->tm_ledger_label       = $bi_invoice_code;
                        $TransactionMovement->tm_debit              = 0;
                        $TransactionMovement->tm_credit             = $vat_amount;
                        $TransactionMovement->tm_creation_date      = date("Y-m-d");
                        $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                        $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                        $TransactionMovement->save();

                    }





                    if( $item_type == 2 )
                    {
                        // Save Service Income for all servbice items inside the invoice
                        $lst_invoice_items =  InvoiceProducts::whereFkInvoiceId($bi_id)->get();
                        $total_price = 0;
                        foreach ( $lst_invoice_items as $key => $ii_info )
                        {
                            $item_id = $ii_info->ii_item_id;

                            $ii_supplier_id = $ii_info->ii_supplier_id;
                            $service_info = CRMServices::find($item_id);

                            // get the payment method if it's selected

                            $ii_payment_type_id = $ii_info->ii_payment_type_id;
                            if($ii_payment_type_id > 0)
                            {
                                $pt_info      = PaymentTypes::find($ii_payment_type_id);
                                $pt_payment_account     = $pt_info->pt_payment_account;

                            }

                            if($service_info != null)
                            {
                                $sales_account_id    = $service_info->cs_sale_accounting_code;
                                $purchase_account_id = 713;

                                // check if the user has a record by payment type to get account else we get the default
                                $sptype_data = CRMServicesPaymentTypes::whereStServiceId($item_id)->whereStPaymentTypeId($invoice_payment_type)->get();
                                if(count($sptype_data) > 0)
                                {
                                    foreach ($sptype_data as $key => $type_info)
                                    {
                                        // save sales and purchase account id
                                        $sales_account_id       = $type_info->st_account_income_id;
                                        $purchase_account_id    = $type_info->st_account_purchase_Id;
                                    }
                                }


                                $TransactionMovement = new TransactionMovements();
                                $TransactionMovement->fk_tran_id            = $at_id;
                                $TransactionMovement->tm_company_id            = $default_company_id;
                                $TransactionMovement->tm_trans_code         = $bi_invoice_code;
                                $TransactionMovement->tm_ledger_account     = $customer_info->ic_account_number;
                                $TransactionMovement->tm_sub_ledger_account =  $customer_info->ic_account_number;
                                $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $bi_invoice_note;
                                $TransactionMovement->tm_debit              = ( $ii_info->ii_item_price + $vat_amount);
                                $TransactionMovement->tm_credit             = 0;
                                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                                $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                                $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                                $TransactionMovement->save();

                                if($ii_supplier_id > 0 )
                                {
                                    $supplier_info = Suppliers::find($ii_supplier_id);
                                    $TransactionMovement = new TransactionMovements();
                                    $TransactionMovement->fk_tran_id            = $at_id;
                                    $TransactionMovement->tm_company_id            = $default_company_id;
                                    $TransactionMovement->tm_trans_code         = $bi_invoice_code;
                                    $TransactionMovement->tm_ledger_account     = $supplier_info->ss_sale_account_id;
                                    $TransactionMovement->tm_sub_ledger_account = $supplier_info->ss_sale_account_id;
                                    $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $bi_invoice_note;
                                    $TransactionMovement->tm_debit              = 0;
                                    $TransactionMovement->tm_credit             = $ii_info->ii_item_price;
                                    $TransactionMovement->tm_creation_date      = date("Y-m-d");
                                    $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                                    $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                                    $TransactionMovement->save();

                                    $supplier_info = Suppliers::find($ii_supplier_id);
                                    $TransactionMovement = new TransactionMovements();
                                    $TransactionMovement->fk_tran_id            = $at_id;
                                    $TransactionMovement->tm_trans_code         = $bi_invoice_code;
                                    $TransactionMovement->tm_company_id            = $default_company_id;
                                    $TransactionMovement->tm_ledger_account     = 622;
                                    $TransactionMovement->tm_sub_ledger_account = 622;
                                    $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $bi_invoice_note;
                                    $TransactionMovement->tm_debit              = $ii_info->ii_item_price;
                                    $TransactionMovement->tm_credit             = 0;
                                    $TransactionMovement->tm_creation_date      = date("Y-m-d");
                                    $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                                    $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                                    $TransactionMovement->save();
                                }

                                if($ii_info->ii_cost_price > 0)
                                {
                                    $TransactionMovement = new TransactionMovements();
                                    $TransactionMovement->fk_tran_id            = $at_id;
                                    $TransactionMovement->tm_company_id            = $default_company_id;
                                    $TransactionMovement->tm_trans_code         = $bi_invoice_code;
                                    $TransactionMovement->tm_ledger_account     = $purchase_account_id;
                                    $TransactionMovement->tm_sub_ledger_account = $purchase_account_id;
                                    $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $bi_invoice_note;
                                    $TransactionMovement->tm_debit              = 0;
                                    $TransactionMovement->tm_credit             = $ii_info->ii_cost_price;
                                    $TransactionMovement->tm_creation_date      = date("Y-m-d");
                                    $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                                    $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                                    $TransactionMovement->save();

                                }
                                $total_price += $ii_info->ii_item_price;
                            }
                        }
                    }
                    else
                    {

                        $account_id = 0;
                        if($fk_customer_id == null)
                        {
                            $account_id = $client_info->ca_accounting_id;
                        }
                        else
                        {
                            $account_id = $customer_info->ic_account_number;
                        }


                        $TransactionMovement = new TransactionMovements();
                        $TransactionMovement->fk_tran_id            = $at_id;
                        $TransactionMovement->tm_company_id            = $default_company_id;
                        $TransactionMovement->tm_trans_code         = $bi_invoice_code;
                        $TransactionMovement->tm_ledger_account     = $account_id;
                        $TransactionMovement->tm_sub_ledger_account = $account_id;
                        $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $bi_invoice_note;
                        $TransactionMovement->tm_debit              = $total_price + $vat_amount;
                        $TransactionMovement->tm_credit             = 0;
                        $TransactionMovement->tm_creation_date      = date("Y-m-d");
                        $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                        $TransactionMovement->tm_currency_id        = $bi_invoice_currency;
                        $TransactionMovement->save();


                        $TransactionMovement = new TransactionMovements();
                        $TransactionMovement->fk_tran_id            = $at_id;
                        $TransactionMovement->tm_company_id            = $default_company_id;
                        $TransactionMovement->tm_trans_code         = $bi_invoice_code;
                        $TransactionMovement->tm_ledger_account     = 701;
                        $TransactionMovement->tm_sub_ledger_account = 701;
                        $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $bi_invoice_note;
                        $TransactionMovement->tm_debit              = 0;
                        $TransactionMovement->tm_credit             = $total_price;
                        $TransactionMovement->tm_creation_date      = date("Y-m-d");
                        $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                        $TransactionMovement->tm_currency_id        = $bi_invoice_currency;
                        $TransactionMovement->save();
                    }


                }

                // check if is internal invoice we create purchase order
                if($bi_internal_invoice == 1)
                {
                    $quotation_info = new SupplierQuotations();
                    $quotation_info->fk_supplier_id          = $bi_target_supplier;
                    $quotation_info->sq_user_id              = session('user_id');
                    $quotation_info->sq_company_id              = $bi_company_to;
                    $quotation_info->sq_date_submit          = $bi_invoice_date;
                    $quotation_info->sq_due_date             = $bi_invoice_date;
                    $quotation_info->sq_total_price          = $total_price;
                    $quotation_info->sq_quotation_notes      = $bi_invoice_note;
                    $quotation_info->sq_currency_id          = $bi_invoice_currency;
                    $quotation_info->sq_quotation_approve    = 0;
                    $quotation_info->sq_warehouse_id         = $bi_target_warehouse_id;
                    $quotation_info->sq_container_number         = "";
                    $quotation_info->sq_shipping_type         = 1;
                    $quotation_info->save();


                    foreach ( $lst_invoice_items as $key => $ii_info )
                    {
                        $product_info = Products::find($ii_info->ii_item_id);
                        $quotation_product = new SupplierProducts();
                        $quotation_product->sp_product_serial           = "";
                        $quotation_product->fk_product_id               = $ii_info->ii_item_id;
                        $quotation_product->fk_quotation_id             = $quotation_info->sq_id;
                        $quotation_product->sp_product_name             = $product_info ? $product_info->p_product_name : "";
                        $quotation_product->sp_product_description      = $product_info ? $product_info->p_product_description : "";
                        $quotation_product->sp_product_pruchase_price   = $ii_info->ii_item_price;
                        $quotation_product->sp_product_selling_price    = $ii_info->ii_item_price;
                        $quotation_product->sp_product_wholesale_price  = $ii_info->ii_item_price;
                        $quotation_product->sp_product_discount         = 0;
                        $quotation_product->sp_main_currency            = $bi_invoice_currency;
                        $quotation_product->sp_product_currency         = $bi_invoice_currency;
                        $quotation_product->sp_product_quantity         = $ii_info->ii_item_qyt;
                        $quotation_product->sp_stock_unit               = 1;
                        $quotation_product->sp_stock_unit               = 1;
                        $quotation_product->save();
                    }

                }


                $invoice_info = Invoices::find($bi_id);
                $invoice_info->bi_invoice_status = 1;
                $invoice_info->save();

                // get all serial numbers used for this stock and

            }
        }
        $result_array['is_error'] = 0;
        $result_array['bi_id'] = $bi_id;
        $result_array['action'] = $action;
        $result_array['error_msg'] = "Operation Completed Successfully";
        return Response()->json($result_array);
    }


    /**
     * get account information and return in the result_arrray
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response $result_array
     * $result_array['is_error']
     * $result_array['account_info']
     * $result_array['error_msg']
     */
    public function GetAccountInfo(Request $request)
    {
        $result_array = array();
        $bi_account_number = $request->input('bi_account_number');

        $client_info = CRMAccounts::where('ca_account_code','=', $bi_account_number)->where('ca_is_deleted',0)->get();

        if(count($client_info) == 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'Client Information Not Exist';

            return Response()->json($result_array);
        }


        $result_array['is_error'] = 0;
        $result_array['account_info'] = array(
            'account_id' => $client_info[0]->ca_id,
            'ca_account_name' => $client_info[0]->ca_account_name,
            'ca_account_phone' => $client_info[0]->ca_account_phone,
            'ca_account_mobile' => $client_info[0]->ca_account_mobile,
            'ca_client_address' => $client_info[0]->ca_billing_address
        );

        return Response()->json($result_array);
    }


    public function RevertInvoiceDraft(Request $request)
    {
        $bi_id = $request->input('bi_id');
        $invoice_info = Invoices::find($bi_id);
        $at_id = $invoice_info->bi_transaction_id;
        $invoice_info->bi_invoice_status    = 0;
        $invoice_info->bi_transaction_id    = 0;
        $invoice_info->save();
        $result_array = array();



        $transaction_movement = TransactionMovements::whereFkTranId($at_id)->delete();

        $transaction_info = Transactions::whereAtId($at_id)->delete();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Completed Successfully";
        return Response()->json($result_array);
    }

    /**
     * COnvert Invoice from draft to official by change the field of bi_invoice_status flag to 1
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function ConvertInvoiceToOfficial(Request $request)
    {


        $bi_id = $request->input('bi_id');
        $invoice_info = Invoices::find($bi_id);

        $bi_invoice_code = $invoice_info->bi_invoice_code;
        $bi_invoice_note    = $invoice_info->bi_invoice_note;
        // get account of payment type
        $invoice_payment_type   = $invoice_info->bi_payment_type;
        $payment_type_info      = PaymentTypes::find($invoice_payment_type);
        $pt_payment_account     = $payment_type_info->pt_payment_account;
        $default_company_id = session('default_company_id');


        $second_currency        = $invoice_info->bi_second_currency;
        $second_exchange_rate   = $invoice_info->bi_exchange_rate;


        // add transaction record
        $AccTransaction = new Transactions();
        $AccTransaction->at_transaction_date    = $invoice_info->bi_invoice_date;
        $AccTransaction->at_creation_date       = date("Y-m-d");
        $AccTransaction->at_accounting_doc      = $invoice_info->bi_invoice_code;
        $AccTransaction->fk_acc_journal_id      = 3;
        $AccTransaction->save();
        $at_id = $AccTransaction->at_id;


        $customer_info = new Customers();
        $client_info = new CRMAccounts();

        //get information of the customer
        $account_id = 0;
        if(Config::get('appconfig.crm_telemarketing') == 0)
        {
            $customer_info = Customers::find( $invoice_info->fk_customer_id );
            $account_id = $customer_info->ic_account_number;
        }
        else{
            $client_info = CRMAccounts::find( $invoice_info->bi_client_id  );
            $account_id = $client_info->fk_account_id;
        }




        // remove tags from description
        $bi_invoice_note = strip_tags($invoice_info->bi_invoice_note);


        // check number of receipts inside the invoice based on that
        // we create number of record inside the customer account
        $lst_receipts = Receipts::whereFkInvoiceId($bi_id)->get();
        $lst_payments = InvoicePayments::whereFkInvoiceId($bi_id)->count();

        if($lst_payments > 0)
        {
            foreach ($lst_receipts as $key => $receipt_info ) {
                $receipt_amount = $receipt_info->br_payment_value;
                $br_receipt_currency    = $receipt_info->br_receipt_currency;
                $br_second_currency_id  = $receipt_info->br_second_currency_id;
                $br_exchange_rate       = $receipt_info->br_exchange_rate;

                if($br_second_currency_id > 0 )
                {
                    $receipt_amount = $receipt_amount * $br_exchange_rate;
                    $br_receipt_currency = $br_second_currency_id;
                }

                if($receipt_amount == 0)
                    continue;
                    $TransactionMovement = new TransactionMovements();

                    $TransactionMovement->tm_company_id            = $default_company_id;
                    $TransactionMovement->fk_tran_id            = $at_id;
                    $TransactionMovement->tm_ledger_account     = $pt_payment_account;
                    $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number;
                    $TransactionMovement->tm_ledger_label       = $invoice_info->bi_invoice_code;
                    $TransactionMovement->tm_debit              = 0;
                    $TransactionMovement->tm_credit             = $receipt_amount;
                    $TransactionMovement->tm_creation_date      = date("Y-m-d");
                    $TransactionMovement->tm_currency_id        = $br_receipt_currency;
                    $TransactionMovement->save();
            }
        }





        // Save Service Income for all servbice items inside the invoice
        $lst_invoice_items =  InvoiceProducts::whereFkInvoiceId($bi_id)->get();
        $total_price = 0;
        $bi_invoice_type = $invoice_info->bi_invoice_items_type;

        if($bi_invoice_type == 2)
        {
            foreach ( $lst_invoice_items as $key => $ii_info )
            {
                $item_id = $ii_info->ii_item_id;
                $ii_supplier_id = $ii_info->ii_supplier_id;
                $service_info = CRMServices::find($item_id);
                $supplier_info = Suppliers::find($ii_supplier_id);

                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->tm_company_id            = $default_company_id;
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_ledger_account     = $pt_payment_account;
                $TransactionMovement->tm_sub_ledger_account =  $service_info->cs_sale_accounting_code;
                $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $service_info->cs_service_title . " " . $bi_invoice_note;
                $TransactionMovement->tm_debit              = 0;
                $TransactionMovement->tm_credit             = $ii_info->ii_item_price;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                $TransactionMovement->save();

                // supplIER RECORDS

                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_company_id            = $default_company_id;
                $TransactionMovement->tm_ledger_account     = $pt_payment_account;
                $TransactionMovement->tm_sub_ledger_account = $supplier_info->ss_sale_account_id;
                $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $service_info->cs_service_title . " " . $bi_invoice_note;
                $TransactionMovement->tm_debit              = 0;
                $TransactionMovement->tm_credit             = $ii_info->ii_cost_price;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                $TransactionMovement->save();

                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_company_id            = $default_company_id;
                $TransactionMovement->tm_ledger_account     = $pt_payment_account;
                $TransactionMovement->tm_sub_ledger_account = $service_info->cs_purchase_accounting_code;
                $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $service_info->cs_service_title . " " . $bi_invoice_note;
                $TransactionMovement->tm_debit              = $ii_info->ii_cost_price;
                $TransactionMovement->tm_credit             = 0;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                $TransactionMovement->save();

                $total_price = $total_price + $ii_info->ii_item_price;

            }
        }
        else
        {
            foreach ( $lst_invoice_items as $key => $ii_info )
            {
                $item_id = $ii_info->ii_item_id;
                $ii_supplier_id = $ii_info->ii_supplier_id;
                $product_info = Products::find($item_id);
                $supplier_info = Suppliers::find($ii_supplier_id);

                $TransactionMovement = new TransactionMovements();
                $TransactionMovement->fk_tran_id            = $at_id;
                $TransactionMovement->tm_company_id            = $default_company_id;
                $TransactionMovement->tm_ledger_account     = $pt_payment_account;
                $TransactionMovement->tm_sub_ledger_account =  0;
                $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " Credit For Invoice " . $bi_invoice_note;
                $TransactionMovement->tm_debit              = 0;
                $TransactionMovement->tm_credit             = $ii_info->ii_item_price;
                $TransactionMovement->tm_creation_date      = date("Y-m-d");
                $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                $TransactionMovement->save();


                $total_price = $total_price + $ii_info->ii_item_price;

            }


            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_company_id            = $default_company_id;
            $TransactionMovement->tm_ledger_account     = 701;
            $TransactionMovement->tm_sub_ledger_account = 701;
            $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " Credit For Invoice " . $bi_invoice_note;
            $TransactionMovement->tm_debit              = 0;
            $TransactionMovement->tm_credit             = $total_price;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_currency_id        = $invoice_info->bi_invoice_currency;
            $TransactionMovement->save();

            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_company_id            = $default_company_id;
            $TransactionMovement->tm_ledger_account     = 53;
            $TransactionMovement->tm_sub_ledger_account =  53;
            $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " Credit For Invoice " . $bi_invoice_note;
            $TransactionMovement->tm_debit              = $total_price;
            $TransactionMovement->tm_credit             = 0;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_currency_id        = $invoice_info->bi_invoice_currency;
            $TransactionMovement->save();
        }



        if($lst_payments ==0)
        {

            $invoice_currency = $invoice_info->bi_invoice_currency;
            if($second_currency > 0)
            {
                $total_second_price= $total_price * $second_exchange_rate;
                $invoice_currency = $second_currency;
            }

            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_company_id            = $default_company_id;
            $TransactionMovement->tm_ledger_account     = $pt_payment_account;
            $TransactionMovement->tm_sub_ledger_account = $account_id;
            $TransactionMovement->tm_ledger_label       = strip_tags($bi_invoice_note);
            $TransactionMovement->tm_debit              = $total_price;
            $TransactionMovement->tm_credit             = 0;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_currency_id        = $invoice_currency;
            $TransactionMovement->save();
        }



        $invoice_info->bi_transaction_id = $at_id;
        $invoice_info->bi_total_price= $total_price;


        $invoice_info->bi_invoice_status    = 1;
        $invoice_info->bi_transaction_id    = $at_id;
        $invoice_info->save();

        try {
            // 1. Wrap the entire operation in a database transaction for safety.
            DB::transaction(function () use ($bi_id) {
                $invoice_items = InvoiceProducts::whereFkInvoiceId($bi_id)->get();
                $invoice_info = Invoices::find($bi_id);
                foreach ($invoice_items as $item) {
                    // --- SCENARIO A: Item is tracked by a unique serial number ---
                    if (!empty($item->ii_product_serial_number)) {

                        $stock_serial = StockIds::where('si_stock_uid', $item->ii_product_serial_number)
                            ->lockForUpdate() // Lock this row to prevent race conditions
                            ->first();

                        // Ensure the serial exists and has not already been marked as sold
                        if (!$stock_serial || $stock_serial->si_stock_sold) {
                            throw new \Exception("Serial number '{$item->ii_product_serial_number}' not found or already sold.");
                        }

                        // Mark the specific serial number as sold
                        $stock_serial->si_stock_sold = 1;
                        $stock_serial->save();

                        // Atomically decrement the quantity in the parent stock record
                        Stocks::where('is_id', $stock_serial->fk_stock_id)->decrement('is_quanity', 1);


                        $call_product = new InboundCallProducts();
                        $call_product->cp_client_id = $invoice_info->bi_client_id;
                        $call_product->fk_call_id = 0;
                        $call_product->cp_product_id = $item->ii_item_id;
                        $call_product->cp_technician_id = 0;
                        $call_product->cp_warehouse_id = $item->ii_warehouse_id;
                        $call_product->cp_serial_number = $item->ii_product_serial_number;
                        $call_product->cp_quantity = 1;
                        $call_product->cp_total_cost = $item->ii_cost_price;
                        $call_product->cp_total_price = $item->ii_item_price;
                        $call_product->cp_total_price_item = $item->ii_item_price;
                        $call_product->save();

                    }
                    // --- SCENARIO B: Item is tracked by quantity ---
                    else {
                        $quantity_to_deduct = $item->ii_item_qyt;

                        // Find all available stock batches for the product in the specified warehouse
                        $stock_batches = Stocks::where('fk_product_id', $item->ii_item_id)
                            ->where('fk_warehouse_id', $item->ii_warehouse_id)
                            ->where('is_quanity', '>', 0)
                            ->orderBy('is_id', 'asc')
                            ->lockForUpdate() // Lock all found rows
                            ->get();

                        // Check if the total combined stock is sufficient
                        if ($stock_batches->sum('is_quanity') < $quantity_to_deduct) {
                            throw new \Exception("Not enough stock for product ID {$item->ii_item_id} in warehouse {$item->ii_warehouse_id}.");
                        }

                        // Loop through the batches and deduct the required quantity
                        foreach ($stock_batches as $batch) {
                            if ($quantity_to_deduct <= 0) {
                                break; // Stop when the required quantity has been fulfilled
                            }

                            $take_from_this_batch = min($batch->is_quanity, $quantity_to_deduct);
                            $batch->decrement('is_quanity', $take_from_this_batch);
                            $quantity_to_deduct -= $take_from_this_batch;
                        }
                    }

                    $call_product = new InboundCallProducts();
                    $call_product->cp_client_id = $invoice_info->bi_client_id;
                    $call_product->fk_call_id = 0;
                    $call_product->cp_product_id = $item->ii_item_id;
                    $call_product->cp_technician_id = 0;
                    $call_product->cp_warehouse_id = $item->ii_warehouse_id;
                    $call_product->cp_serial_number = "-";
                    $call_product->cp_quantity = $item->ii_item_qyt;
                    $call_product->cp_total_cost = $item->ii_cost_price * $item->ii_item_qyt;
                    $call_product->cp_total_price = $item->ii_item_price * $item->ii_item_qyt;
                    $call_product->cp_total_price_item = $item->ii_item_price;
                    $call_product->save();

                    // save in warehouse movoment log or in database
                    $warehouse_movement = new WareHouseMovement();
                    $warehouse_movement->wm_warehouse_id = $item->ii_warehouse_id;
                    $warehouse_movement->wm_product_id = $item->ii_item_id;
                    $warehouse_movement->wm_quantity = $item->ii_item_qyt;
                    $warehouse_movement->wm_action_date = date('Y-m-d');
                    $warehouse_movement->wm_action_type = "INV";
                    $warehouse_movement->wm_action_description = "Get " . $item->ii_item_qyt . " of " . $item->Product->p_product_name . " From " . $item->warehouse->w_warehouse_name . " Invoice Number #" . $bi_invoice_code;
                    $warehouse_movement->save();

                }
            });



        } catch (\Exception $e) {
            // If any part of the transaction fails, catch the exception and return an error
            $result_array['is_error']   = 1;
            $result_array['error_msg']  = $e->getMessage();
            return response()->json($result_array);
        }



        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Completed Successfully";
        return Response()->json($result_array);
    }

    /**
     * Delete Invoice info from the database]
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteInvoiceInfo(Request $request)
    {
        $bi_id = $request->input('bi_id');

        $BillingInvoices = Invoices::find($bi_id);
        $BillingInvoices->bi_is_deleted = 1;
        $BillingInvoices->bi_deleted_by = session("user_id");
        $BillingInvoices->save();

        $trans_id = $BillingInvoices->bi_transaction_id;


        $delete_trans = Transactions::where('at_id',$trans_id)->delete();
        $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();


        $result_array = array();


        $result_array['is_error']   = 1;
        $result_array['error_msg']  = "Invoice Completly Deleted";
        return Response()->json($result_array);
    }
}
