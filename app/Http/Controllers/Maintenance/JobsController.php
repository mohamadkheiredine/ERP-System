<?php
/***********************************************************
JobsController.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 5, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


namespace App\Http\Controllers\Maintenance;

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
use App\models\Maintenance\Jobs;
use App\models\Maintenance\JobItems;
use App\models\Maintenance\JobStatus;
use App\models\Users\Users;
use App\models\Inventory\Customers;
use App\models\Inventory\Vendors;
use App\models\System\Currency;
use App\Library\MaintenanceManager;
use App\models\Inventory\Products;
use App\models\CRM\CRMServices;
use Milon\Barcode\DNS1D;
use App\models\Inventory\Stocks;
use App\models\Inventory\StockIds;
use App\models\Billing\PaymentTypes;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\models\System\Companies;




class JobsController extends Controller
{
    
    /**
     * Page to control Order Status Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        
        $lst_job_status = JobStatus::whereJsIsDeleted(0)->get();
        $lst_customers  = Customers::whereIcIsDeleted(0)->get();
        
        $data = array(
            "lst_job_status" => $lst_job_status,
            "lst_customers" => $lst_customers,
        );
        return Response()->view('maintenance.jobs',$data);
    }
    
    
    /**
     * Display list of Maintenance Jobs saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        
        //job_status : job_status , j_due_date : j_due_date , customer_id : customer_id
        
        $job_status     = $request->input('job_status');
        $j_due_date     = $request->input('j_due_date');
        $due_date       = date( "Y-m-d", strtotime($j_due_date)); 
        $customer_id    = $request->input('customer_id');
        
        $lst_jobs = Jobs::whereJIsDeleted(0);
        if( $job_status > 0 )
            $lst_jobs = $lst_jobs->whereJJobStatusId($job_status);
        if( $customer_id > 0 )
            $lst_jobs = $lst_jobs->whereJCustomerId($customer_id);
        if( strlen($j_due_date) > 0 )
            $lst_jobs = $lst_jobs->whereJDueDate($due_date);
        
        $lst_jobs = $lst_jobs->orderBy('j_due_date','DESC')->get();
        
        $data = array(
            "lst_jobs" => $lst_jobs
        );
        
        $result_array = array();
        $result_array['display'] = view("maintenance.listjobs",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Display list of items added to maintenance job
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayListItems( Request $request )
    {
        $job_id         = $request->input('job_id');
        $lst_job_items  = JobItems::whereFkJobId($job_id)->get();
        
        $lst_products   = Products::wherePProductIsDeleted(0)->get();
        $products_array = CreateDatabaseArrayByIndex($lst_products, 'p_id');
        $lst_services   = CRMServices::whereCsIsDeleted(0)->get();
        $services_array = CreateDatabaseArrayByIndex($lst_services, 'cs_id');
        
        $data = array(
            "products_array" => $products_array,
            "lst_job_items" => $lst_job_items,
            "services_array" => $services_array
        );
        
        $result_array = array();
        $result_array['display'] = view("maintenance.listjobitems",$data)->render();
        
        return Response()->json($result_array);
    }
    
    /**
     * Display tems dropdown 
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function Displayitemsdropdown( Request $request )
    {
        $mj_job_maintenance = $request->input('mj_job_maintenance');
        $result_array       = array();
         
        switch ($mj_job_maintenance)
        {
            case "1":
            {
                $lst_products = Products::wherePProductIsDeleted(0)->get();
                $products_array = array();
                
                foreach ($lst_products as $key => $value)
                {
                    if($value->Category->pc_maintenance_category == 1)
                        $products_array[$value->p_id] = $value->p_product_name;
                }
                
                
                $data = array(
                    "html_array" => $products_array,
                    "name" => 'jm_product_items',
                    "id" => 'JM_PRODUCT_ITEMS'
                );
                $result_array['display'] = view('html.dropdown',$data)->render();
                
            }
            break;
            case "2":
            {
                $lst_services = CRMServices::whereCsIsDeleted(0)->get();
                $services_array = array();
                
                foreach ($lst_services as $key => $value)
                {
                    $services_array[$value->cs_id] = $value->cs_service_title;
                }
                
                
                $data = array(
                    "html_array" => $services_array,
                    "name" => 'jm_service_items',
                    "id" => 'JM_SERVICE_ITEMS'
                );
                $result_array['display'] = view('html.dropdown',$data)->render();
            }
            break;
        }
        
        
        return Response()->json($result_array);
        
    }
    
    
   /**
    * Get Service Price of selected Service
    * 
    * @author Moe Mantach
    * @access public
    * @param Request $request
    */
    public function GetServicePrice(Request $request)
    {
        $service_item = $request->input('service_item');
        $service_info = CRMServices::find($service_item);
        $result_array = array();
        
        
        $service_price      = $service_info->cs_cost_per_hour;
        $service_currency   = $service_info->currency->cc_currency_code;
        
        $result_array['is_error']           = 0;
        $result_array['service_price']      = $service_price;
        $result_array['service_currency']   = $service_currency;
    
        return Response()->json($result_array);    
    }
    
    /**
     * Function of Adding a new job information
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $rand_barcode       = rand(10000000,99999999999);
        $bar_code_png       = DNS1D::getBarcodePNG($rand_barcode , "C39+",150 , 50 );
        
        
        $lst_job_status = JobStatus::whereJsIsDeleted(0)->get();
        $lst_users      = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_customers  = Customers::whereIcIsDeleted(0)->get();
        $lst_vendors    = Vendors::whereIvIsDeleted(0)->get();
        $lst_currencies = Currency::all();
         
        
        $main_manager = new MaintenanceManager();
        $job_code = $main_manager->GenerateJobCode();
        
        
        $data = array(
            "rand_barcode" => $rand_barcode,
            "bar_code_png" => $bar_code_png,
            "job_code" => $job_code,
            "lst_job_status" => $lst_job_status,
            "lst_currencies" => $lst_currencies,
            "lst_users" => $lst_users,
            "lst_customers" => $lst_customers,
            "lst_vendors" => $lst_vendors
        ); 
        return view('maintenance.addjob',$data);
    }
    
    
    /**
     * Save Order Order Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveJobDataInfo(Request $request)
    {
        $j_id               = $request->input('j_id');
        $j_job_code         = $request->input('j_job_code'); 
        $bare_code_png      = $request->input('bare_code_png'); 
        $bare_code          = $request->input('bare_code'); 
        $j_job_title        = $request->input('j_job_title'); 
        $j_job_description  = $request->input('j_job_description'); 
        $j_job_total_cost   = $request->input('j_job_total_cost'); 
        $j_due_date         = $request->input('j_due_date');
        $j_due_date         = date("Y-m-d" , strtotime($j_due_date) );
        $j_date_creation    = date("Y-m-d"); 
        $j_job_status_id    = $request->input('j_job_status_id'); 
        $j_currency_id      = $request->input('j_currency_id'); 
        $j_user_id          = $request->input('j_user_id'); 
        $j_customer_id      = $request->input('j_customer_id'); 
        $j_vendor_id        = $request->input('j_vendor_id');
        $lst_job_items      = $request->input('lst_job_items');
        $items_array        = json_decode($lst_job_items);
        
        $action = 'add';
        if($j_id != null)
        {
            $action = 'edit';
        }
        
        $result_array = array();
        
        $job_info  = new Jobs();
        
        if( $j_id != null )
        {
          $job_info = Jobs::find($j_id);
        }
        else 
        {
          $job_info->j_date_creation      = $j_date_creation;
        }
        $job_info->j_job_code           = $j_job_code;
        $job_info->j_job_title          = $j_job_title; 
        $job_info->j_job_description    = $j_job_description;
        $job_info->j_job_total_cost     = $j_job_total_cost;
        $job_info->j_due_date           = $j_due_date;
        $job_info->j_job_status_id      = $j_job_status_id;
        $job_info->j_currency_id        = $j_currency_id;
        $job_info->j_user_id            = $j_user_id;
        $job_info->j_customer_id        = $j_customer_id;
        $job_info->j_vendor_id          = $j_vendor_id;
        $job_info->j_job_barecode       = $bare_code;
        $job_info->j_barecode_img       = $bare_code_png;
        
        $job_info->save();
        
        $j_id = $job_info->j_id;
        
        $job_total_cost = 0;
        
        if($items_array != null)
        {
            foreach ( $items_array as $key => $item_info ) {
                $item_type      = $item_info->item_type;
                $item_id        = ($item_info->service_id == null) ? $item_info->product_id: $item_info->service_id;
                $ji_item_cost   =  $item_info->mj_item_cost;
                $ji_price_item  =  $item_info->mj_item_cost *  $item_info->bi_quanity;
                
                $job_item = new JobItems();
                $job_item->fk_job_id        = $j_id;
                $job_item->ji_item_id       = $item_id;
                $job_item->ji_item_type     = $item_type;
                $job_item->ji_item_cost     =  $ji_item_cost;
                $job_item->ji_currency_id   =  $j_currency_id;
                $job_item->ji_quantity      =  $item_info->bi_quanity;
                $job_item->ji_price_item    =  $ji_price_item;
                $job_item->save();
                
                $job_total_cost = $job_total_cost + $item_info->mj_item_cost *  $item_info->bi_quanity;
            }
        }

        
        $job_info  = Jobs::find($j_id);
        $job_info->j_job_total_cost = $job_total_cost;
        $job_info->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Job Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Job Status Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $j_id
     */
    public function EditForm( $j_id)
    { 
        $lst_job_status = JobStatus::whereJsIsDeleted(0)->get();
        $lst_currencies = Currency::all();
        $lst_users      = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_customers  = Customers::whereIcIsDeleted(0)->get();
        $lst_vendors    = Vendors::whereIvIsDeleted(0)->get();
        $job_info       = Jobs::find($j_id);
        $lst_job_items  = JobItems::whereFkJobId($j_id)->get();
        $job_items_array = array();
        
        foreach ($lst_job_items as $key => $item_info) 
        {
            if($item_info->ji_item_type == 1)
            {
                $product_info = Products::find( $item_info->ji_item_id );
                $product_name = $product_info->p_product_name;
                
                $job_items_array[] = array(
                    'item_type' => $item_info->ji_item_type,
                    'product_id' => $item_info->ji_item_id,
                    'product_name' => $product_name,
                    'mj_item_barecode' => $item_info->ji_item_barecode,
                    'mj_item_cost' => $item_info->ji_item_cost,
                    'bi_quanity' => $item_info->ji_quantity
                );
            }
            else 
            {
                $service_info = CRMServices::find( $item_info->ji_item_id );
                $service_name = $service_info->cs_service_title;
                
                $job_items_array[] = array(
                    'item_type' => $item_info->ji_item_type,
                    'service_id' => $item_info->ji_item_id,
                    'service_name' => $service_name,
                    'mj_item_barecode' => $item_info->ji_item_barecode,
                    'mj_item_cost' => $item_info->ji_item_cost,
                    'bi_quanity' => $item_info->ji_quantity
                );
            }
            
        }
        
      
        
        $item_json = json_encode($job_items_array);
        
        $data = array(
            "job_info" => $job_info,
            "job_items_array" => $job_items_array,
            "item_json" => $item_json,
            "lst_job_status" => $lst_job_status,
            "lst_users" => $lst_users,
            "lst_customers" => $lst_customers,
            "lst_currencies" => $lst_currencies,
            "lst_vendors" => $lst_vendors
        ); 
        return view('maintenance.editjob',$data);
    }
    
    /**
     * Pay Job Order and add records to accounting table
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function PayJobOrder(Request $request)
    {
        $job_id     = $request->input('job_id');
        $job_info   = Jobs::find($job_id);
        $result_array = array();
        
        $customer_id    = $job_info->j_customer_id;
        $vendor_id      = $job_info->j_vendor_id;
        
        $customer_info  = new Customers();
        $vendor_info    = new Vendors();
        
        if( $customer_id != null )
            $customer_info = Customers::find($customer_id);
        if($vendor_id != null)
            $vendor_info = Vendors::find($vendor_id);
        
        
        $account_id = ($customer_id == null) ? $customer_info->ic_account_number : $vendor_info->iv_vendor_account_id;
            
        $job_total_cost = $job_info->j_job_total_cost;
            
            
        $payment_type_info      = PaymentTypes::find(2);
        $pt_payment_account     = $payment_type_info->pt_payment_account;
        
        $AccTransaction = new Transactions();
        $AccTransaction->at_transaction_date    = $job_info->j_date_creation;
        $AccTransaction->at_creation_date       = date("Y-m-d");
        $AccTransaction->at_accounting_doc      = "Transaction of job Maintenance " . $job_info->j_job_code;
        $AccTransaction->fk_acc_journal_id      = 3;
        $AccTransaction->save();
        $at_id = $AccTransaction->at_id;
        
        $TransactionMovement = new TransactionMovements();
        $TransactionMovement->fk_tran_id            = $at_id;
        $TransactionMovement->tm_ledger_account     = $customer_info->ic_account_number;
        $TransactionMovement->tm_sub_ledger_account = $pt_payment_account;
        $TransactionMovement->tm_ledger_label       = "Debit Movement of job Maintenance " . $job_info->j_job_code;
        $TransactionMovement->tm_debit              = $job_total_cost;
        $TransactionMovement->tm_credit             = 0;
        $TransactionMovement->tm_creation_date      = date("Y-m-d");
        $TransactionMovement->tm_currency_id        = $job_info->j_currency_id;
        $TransactionMovement->save();
        
        
        $TransactionMovement = new TransactionMovements();
        $TransactionMovement->fk_tran_id            = $at_id;
        $TransactionMovement->tm_ledger_account     = $pt_payment_account;
        $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number;
        $TransactionMovement->tm_ledger_label       = "Credit Movement of job Maintenance " . $job_info->j_job_code;
        $TransactionMovement->tm_debit              = 0;
        $TransactionMovement->tm_credit             = $job_total_cost;
        $TransactionMovement->tm_creation_date      = date("Y-m-d");
        $TransactionMovement->tm_currency_id        = $job_info->j_currency_id;
        $TransactionMovement->save();
        
        $job_info   = Jobs::find($job_id);
        $job_info->j_order_paid = 1;
        $job_info->save();
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "operation completed successfully";
        
        return Response()->json($result_array);
    }
    
    /**
     * Get list of jobs already done for this product
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetProductLog(Request $request)
    {
        $barcode = $request->input('barcode');
        $uid_info = StockIds::whereSiStockUid($barcode)->get();
        $result_array = array();
        
        $stock_id = 0;
        foreach ($uid_info as $key => $stock_info) {
            $stock_id = $stock_info->si_stock_id;
        }
        
        $stock_info = Stocks::find($stock_id);
        $lst_job_logs = Jobs::whereJIsDeleted(0)->get();
        
        $data = array(
            "lst_job_logs" => $lst_job_logs
        );
        $result_array['display'] = view('maintenance.displayjoblogs',$data)->render();
        return Response()->json($result_array);
    }
    
    /**
     * Delete job information by changing j_is_deleted flag
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteJobDataInfo(Request $request)
    {
        
        $j_id= $request->input('j_id');
        
        $job_info = Jobs::find( $j_id);
        $job_info->j_is_deleted          = 1;
        $job_info->j_deleted_by          = Session('user_id');
        $job_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Print job order 
     * @param unknown $j_id
     */
    public function PrintJobOrder( $j_id )
    {
        {/** initialization block "define variables and database object" */
            $job_info       = Jobs::find($j_id);
            $customer_id    = $job_info->j_customer_id;
            $j_vendor_id    = $job_info->j_vendor_id;
            $company_id     = session('company_id');
            $company_logo   = session('company_logo');
            $customer_info  = new Customers();
            $vendor_info    = new Vendors();
           
        }
        
        {/** data retrivele block "find all data required for this view" */
            $list_job_items = JobItems::whereFkJobId($j_id)->get();
            $company_info   = Companies::find($company_id);
            
            if($customer_id != 0)
                $customer_info = Customers::find($customer_id);
            if($j_vendor_id != 0)
                $vendor_info = Vendors::find($j_vendor_id);
                
                
            $client_name      = ( $customer_id != 0 ) ? $customer_info->ic_customer_name : $vendor_info->iv_vendor_name;
            $client_address   = ( $customer_id != 0 ) ? $customer_info->ic_customer_address : $vendor_info->iv_vendor_address;
            $client_email     = ( $customer_id != 0 ) ? $customer_info->ic_customer_email : $vendor_info->iv_vendor_email;
            $client_phone     = ( $customer_id != 0 ) ? $customer_info->ic_customer_phone : $vendor_info->iv_vendor_phone;
            
        }
        


        
        $data = array(
            'job_info' => $job_info,
            'customer_info' => $customer_info,
            'vendor_info' => $vendor_info,
            'company_logo' => $company_logo,
            'company_info' => $company_info,
            'client_name' => $client_name,
            'client_address' => $client_address,
            'client_email' => $client_email,
            'client_phone' => $client_phone,
            'list_job_items' => $list_job_items
        );
        return Response()->view('maintenance.joborder',$data);
    }
    
    
    /**
     * print job request that should be with the client before the job finish
     * 
     * @author Moe mantach
     * @access public
     * @param unknown $j_id
     * @return unknown
     */
    public function PrintJobRequest( $j_id )
    {
        {//initialisation block // define all the variables and database objects
            $job_info       = Jobs::find($j_id);
            $customer_id    = $job_info->j_customer_id;
            $j_vendor_id    = $job_info->j_vendor_id;
            $company_id     = session('company_id');
            $company_logo   = session('company_logo');
            $customer_info  = new Customers();
            $vendor_info    = new Vendors();
        }
        
        {// data retrivel block 
            $company_info   = Companies::find($company_id);
            if($customer_id != 0)
              $customer_info = Customers::find($customer_id);
            if($j_vendor_id != 0)
              $vendor_info = Vendors::find($j_vendor_id);
            
              
              $client_name      = ( $customer_id != 0 ) ? $customer_info->ic_customer_name : $vendor_info->iv_vendor_name;
              $client_address   = ( $customer_id != 0 ) ? $customer_info->ic_customer_address : $vendor_info->iv_vendor_address;
              $client_email     = ( $customer_id != 0 ) ? $customer_info->ic_customer_email : $vendor_info->iv_vendor_email;
              $client_phone     = ( $customer_id != 0 ) ? $customer_info->ic_customer_phone : $vendor_info->iv_vendor_phone;
        }
        
 
        $data = array(
            'job_info' => $job_info,
            'customer_info' => $customer_info,
            'vendor_info' => $vendor_info,
            'company_logo' => $company_logo,
            'company_info' => $company_info,
            'client_name' => $client_name,
            'client_address' => $client_address,
            'client_email' => $client_email,
            'client_phone' => $client_phone
        );
        return Response()->view('maintenance.jobrequest',$data);
    }
}