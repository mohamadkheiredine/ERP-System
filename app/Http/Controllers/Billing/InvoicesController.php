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
use App\Library\AccountsManager;
use App\Library\AccountingManager;
use Models\Account;
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
use Dompdf\Dompdf;
use App\models\CRM\CRMServicesPaymentTypes;



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
        
        $list_accounts      = CRMAccounts::whereCaIsDeleted(0)->get();
        $list_customers     = Customers::whereIcIsDeleted(0)->get();
        $lst_banks_info     = BankAccounts::whereBaIsDeleted(0)->get();
        
        $data = array(
            "list_accounts"     => $list_accounts,
            "list_customers"    => $list_customers,
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
            "currency" => $total_array['currency']
        );
        $result_array['display'] = view('billing.listproducts',$data)->render();
        
        unset($AccountingManager);
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
        
        $pdf = new Dompdf();
        $pdf->loadHTML($display);
        $pdf->render();
        return $pdf->stream('invoice-' . strtolower($invoice_info->bi_invoice_ref) . '.pdf');
        //return $display;
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
        
        $invoice_customer   = $request->input("invoice_customer");
        $invoice_bank       = $request->input("invoice_bank");
        $start_date         = $request->input("start_date");
        $end_date           = $request->input("end_date");
        $page_number        = $request->input("page_number");
        $general_search         = $request->input("general_search");
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        $lst_customers    = Customers::whereIcIsDeleted(0)->get();
        $customers_array  = CreateDatabaseArrayByIndex($lst_customers, "ic_id"); 
        $fisical_year =  $request->input('fisical_year')  !== null ? $request->input('fisical_year') : date("Y");
        
        $strfirstday = 'first day of January ' .$fisical_year;
        $strlastday = 'last day of December ' . $fisical_year;
        
        $firstday = date("Y-m-d",strtotime($strfirstday));
        $lastday = date("Y-m-d",strtotime($strlastday)); 
        
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
            else
                $skip = 0;
                
                
        $lst_invoices   = Invoices::whereBiIsDeleted(0);
        
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
                $lst_invoices = $lst_invoices->whereBetween('bi_invoice_date', [$firstday, $lastday]);
            }
                        
            if(strlen($general_search) > 0)
            {
                $lst_invoices = $lst_invoices->where('bi_invoice_note','LIKE',"%" . $general_search. "%");
            }
            
            $count_invoices =     $lst_invoices->count();
            $total_pages = ceil( $count_invoices/$nbr_rows_per_pages );
            $total_pages = intval($total_pages);
            
            $lst_invoices = $lst_invoices->skip($skip)->take($nbr_rows_per_pages)->get(); 
            
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
        
        $list_accounts      = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_banks_info     = BankAccounts::whereBaIsDeleted(0)->get();
        $lst_payment_types  = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_payment_terms  = PaymentTerms::wherePtIsDeleted(0)->get();
        $lst_vat_accounts   = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_currencies     = Currency::all();
        $list_customers     = Customers::whereIcIsDeleted(0)->get();
        $list_services      = CRMServices::whereCsIsDeleted(0)->get();
        
        $data = array(
            "invoice_code" => $invoice_code,
            "list_accounts" => $list_accounts,
            "list_customers" => $list_customers,
            "lst_banks_info" => $lst_banks_info,
            "lst_payment_types" => $lst_payment_types,
            "lst_payment_terms" => $lst_payment_terms,
            "lst_currencies" => $lst_currencies,
            "list_services" => $list_services,
            "lst_vat_accounts" => $lst_vat_accounts
        );
        return Response()->view("billing.addinvoice",$data);
        
    }
    
    /**
     * Display Edit Receipt Form From
     * @param unknown $br_id
     */
    public function EditIReceiptForm( $br_id )
    {
        
        $receipt_info = Receipts::find($br_id);
        $lst_invoices = Invoices::whereBiIsDeleted(0)->get();
        $lst_customers = Customers::whereIcIsDeleted(0)->get();
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
        $list_accounts      = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_banks_info     = BankAccounts::whereBaIsDeleted(0)->get();
        $lst_payment_types  = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_payment_terms  = PaymentTerms::wherePtIsDeleted(0)->get();
        $lst_products       = Products::wherePProductIsDeleted(0)->get();
        $lst_vat_accounts   = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_currencies     = Currency::all();
        $currencies_array = CreateDatabaseArrayByIndex($lst_currencies, "cc_id");
        $list_customers     = Customers::whereIcIsDeleted(0)->get();
        $list_services      = CRMServices::whereCsIsDeleted(0)->get();
        $lst_suppliers      = Suppliers::whereSsIsDeleted(0)->get();
        
        $data = array(
            "invoice_info" => $invoice_info,
            "list_accounts" => $list_accounts,
            "lst_banks_info" => $lst_banks_info,
            "lst_products" => $lst_products,
            "lst_payment_types" => $lst_payment_types,
            "lst_payment_terms" => $lst_payment_terms,
            "lst_currencies" => $lst_currencies,
            "currencies_array" => $currencies_array,
            "list_customers" => $list_customers,
            "list_services" => $list_services,
            "lst_suppliers" => $lst_suppliers,
            "lst_vat_accounts" => $lst_vat_accounts
        );
        return Response()->view("billing.editinvoice",$data);
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
        $ii_payment_type    = $request->input("ii_payment_type");
        $product_info       = Products::find($bi_product);
        $invoice_info       = Invoices::find($invoice_id);
        $result_array       = array();
        if($item_id == "")
            $invoice_product = new InvoiceProducts();
            else
                $invoice_product = InvoiceProducts::find($item_id);
                
                $invoice_product->fk_invoice_id     = $invoice_id;
                $invoice_product->ii_item_id        = $bi_product;
                $invoice_product->ii_item_type      = $invoice_type_item;
                $invoice_product->ii_item_label     = $product_info->p_product_name;
                $invoice_product->ii_price_currency = $product_info->p_product_currency;
                $invoice_product->ii_item_qyt       = $bi_quanity;
                $invoice_product->ii_payment_type   = $ii_payment_type;
                $invoice_product->ii_item_price     = $product_info->p_product_selling_price;
                $invoice_product->ii_total_price    = $product_info->p_product_selling_price * $bi_quanity;
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
                
                unset($AccountingManager);
                
                $result_array['is_error'] = 0;
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
        $bi_invoice_date        = date("Y-m-d",strtotime($bi_invoice_date));
        $cyear                  = date('Y', strtotime($bi_invoice_date));
  
        
        $fk_bankaccount_id      = $request->input("fk_bankaccount_id");
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
        
        $invoice_info->bi_invoice_ref       = $bi_invoice_ref;
        $invoice_info->bi_invoice_code      = $bi_invoice_code;
        $invoice_info->fk_account_id        = $invoice_account;
        $invoice_info->fk_customer_id       = $fk_customer_id;
        $invoice_info->bi_invoice_date      = $bi_invoice_date;
        $invoice_info->fk_bankaccount_id    = $fk_bankaccount_id;
        $invoice_info->bi_payment_type      = $bi_payment_type;
        $invoice_info->bi_payment_terms     = $bi_payment_terms;
        $invoice_info->bi_invoice_note      = $bi_invoice_note;
        $invoice_info->bi_invoice_type      = $bi_invoice_items_type;
        $invoice_info->bi_invoice_currency  = $bi_invoice_currency;
        $invoice_info->bi_vat_id            = $bi_vat_id;
        $invoice_info->bi_discount          = $bi_discount;
        $invoice_info->bi_second_currency   = $bi_second_currency;
        $invoice_info->bi_exchange_rate     = $bi_exchange_rate;
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
                    $customer_info = Customers::find( $invoice_info->fk_customer_id );
                    
                    $invoice_payment_type   = $invoice_info->bi_payment_type;
                    $payment_type_info      = PaymentTypes::find($invoice_payment_type);
                    $pt_payment_account     = $payment_type_info->pt_payment_account;
                     
                    
                    // Save Service Income for all servbice items inside the invoice
                    $lst_invoice_items =  InvoiceProducts::whereFkInvoiceId($bi_id)->get();
                    $total_price = 0; 
                    foreach ( $lst_invoice_items as $key => $ii_info )
                    { 
                        $item_id = $ii_info->ii_item_id;
                      
                        $ii_supplier_id = $ii_info->ii_supplier_id;
                        $service_info = CRMServices::find($item_id);
                        $supplier_info = Suppliers::find($ii_supplier_id);
                        
                        // get the payment method if it's selected
                        
                        $ii_payment_type_id = $ii_info->ii_payment_type_id;
                        if($ii_payment_type_id > 0)
                        {
                            $pt_info      = PaymentTypes::find($ii_payment_type_id);
                            $pt_payment_account     = $pt_info->pt_payment_account;
                            
                        }
                        
                        
                        $sales_account_id    = $service_info->cs_sale_accounting_code;
                        $purchase_account_id = $service_info->cs_purchase_accounting_code;
                        
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
                        $TransactionMovement->tm_ledger_account     = $pt_payment_account;
                        $TransactionMovement->tm_sub_ledger_account =  $sales_account_id;
                        $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $bi_invoice_note;
                        $TransactionMovement->tm_debit              = 0;
                        $TransactionMovement->tm_credit             = $ii_info->ii_item_price;
                        $TransactionMovement->tm_creation_date      = date("Y-m-d");
                        $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                        $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                        $TransactionMovement->save();  
                        
                        $TransactionMovement = new TransactionMovements();
                        $TransactionMovement->fk_tran_id            = $at_id;
                        $TransactionMovement->tm_ledger_account     = $pt_payment_account;
                        $TransactionMovement->tm_sub_ledger_account = $supplier_info->ss_sale_account_id;
                        $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $bi_invoice_note;
                        $TransactionMovement->tm_debit              = 0;
                        $TransactionMovement->tm_credit             = $ii_info->ii_cost_price;
                        $TransactionMovement->tm_creation_date      = date("Y-m-d");
                        $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                        $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                        $TransactionMovement->save();
                      
                        $TransactionMovement = new TransactionMovements();
                        $TransactionMovement->fk_tran_id            = $at_id;
                        $TransactionMovement->tm_ledger_account     = $pt_payment_account;
                        $TransactionMovement->tm_sub_ledger_account = $purchase_account_id;
                        $TransactionMovement->tm_ledger_label       = $bi_invoice_code . " " . $bi_invoice_note;
                        $TransactionMovement->tm_debit              = $ii_info->ii_cost_price;
                        $TransactionMovement->tm_credit             = 0;
                        $TransactionMovement->tm_creation_date      = date("Y-m-d");
                        $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                        $TransactionMovement->tm_currency_id        = $ii_info->ii_price_currency;
                        $TransactionMovement->save();
                        $total_price += $ii_info->ii_item_price;
                      
                    }
                 
                     
                    $TransactionMovement = new TransactionMovements();
                    $TransactionMovement->fk_tran_id            = $at_id;
                    $TransactionMovement->tm_ledger_account     = $pt_payment_account;
                    $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number;
                    $TransactionMovement->tm_ledger_label       = strip_tags($bi_invoice_note);
                    $TransactionMovement->tm_debit              = $total_price;
                    $TransactionMovement->tm_credit             = 0;
                    $TransactionMovement->tm_creation_date      = date("Y-m-d");
                    $TransactionMovement->tm_transaction_date   = $bi_invoice_date;
                    $TransactionMovement->tm_currency_id        = $bi_invoice_currency;
                    $TransactionMovement->save();
                   
                }
                
                $invoice_info = Invoices::find($bi_id);
                $invoice_info->bi_invoice_status = 1;
                $invoice_info->save();
            }
        } 
        $result_array['is_error'] = 0;
        $result_array['bi_id'] = $bi_id;
        $result_array['action'] = $action;
        $result_array['error_msg'] = "Operation Completed Successfully";
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
        
        //get information of the customer
        $customer_info = Customers::find( $invoice_info->fk_customer_id );
 
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
        foreach ( $lst_invoice_items as $key => $ii_info )
        {
            $item_id = $ii_info->ii_item_id;
            $ii_supplier_id = $ii_info->ii_supplier_id;
            $service_info = CRMServices::find($item_id);
            $supplier_info = Suppliers::find($ii_supplier_id);
            
            $TransactionMovement = new TransactionMovements();
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
        
        if($lst_payments ==0)
        {
            if($second_currency > 0)
            {
                $total_price= $total_price * $second_exchange_rate;
                $invoice_currency = $second_currency;
            }
            
            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $pt_payment_account;
            $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number;
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