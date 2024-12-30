<?php
/***********************************************************
InboundController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\Http\Controllers\CallCenter;

use App;
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
use App\models\CostCenter\Categories;
use App\models\CostCenter\CostCenters;
use App\models\CostCenter\CostCenterTypes;
use App\models\CostCenter\CostCenterStatus;
use App\models\Users\Users;
use App\models\Users\UserTypes;
use App\models\CallCenter\InboundCall;
use App\models\CallCenter\MaintenanceTypes;
use App\models\CRM\CRMAccounts;
use App\models\Inventory\Customers;
use App\models\Inventory\Products;
use App\models\Billing\PaymentTypes;
use App\models\System\Currency;
use App\models\CallCenter\CallResults;
use App\models\CallCenter\CallResultsWorkflow;


class InboundController extends Controller
{
    
  
    /**
     * Page to control Inbound Calls Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        
        $lst_telemarketings = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_maint_types = MaintenanceTypes::whereMtIsDeleted(0)->get();
        $lst_payment_types = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_currencies = Currency::all();
        $lst_results = CallResults::whereCrIsDeleted(0)->get();
        $lst_technicians = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TECHNICIAN)->get();
        
        $data = array(
            "lst_telemarketings" => $lst_telemarketings,
            "lst_technicians" => $lst_technicians,
            "lst_payment_types" => $lst_payment_types,
            "lst_currencies" => $lst_currencies,
            "lst_results" => $lst_results,
            "lst_maint_types" => $lst_maint_types
        );
        return Response()->view('callcenter.inboundcalls',$data);
    }
    
    
    /**
     * Display list of Inbound call saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
   
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $ic_technician_id           = $request->input('ic_technician_id');
        $ic_maintenance_type           = $request->input('ic_maintenance_type');
        $ic_call_date           = $request->input('ic_call_date');
        $ic_archived_call           = $request->input('ic_archived_call');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
            
        $inboundcall_cond = InboundCall::whereIcIsDeleted(0);

       
        
        if(strlen($general_search) > 0)
        {
            $inboundcall_cond = $inboundcall_cond->where('ic_call_outcome','LIKE','%' . $general_search . '%');
            $inboundcall_cond = $inboundcall_cond->orWhere('ic_notes','LIKE','%' . $general_search . '%');
            $inboundcall_cond = $inboundcall_cond->orWhere('ic_resolution_notes','LIKE','%' . $general_search . '%');
            $inboundcall_cond = $inboundcall_cond->orWhere('ic_client_code','LIKE','%' . $general_search . '%');
            $inboundcall_cond = $inboundcall_cond->orWhere('ic_contract_code','LIKE','%' . $general_search . '%');
        }
        
        if(strlen($ic_technician_id) > 0 )
        {
             $inboundcall_cond = $inboundcall_cond->where('ic_technician_id','=',$ic_technician_id);
        }
        
        if(strlen($ic_maintenance_type) > 0 )
        {
             $inboundcall_cond = $inboundcall_cond->where('ic_maintenance_type','=',$ic_maintenance_type);
        }
        
        if(strlen($ic_call_date) > 0 )
        {
             $inboundcall_cond = $inboundcall_cond->where('ic_call_date','<=',$ic_call_date);
        }
         
            
        if(strlen($ic_archived_call) > 0 )
        {
             $inboundcall_cond = $inboundcall_cond->where('ic_issue_resolved','=',$ic_archived_call);
        }
         
        $inboundcall_count = $inboundcall_cond->count();


        $total_pages = ceil( $inboundcall_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_inboundcall_info = $inboundcall_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('ic_id', 'asc')->get();

        $data = array(
            "lst_inboundcall_info" => $lst_inboundcall_info
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("callcenter.listinbound",$data)->render();

        return Response()->json($result_array);
    }
    
    
    /**
     * get list of current call result and display it inside pop
     * @param type $request
     */
    public function GetListCallResul(Request $request)
    {
        $ic_id = $request->input('ic_id');
        $result_array = array();
        
        $lst_result_workflow = CallResultsWorkflow::whereCwIsDeleted(0)->whereCwCallId($ic_id)->orderBy('cw_creation_date','DESC')->get();
        $data = array(
            "lst_result_workflow" => $lst_result_workflow
        );
        $result_array['is_error'] = 0;
        $result_array['display'] = view('callcenter.listcallresults',$data)->render();
        return Response()->json($result_array);
    }
    
    
    public function SaveCallResultInfo(Request $request)
    {
        $ic_call_id           = $request->input('ic_call_ids');
        $cw_result_id           = $request->input('cw_result_id');
        $cw_creation_date           = $request->input('cw_creation_date');
        $cw_callback_date           = $request->input('cw_callback_date');
        $cw_assigned_to           = $request->input('cw_assigned_to');
        $cw_result_note           = $request->input('cw_result_note');
       
        $result_array = array();
        $result_info = new CallResultsWorkflow();
        $result_info->cw_call_id = $ic_call_id;
        $result_info->cw_result_id = $cw_result_id;
        $result_info->cw_creation_date = $cw_creation_date;
        $result_info->cw_assigned_to = $cw_assigned_to;
        $result_info->cw_result_note = $cw_result_note;
        if($cw_result_id == 1)
            $result_info->cw_callback_date = $cw_callback_date;
        $result_info->save();
        
        
        $call_info = InboundCall::find($ic_call_id);
        $call_info->ic_result_id = $cw_result_id;
        $call_info->save();
        
        $result_array['is_error'] = 0;
        
        return Response()->json($result_array);
    }
    
    public function GenerateAndDownloadList(Request $request)
    {
       $general_search         = $request->input('general_search');
        $ic_technician_id           = $request->input('ic_technician_id');
        $ic_maintenance_type           = $request->input('ic_maintenance_type');
        $ic_call_date           = $request->input('ic_call_date');
        $ic_archived_call           = $request->input('ic_archived_call');
            
        $inboundcall_cond = InboundCall::whereIcIsDeleted(0);

       
        
        if(strlen($general_search) > 0)
        {
            $inboundcall_cond = $inboundcall_cond->where('ic_call_outcome','LIKE','%' . $general_search . '%');
            $inboundcall_cond = $inboundcall_cond->orWhere('ic_notes','LIKE','%' . $general_search . '%');
            $inboundcall_cond = $inboundcall_cond->orWhere('ic_resolution_notes','LIKE','%' . $general_search . '%');
            $inboundcall_cond = $inboundcall_cond->orWhere('ic_client_code','LIKE','%' . $general_search . '%');
            $inboundcall_cond = $inboundcall_cond->orWhere('ic_contract_code','LIKE','%' . $general_search . '%');
        }
        
        if(strlen($ic_technician_id) > 0 )
        {
             $inboundcall_cond = $inboundcall_cond->where('ic_technician_id','=',$ic_technician_id);
        }
        
        if(strlen($ic_maintenance_type) > 0 )
        {
             $inboundcall_cond = $inboundcall_cond->where('ic_maintenance_type','=',$ic_maintenance_type);
        }
        
        if(strlen($ic_call_date) > 0 )
        {
             $inboundcall_cond = $inboundcall_cond->where('ic_call_date','<=',$ic_call_date);
        }
        
        if(strlen($ic_archived_call) > 0 )
        {
             $inboundcall_cond = $inboundcall_cond->where('ic_issue_resolved','=',$ic_archived_call);
        }
         
       


        $inboundcall_count = $inboundcall_cond->count();



        $lst_inboundcall_info = $inboundcall_cond->orderBy('ic_id', 'asc')->get();

        $data = array(
            "lst_inboundcall_info" => $lst_inboundcall_info
        );
        
        $contract_document = view('templates.lstcalls',$data)->render();
        
         
      
 
       $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($contract_document);
        return $pdf->inline();
    }
    
    
    
    /**
     * Function of Adding a new Cost Center category
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    { 
        $lst_clients = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_products = Products::wherePProductIsDeleted(0)->get();
        $lst_maint_types = MaintenanceTypes::whereMtIsDeleted(0)->get();
        $lst_technicians = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TECHNICIAN)->get();
        $lst_results = CallResults::whereCrIsDeleted(0)->get();
        
        $count_calls = InboundCall::whereIcIsDeleted(0)->count() + 1;
        $ic_call_index = str_pad($count_calls, 7, '0', STR_PAD_LEFT);
        
        $data = array(
            "lst_clients" => $lst_clients,
            "lst_products" => $lst_products,
            "lst_maint_types" => $lst_maint_types,
            "lst_technicians" => $lst_technicians,
            "lst_sales" => $lst_sales,
            "lst_results" => $lst_results,
            "ic_call_index" => $ic_call_index,
        );
        return view('callcenter.addinboundcall',$data);
    }
    
    
    /**
     * Save Maintenance Voucher Information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveMaintenanceVoucherInfo(Request $request)
    {
        $result_array = array();
        $ic_ids = $request->input('ic_ids');
        $ic_resolution_date = $request->input('ic_resolution_date');
        $ic_doc_number = $request->input('ic_doc_number');
        $ic_call_index = $request->input('ic_call_index');
        $ic_comission = $request->input('ic_comission');
        $ic_payment_type = $request->input('ic_payment_type');
        $ic_visit_price = $request->input('ic_visit_price');
        $ic_currency_id = $request->input('ic_currency_id');
        
        $voucher_info = InboundCall::find($ic_ids);
        $voucher_info->ic_resolution_date = $ic_resolution_date;
        $voucher_info->ic_doc_number = $ic_doc_number;
        $voucher_info->ic_call_index = $ic_call_index;
        $voucher_info->ic_comission = $ic_comission;
        $voucher_info->ic_payment_type = $ic_payment_type;
        $voucher_info->ic_visit_price = $ic_visit_price;
        $voucher_info->ic_currency_id = $ic_currency_id;
        $voucher_info->ic_issue_resolved = 1;
        
        $voucher_info->save();
        
        // add new maintenance call on save 
        if($call_info->ic_maintenance_type == MaintenanceTypes::MAINTENANCE_RO || $call_info->ic_maintenance_type == MaintenanceTypes::MAINTENANCE_SCHEDULED_MAIN)
        {
           $today = date('Y-m-d');
           $call_date = "";
           if($call_info->ic_maintenance_type == MaintenanceTypes::MAINTENANCE_RO)
               $call_date = date('Y-m-d', strtotime('+18 month', strtotime($today)));
           else
               $call_date = date('Y-m-d', strtotime('+3 month', strtotime($today)));

           $count_calls = InboundCall::whereIcIsDeleted(0)->count();
           $index = $count_calls + 1;
           $call_index = "CC" . sprintf('%05d', $index);

           $call_info = new InboundCall();
           $call_info->ic_call_index           = $call_index;  
           $call_info->ic_sales_id             = $voucher_info->fk_sales_id;  
           $call_info->ic_telemarketing_id     = $voucher_info->fk_telemarketing_id;  
           $call_info->ic_client_code          = $voucher_info->ic_client_code;  
           $call_info->ic_contract_code        = $voucher_info->ic_contract_code;  
           $call_info->ic_serial_number        = $voucher_info->ic_serial_number;  
           $call_info->ic_call_date            = $call_date;  
           $call_info->ic_call_start_time      = "08:00";  
           $call_info->ic_maintenance_type     = $voucher_info->ic_maintenance_type;  
           $call_info->save();           
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        return Response()->json($result_array);
    }
    
    
    /**
     * Save Cost Center Category Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveInboundCallInfo(Request $request)
    {
        $ic_id                          = $request->input('ic_id');
        $fk_customer_id             = $request->input('fk_customer_id');
        $ic_sales_id             = $request->input('ic_sales_id');
        $ic_client_code             = $request->input('ic_client_code');
        $ic_contract_code             = $request->input('ic_contract_code');
        $ic_call_date             = $request->input('ic_call_date');
        $ic_call_start_time             = $request->input('ic_call_start_time');
        $ic_call_duration             = $request->input('ic_call_duration');
        $ic_call_outcome             = $request->input('ic_call_outcome');
        $ic_issue_resolved             = $request->input('ic_issue_resolved');
        $ic_call_subject             = $request->input('ic_call_subject');
        $ic_customer_product             = $request->input('ic_customer_product');
        $ic_product_machine_id             = $request->input('ic_product_machine_id');
        $ic_under_warranty             = $request->has('ic_under_warranty') ? 1 : 0;
        $ic_warranty_expiry             = $request->input('ic_warranty_expiry'); 
        $ic_notes                   = $request->input('ic_notes');
        $ic_item_problem                  = $request->input('ic_item_problem');
        $ic_bill_situation                  = $request->input('ic_bill_situation');
        $ic_technician_id                  = $request->input('ic_technician_id');
        $ic_result_id                  = $request->input('ic_result_id');
        $ic_maintenance_type                  = $request->input('ic_maintenance_type');
   
        
        $result_array = array();
 
        
        $inboundcall_info = new InboundCall();
        if( $ic_id != null )
        {
            $inboundcall_info = InboundCall::find($ic_id);
            $inboundcall_info->ic_last_updated_by = session('user_id'); 
            $inboundcall_info->ic_last_updated_date = date('Y-m-d'); 
        }
        else
        {
            $inboundcall_info->ic_created_by = session('user_id'); 
            $inboundcall_info->ic_created_at = date('Y-m-d');
        }
         
        $inboundcall_info->fk_customer_id               = $fk_customer_id;
        $inboundcall_info->ic_sales_id               = $ic_sales_id;
        $inboundcall_info->ic_client_code               = $ic_client_code;
        $inboundcall_info->ic_contract_code               = $ic_contract_code;
        $inboundcall_info->ic_call_date               = $ic_call_date;
        $inboundcall_info->ic_call_start_time               = $ic_call_start_time;
        $inboundcall_info->ic_call_duration               = $ic_call_duration;
        $inboundcall_info->ic_call_outcome               = $ic_call_outcome;
        $inboundcall_info->ic_issue_resolved               = $ic_issue_resolved;
        $inboundcall_info->ic_notes               = $ic_notes;
        $inboundcall_info->ic_call_subject               = $ic_call_subject;
        $inboundcall_info->ic_customer_product               = $ic_customer_product;
        $inboundcall_info->ic_product_machine_id               = $ic_product_machine_id;
        $inboundcall_info->ic_under_warranty               = $ic_under_warranty;
        $inboundcall_info->ic_warranty_expiry               = $ic_warranty_expiry;
        $inboundcall_info->ic_item_problem               = $ic_item_problem;
        $inboundcall_info->ic_bill_situation               = $ic_bill_situation;
        $inboundcall_info->ic_technician_id               = $ic_technician_id;
        $inboundcall_info->ic_result_id                     = $ic_result_id;
        $inboundcall_info->ic_maintenance_type                     = $ic_maintenance_type;
        
        $inboundcall_info->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Inbound Call Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Cost Center category Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $tc_id
     */
    public function EditForm( $ic_id )
    {
        $inboundcall_info          = InboundCall::find($ic_id);
        $lst_clients = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_technicians = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TECHNICIAN)->get();
        $lst_products = Products::wherePProductIsDeleted(0)->get();
        $lst_maint_types = MaintenanceTypes::whereMtIsDeleted(0)->get();
        $lst_results = CallResults::whereCrIsDeleted(0)->get();
        
        
        $data = array(
            "inboundcall_info" => $inboundcall_info,
            "lst_sales" => $lst_sales,
            "lst_clients" => $lst_clients,
            "lst_results" => $lst_results,
            "lst_technicians" => $lst_technicians,
            "lst_products" => $lst_products,
            "inboundcall_info" => $inboundcall_info,
            "lst_maint_types" => $lst_maint_types,
        );
        return view('callcenter.editinboundcall',$data);
    }
    
    
    /**
     * Delete Cost Center information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteInboundCallInformation(Request $request)
    {
        $result_array = array();
        $ic_id= $request->input('ic_id');
         
        $inboundcall_info = InboundCall::find( $ic_id );
        $inboundcall_info->ic_is_deleted          = 1;
        $inboundcall_info->ic_deleted_by          = Session('user_id');
        $inboundcall_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
}