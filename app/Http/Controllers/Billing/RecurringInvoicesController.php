<?php
/***********************************************************
RecurringInvoicesController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 18, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

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
use App\models\Billing\RecurringInvoices;
use App\models\Billing\InvoiceTemplates;
use App\models\CRM\CRMAccounts;
use App\models\Inventory\Customers;



class RecurringInvoicesController extends Controller
{
    
    /**
     * Page to Recurring Invoice added to the
     * Database
     *
     * @author Moe mantach
     * @access public
     * @return View
     */
    public function index()
    {
        $lst_templates      = InvoiceTemplates::whereRiIsDeleted(0)->get();
        
        $data = array(
            "lst_templates" => $lst_templates
        );
        return Response()->view('billing.recurringinvoices',$data);
    }
    
    
    
    /**
     * Display list of Recurring Invoices saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {

       
        $page_number                = $request->input("page_number");
        $ri_template_id             = $request->input("ri_template_id");
        $nbr_rows_per_pages         = Config::get('appconfig.max_rows_per_page');
        
        if($page_number > 1)
           $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
           $skip = 0;
                
                
                
        $recinvoices_cond     = RecurringInvoices::whereRIIsDeleted(0);
        
        if($ri_template_id > 0)
            $recinvoices_cond = $recinvoices_cond->where('ri_template_id',$ri_template_id);

            
        $recinv_count =     $recinvoices_cond->count();
        $total_pages = ceil( $recinv_count / $nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $lst_recinvoices   = $recinvoices_cond->skip($skip)->take($nbr_rows_per_pages)->get();
        
         
        
        $data = array( 
            "lst_recinvoices" => $lst_recinvoices, 
        );
        
        $result_array = array();
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("billing.listrecurringinvoices",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Open form of add new Recurring Invoice
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function AddForm()
    {
        
        $lst_templates      = InvoiceTemplates::whereRiIsDeleted(0)->get();
        $lst_accounts      = CRMAccounts::whereRiIsDeleted(0)->get();
        $lst_customers      = Customers::whereRiIsDeleted(0)->get();
        
        $ri_frequencies = array('daily', 'weekly', 'monthly', 'quarterly', 'annually');
        $ri_statuses = array('active', 'paused', 'cancelled');

        $data = array(
            'lst_templates' => $lst_templates,
            'lst_accounts' => $lst_accounts,
            'ri_frequencies' => $ri_frequencies,
            'ri_statuses' => $ri_statuses,
            'lst_customers' => $lst_customers
        );
        
        return Response()->view('billing.addrecurringinvoice',$data);
    }
    
    
    
    
    /**
     * Edit page for selected reccuring invoice 
     * @param unknown $ri_id
     * @return \Illuminate\Http\Response
     */
    public function EditForm( $ri_id )
    {
        
        $lst_templates      = InvoiceTemplates::whereRiIsDeleted(0)->get();
        $lst_accounts      = CRMAccounts::whereRiIsDeleted(0)->get();
        $lst_customers      = Customers::whereRiIsDeleted(0)->get();

        $recurring_invoice = RecurringInvoices::find($ri_id);
              $ri_frequencies = array('daily', 'weekly', 'monthly', 'quarterly', 'annually');
        $ri_statuses = array('active', 'paused', 'cancelled');
        
        $data = array(
            'lst_templates' => $lst_templates,
            'recurring_invoice' => $recurring_invoice,
            'lst_accounts' => $lst_accounts,
            'ri_frequencies' => $ri_frequencies,
            'ri_statuses' => $ri_statuses,
            'lst_customers' => $lst_customers
        );
        
        return Response()->view('billing.editrecurringinvoice',$data);
        
    }
    
    
    
    
    /**
     * Save information of new recurring invoice
     * 
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Json $result_array
     */
    public function SaveRecurringInvoice(Request $request)
    {
        $ri_id                            = $request->input('ri_id');
        $ri_customer_id                   = $request->input('ri_customer_id');
        $ri_template_id                   = $request->input('ri_template_id');
        $ri_account_id                    = $request->input('ri_account_id');
        $ri_frequency                     = $request->input('ri_frequency');
        $ri_recurring_title             = $request->input('ri_recurring_title');
        $ri_recurring_description             = $request->input('ri_recurring_description');
        $ri_next_invoice_date             = $request->input('ri_next_invoice_date');
        $ri_end_date             = $request->input('ri_end_date');
        $ri_status             = $request->input('ri_status');
        $ri_last_generated_date             = $request->input('ri_last_generated_date');
        $ri_is_activate             = $request->has('ri_is_activate') ? 1 : 0;
        $recurring_inv = new RecurringInvoices(); 
        if( $ri_id  > 0 )
        {
            $recurring_inv       = RecurringInvoices::find( $ri_id); 
            $recurring_inv->ri_last_updated_by = session('user_id');
            $recurring_inv->ri_updated_at = date('Y-m-d H:i:s');
        }
        else
        {
            $recurring_inv->ri_created_by = session('user_id');
            $recurring_inv->ri_created_at = date('Y-m-d H:i:s');
        }
        
        $recurring_inv->ri_customer_id                = $ri_customer_id;
        $recurring_inv->ri_template_id                = $ri_template_id;
        $recurring_inv->ri_account_id                = $ri_account_id;
        $recurring_inv->ri_frequency                = $ri_frequency;
        $recurring_inv->ri_next_invoice_date                = $ri_next_invoice_date;
        $recurring_inv->ri_end_date                = $ri_end_date;
        $recurring_inv->ri_status                = $ri_status;
        $recurring_inv->ri_last_generated_date                = $ri_last_generated_date;
        $recurring_inv->ri_is_activate                = $ri_is_activate;
        $recurring_inv->ri_recurring_title                = $ri_recurring_title;
        $recurring_inv->ri_recurring_description                = $ri_recurring_description;
        $recurring_inv->save();
       

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
        
    }
    
    
    
    
    /**
     * Delete Recurring Invoice info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteRecurringInvoiceInfo(Request $request)
    {
        $ri_id  = $request->input('ri_id');
        $result_array = array();
        
        
        
        $recurring_inv= RecurringInvoices::find( $ri_id); 
        $recurring_inv->ri_is_deleted = 1;
        $recurring_inv->ri_deleted_by = session('user_id');
        $recurring_inv->save();
        
        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
    
    
    
}