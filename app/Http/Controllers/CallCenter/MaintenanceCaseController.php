<?php
/***********************************************************
MaintenanceCaseController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\Http\Controllers\CallCenter;

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
use App\models\CallCenter\CaseStatus;
use App\models\Users\Users;
use App\models\Users\UserTypes;
use App\models\CallCenter\MaintenanceCase;
use App\models\CallCenter\MaintenanceTypes;
use App\models\CallCenter\InboundCall;
use App\models\Inventory\Products;
use App\models\System\Currency;


class MaintenanceCaseController extends Controller
{

    /**
     * Page to control Case Maintenance Case Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        
        $lst_statuses = CaseStatus::whereCcIsDeleted(0)->get();
        $lst_maint_types = MaintenanceTypes::whereMtIsDeleted(0)->get();
        $lst_technicians = Users::whereUIsDeleted(0)->whereUIsActive(0)->whereIn('u_user_type',array(UserTypes::USER_TYPE_TECHNICIAN))->get();
        $lst_telemarketings = Users::whereUIsDeleted(0)->whereUIsActive(0)->whereIn('u_user_type',array(UserTypes::USER_TYPE_TELEMARKETING))->get();
        
        $data = array(
            "lst_statuses" => $lst_statuses,
            "lst_technicians" => $lst_technicians,
            "lst_maint_types" => $lst_maint_types,
            "lst_telemarketings" => $lst_telemarketings,
        );
        return Response()->view('callcenter.maintenancecase',$data);
    }
    
    
   /**
    * Display list of Case Statuses saved in the database
    * 
    * @author Moe Mantach
    * @access public
    * @param Request $request
    * @return unknown
    */
    public function DisplayList(Request $request)
    {   
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $cc_assigned_agent_id   = $request->input('cc_assigned_agent_id');
        $cc_technician_id       = $request->input('cc_technician_id');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
            
        $maintenancecases_cond = MaintenanceCase::whereCcIsDeleted(0);

        if(strlen($general_search) > 0)
        {
            $maintenancecases_cond = $maintenancecases_cond->where('cc_case_label','LIKE','%' . $general_search . '%');
            $maintenancecases_cond = $maintenancecases_cond->orWhere('cc_case_description','LIKE','%' . $general_search . '%');
            $maintenancecases_cond = $maintenancecases_cond->orWhere('cc_case_code','LIKE','%' . $general_search . '%');
            $maintenancecases_cond = $maintenancecases_cond->orWhere('cc_serial_number','LIKE','%' . $general_search . '%');
        }
        
        if($cc_assigned_agent_id > 0)
        {
            $maintenancecases_cond = $maintenancecases_cond->where('cc_assigned_agent_id',$cc_assigned_agent_id); 
        }
        
        if($cc_technician_id > 0)
        {
            $maintenancecases_cond = $maintenancecases_cond->where('cc_technician_id',$cc_technician_id); 
        }


        $cases_count = $maintenancecases_cond->count();


        $total_pages = ceil( $cases_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_cases_info = $maintenancecases_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('cc_id', 'asc')->get();

        $data = array(
            "lst_cases_info" => $lst_cases_info
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("callcenter.listcases",$data)->render();

        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $count_cases = MaintenanceCase::whereCcIsDeleted(0)->count();
        $index = $count_cases + 1;
        $case_code = "CC" . sprintf('%05d', $index);
        
        //$cc_case_code
        $lst_statuses = CaseStatus::whereCcIsDeleted(0)->get();
        $lst_technicians = Users::whereUIsDeleted(0)->whereUIsActive(0)->whereIn('u_user_type',array(UserTypes::USER_TYPE_TECHNICIAN))->get();
        $lst_telemarketing = Users::whereUIsDeleted(0)->whereUIsActive(0)->whereIn('u_user_type',array(UserTypes::USER_TYPE_TELEMARKETING))->get();
        $lst_maint_types = MaintenanceTypes::whereMtIsDeleted(0)->get();
        $lst_currencies = Currency::all();
        $data = array(
            "lst_statuses" => $lst_statuses,
            "case_code" => $case_code,
            "lst_maint_types" => $lst_maint_types,
            "lst_currencies" => $lst_currencies,
            "lst_technicians" => $lst_technicians,
            "lst_telemarketing" => $lst_telemarketing,
        );
        return view('callcenter.addcase',$data);
    }
    
    
    /**
     * Save Maintenance Case Info to saved in the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveMaintenanceCaseInfo(Request $request)
    {
        $cc_id                                      = $request->input('cc_id');
        $cc_case_code                                = $request->input('cc_case_code');
        $cc_case_date                                = $request->input('cc_case_date');
        $cc_case_time                                = $request->input('cc_case_time');
        $cc_contract_code                              = $request->input('cc_contract_code');
        $cc_client_code                              = $request->input('cc_client_code');
        $cc_client_id                              = $request->input('cc_client_id');
        $cc_phone_number                             = $request->input('cc_phone_number');
        $cc_case_description                        = $request->input('cc_case_description');
        $cc_priority_level                          = $request->input('cc_priority_level');
        $cc_technician_id                           = $request->input('cc_technician_id');
        $cc_case_status                             = $request->input('cc_case_status');
        $cc_case_deadline                           = $request->input('cc_case_deadline');
        $cc_resolution_date                         = $request->input('cc_resolution_date');
        $cc_resolution_notes                        = $request->input('cc_resolution_notes');
        $cc_maint_type_id                        = $request->input('cc_maint_type_id');
        $cc_doc_number                        = $request->input('cc_doc_number');
        $cc_serial_number                        = $request->input('cc_serial_number');
        $cc_visit_type                       = $request->input('cc_visit_type');
        $cc_case_price                       = $request->input('cc_case_price');
        $cc_currency_id                       = $request->input('cc_currency_id'); 
        $cc_comission                       = $request->input('cc_comission'); 
        
        $result_array = array();
 

        $cases_info     = new MaintenanceCase();
        if($cc_id != null)
        {
            $cases_info = MaintenanceCase::find($cc_id);
        }
         
        $cases_info->cc_call_id                     = 0; 
        $cases_info->cc_case_code                   = $cc_case_code; 
        $cases_info->cc_doc_number                   = $cc_doc_number; 
        $cases_info->cc_contract_code                  = $cc_contract_code; 
        $cases_info->cc_comission                  = $cc_comission; 
        $cases_info->cc_client_code                  = $cc_client_code; 
        $cases_info->cc_client_id                  = $cc_client_id; 
        $cases_info->cc_case_date                  = $cc_case_date; 
        $cases_info->cc_case_time                  = $cc_case_time; 
        $cases_info->cc_case_description            = $cc_case_description; 
        $cases_info->cc_priority_level              = $cc_priority_level; 
        $cases_info->cc_technician_id               = $cc_technician_id; 
        $cases_info->cc_case_status                 = $cc_case_status; 
        $cases_info->cc_case_deadline               = $cc_case_deadline; 
        $cases_info->cc_resolution_date             = $cc_resolution_date; 
        $cases_info->cc_resolution_notes            = $cc_resolution_notes; 
        $cases_info->cc_maint_type_id               = $cc_maint_type_id; 
        $cases_info->cc_phone_number               = $cc_phone_number; 
        $cases_info->cc_serial_number               = $cc_serial_number; 
        $cases_info->cc_visit_type               = $cc_visit_type; 
        $cases_info->cc_case_price               = $cc_case_price; 
        $cases_info->cc_currency_id               = $cc_currency_id; 
        
        
        
        $cases_info->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Case Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit Form Page for Case status
     * @param unknown $ss_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $cc_id )
    {
        $case_info = MaintenanceCase::find($cc_id); 
        $lst_statuses = CaseStatus::whereCcIsDeleted(0)->get();
        $lst_technicians = Users::whereUIsDeleted(0)->whereUIsActive(0)->whereIn('u_user_type',array(UserTypes::USER_TYPE_TECHNICIAN))->get();
        $lst_telemarketing = Users::whereUIsDeleted(0)->whereUIsActive(0)->whereIn('u_user_type',array(UserTypes::USER_TYPE_TELEMARKETING))->get();
        $lst_maint_types = MaintenanceTypes::whereMtIsDeleted(0)->get();
        $lst_currencies = Currency::all();
        $data = array(
            "lst_statuses" => $lst_statuses,
            "lst_technicians" => $lst_technicians,
            "lst_maint_types" => $lst_maint_types,
            "lst_currencies" => $lst_currencies,
            "lst_telemarketing" => $lst_telemarketing,
            "case_info" => $case_info,
        );
        return view('callcenter.editcase',$data);
    }
    
    
    /**
     * Delete Supplier status from the database by change flag is_deleted of the row
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteMaintenanceCaseInfo(Request $request)
    {
        
        $cc_id = $request->input('cc_id');
         
        $maintenance_case = MaintenanceCase::find( $cc_id );
        $maintenance_case->cc_is_deleted          = 1;
        $maintenance_case->cc_deleted_by          = Session('user_id');
        $maintenance_case->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}