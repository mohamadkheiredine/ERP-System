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
use App\models\CallCenter\InboundCall;
use App\models\Inventory\Customers;


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
        
        $lst_users = Users::whereUIsDeleted(0)->get();
        
        $data = array(
            "lst_users" => $lst_users,
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
        $fk_agent_id            = $request->input('fk_agent_id');
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
     * Function of Adding a new Cost Center category
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    { 
        $lst_customers = Customers::whereIcIsDeleted(0)->get();
        $lst_agents = Users::where('u_is_active',1)->where('u_is_deleted',0)->get();
        
        
        $data = array(
            "lst_customers" => $lst_customers,
            "lst_agents" => $lst_agents,
        );
        return view('callcenter.addinboundcall',$data);
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
        $fk_agent_id             = $request->input('fk_agent_id');
        $ic_call_date             = $request->input('ic_call_date');
        $ic_call_start_time             = $request->input('ic_call_start_time');
        $ic_call_end_time             = $request->input('ic_call_end_time');
        $ic_call_duration             = $request->input('ic_call_duration');
        $ic_call_outcome             = $request->input('ic_call_outcome');
        $ic_issue_resolved             = $request->input('ic_issue_resolved');
        $ic_call_subject             = $request->input('ic_call_subject');
        $ic_notes             = $request->input('ic_notes');
   
        
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
        $inboundcall_info->fk_agent_id               = $fk_agent_id;
        $inboundcall_info->ic_call_date               = $ic_call_date;
        $inboundcall_info->ic_call_start_time               = $ic_call_start_time;
        $inboundcall_info->ic_call_end_time               = $ic_call_end_time;
        $inboundcall_info->ic_call_duration               = $ic_call_duration;
        $inboundcall_info->ic_call_outcome               = $ic_call_outcome;
        $inboundcall_info->ic_issue_resolved               = $ic_issue_resolved;
        $inboundcall_info->ic_notes               = $ic_notes;
        $inboundcall_info->ic_call_subject               = $ic_call_subject;
        
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
       $lst_customers = Customers::whereIcIsDeleted(0)->get();
        $lst_agents = Users::where('u_is_active',1)->where('u_is_deleted',0)->get();
        
        
        $data = array(
            "lst_customers" => $lst_customers,
            "lst_agents" => $lst_agents,
            "inboundcall_info" => $inboundcall_info,
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