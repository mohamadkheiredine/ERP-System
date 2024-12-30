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
use App\models\Accounting\BankAccounts;
use App\models\Billing\PaymentTypes;
use App\models\Billing\PaymentVouchers;
use App\models\System\Currency;
use App\models\CRM\CRMAccounts;
use App\models\Inventory\Customers;
use App\library\AccountingManager;
use App\models\Users\Users;
use App\models\Billing\InvoiceTemplates;
use App\models\Billing\PaymentTerms;
use App\models\Accounting\VatAccounts;
use App\models\CRM\CRMServices;
use App\models\Billing\InvoiceTemplateItems;

class InvoiceTemplatesController extends Controller
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
        $list_accounts      = CRMAccounts::whereCaIsDeleted(0)->get();
        $list_customers     = Customers::whereIcIsDeleted(0)->get();
        $lst_banks_info     = BankAccounts::whereBaIsDeleted(0)->get();
        
        $data = array(
            "list_accounts"     => $list_accounts,
            "list_customers"    => $list_customers,
            "lst_banks_info"    => $lst_banks_info
        );
        return Response()->view("billing.invoicetemplates",$data);
    }
    
    
    
    /**
     * Display list of Invoice Templates saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $it_customer_id         = $request->input('it_customer_id'); 
        $it_account_id         = $request->input('it_account_id'); 
        $page_number                = $request->input("page_number");
        $general_search             = $request->input("general_search"); 
        $nbr_rows_per_pages         = Config::get('appconfig.max_rows_per_page');
   
 
         
        
        $templates_cond       = InvoiceTemplates::whereItIsDeleted(0);
      
        if(strlen($general_search) > 0)
        {
            $templates_cond = $templates_cond->where('it_template_label','LIKE','%' . $general_search. '%');
            $templates_cond = $templates_cond->orWhere('it_template_description','LIKE','%' . $general_search. '%');
            $templates_cond = $templates_cond->orWhere('it_template_code','LIKE','%' . $general_search. '%');
        }
        
        
        if($it_customer_id > 0)
        {
             $templates_cond = $templates_cond->whereItCustomerId($it_customer_id);
        }
        
        if($it_account_id > 0)
        {
             $templates_cond = $templates_cond->whereItAccountId($it_account_id);
        }
        
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
       
        $count_templates =  $templates_cond->count();
        $total_pages = ceil( $count_templates/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $lst_templates = $templates_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('it_id','ASC')->get();
         
        
        $data = array(
            "lst_templates" => $lst_templates
        );
        
        $result_array = array();
        
        $result_array['display'] = view("billing.listtemplates",$data)->render();
        $result_array['total_pages'] = $total_pages;
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Display list of template items based on selected invoice template id
     * 
     * @author Moe Mantach
     * @access public 
     * @param Request $request
     */
    public function DisplayListTemplateItems(Request $request)
    {
        $it_id = $request->input('it_id');
        
        $lst_template_items = InvoiceTemplateItems::whereFkTemplateId($it_id)->whereTiIsDeleted(0)->get();
        
        
         $result_array = array();
        $data = array(
           "lst_template_items"  => $lst_template_items
        );
        $result_array['display'] = view("billing.listtemplateitems",$data)->render();
        
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
        
        $AccountingManager = new AccountingManager();
        
        $it_template_code = $AccountingManager->GenerateInvoiceTemplateCode();
        
        $list_accounts      = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_banks_info     = BankAccounts::whereBaIsDeleted(0)->get();
        $lst_payment_types  = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_payment_terms  = PaymentTerms::wherePtIsDeleted(0)->get();
        $lst_vat_accounts   = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_currencies     = Currency::all();
        $list_customers     = Customers::whereIcIsDeleted(0)->get();
        $list_services      = CRMServices::whereCsIsDeleted(0)->get();
        
        $data = array(
            'list_accounts' => $list_accounts,
            'it_template_code' => $it_template_code,
            'lst_banks_info' => $lst_banks_info,
            "lst_payment_types" => $lst_payment_types,
            "lst_payment_terms" => $lst_payment_terms,
            "lst_vat_accounts" => $lst_vat_accounts,
            "lst_currencies" => $lst_currencies,
            "list_customers" => $list_customers,
            "list_services" => $list_services
        );
        return Response()->view('billing.addinvoicetemplate',$data);
    }
    
    /**
     * Add New Invoice template item to template and save information
     * related
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
     public function SaveInvoiceTemplateItemInfo( Request $request )
     {
         $tl_id = $request->input('tl_id');
         $tl_item_id = $request->input('tl_item_id');
         $ti_currency_id = $request->input('ti_currency_id');
         $crm_service = CRMServices::find($tl_item_id);
         
         $template_info = new InvoiceTemplates();
         $template_info->fk_template_id = $tl_id;
         $template_info->tl_item_id = $tl_item_id;
         $template_info->ti_description = $crm_service->cs_service_description;
         $template_info->ti_quantity = 1;
         $template_info->ti_unit_price = $crm_service->cs_service_cost;
         $template_info->ti_total_price = $crm_service->cs_service_cost;
         $template_info->ti_currency_id = $ti_currency_id;
         $template_info->save();
     }
    
    /**
     * get information of selected Account and  and open the edit form fields
     * @param unknown $w_id
     * @return \Illuminate\Http\Response
     */
    public function EditForm( $it_id )
    {
        $invoice_template   = InvoiceTemplates::find( $it_id );
        $AccountingManager = new AccountingManager();
        
        $it_template_code = $AccountingManager->GenerateInvoiceTemplateCode();
        
        $list_accounts      = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_banks_info     = BankAccounts::whereBaIsDeleted(0)->get();
        $lst_payment_types  = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_payment_terms  = PaymentTerms::wherePtIsDeleted(0)->get();
        $lst_vat_accounts   = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_currencies     = Currency::all();
        $list_customers     = Customers::whereIcIsDeleted(0)->get();
        $list_services      = CRMServices::whereCsIsDeleted(0)->get();
        
        $data = array(
            'invoice_template' => $invoice_template,
            'list_accounts' => $list_accounts,
            'lst_banks_info' => $lst_banks_info,
            "lst_payment_types" => $lst_payment_types,
            "lst_payment_terms" => $lst_payment_terms,
            "lst_vat_accounts" => $lst_vat_accounts,
            "lst_currencies" => $lst_currencies,
            "list_customers" => $list_customers,
            "it_template_code" => $it_template_code,
            "list_services" => $list_services
        );
        
        return Response()->view('billing.editinvoicetemplate',$data);
        
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
    public function SaveTemplateInfo(Request $request)
    {
        $it_id                      = $request->input('it_id');
        $it_template_code           = $request->input('it_template_code');
        $it_template_label          = $request->input('it_template_label');
        $it_template_description    = $request->input('it_template_description');
        $it_customer_id             = $request->input('it_customer_id');
        $it_account_id              = $request->input('it_account_id');
        $it_invoice_type            = $request->input('it_invoice_type');
        $it_payment_terms            = $request->input('it_payment_terms');
        $it_payment_type            = $request->input('it_payment_type');
        $it_invoice_note            = $request->input('it_invoice_note');
        $it_total_cost            = $request->input('it_total_cost');
        $it_vat_id                  = $request->input('it_vat_id');
        $it_discount                  = $request->input('it_discount');
        $it_total_price                  = $request->input('it_total_price');
        $it_invoice_currency                  = $request->input('it_invoice_currency');
        
        $invoice_template = new InvoiceTemplates();
        if( $it_id > 0 )
        {
            $invoice_template = InvoiceTemplates::find( $it_id );
            $invoice_template->it_last_updated_by = session('user_id');
            $invoice_template->it_updated_at = date('Y-m-d H:i:s');
        }
        else
        {
            $invoice_template->it_created_by = session('user_id');
            $invoice_template->it_created_at = date('Y-m-d H:i:s');
        }
            
        
        $invoice_template->it_template_code   = $it_template_code;
        $invoice_template->it_template_label   = $it_template_label;
        $invoice_template->it_template_description   = $it_template_description;
        $invoice_template->it_customer_id   = $it_customer_id;
        $invoice_template->it_account_id   = $it_account_id;
        $invoice_template->it_invoice_type   = $it_invoice_type;
        $invoice_template->it_payment_terms   = $it_payment_terms;
        $invoice_template->it_payment_type   = $it_payment_type;
        $invoice_template->it_invoice_note   = $it_invoice_note;
        $invoice_template->it_total_cost   = $it_total_cost;
        $invoice_template->it_vat_id   = $it_vat_id;
        $invoice_template->it_discount   = $it_discount;
        $invoice_template->it_total_price   = $it_total_price;
        $invoice_template->it_invoice_currency   = $it_invoice_currency;
        
         
        
        
        $invoice_template->save();
        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
        
    }
    
    

    
    /**
     * Delete Invoice Template info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteTemplateInfo(Request $request)
    {
        $it_id  = $request->input('it_id');
        $result_array = array();
        
        
        
        $invoice_template   = InvoiceTemplates::find($it_id);
        $invoice_template->it_is_deleted = 1;
        $invoice_template->it_deleted_by = session('user_id');
        $invoice_template->save();
        
        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
    /**
     * Delete Invoice Template Item 
     * @param Request $request
     * @return type
     */
    public function DeleteTemplateInvoiceItem(Request $request)
    {
        $ti_id  = $request->input('ti_id');
        $result_array = array();
        
        
        
        $template_items   = InvoiceTemplateItems::find($ti_id);
        $template_items->ti_is_deleted = 1;
        $template_items->ti_deleted_by = session('user_id');
        $template_items->save();
        
        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
}