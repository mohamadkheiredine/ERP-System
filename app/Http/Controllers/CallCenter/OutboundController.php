<?php
/***********************************************************
OutboundController.php
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
use App\models\CostCenter\CostCenters;
use App\models\Users\Users;
use App\models\CallCenter\OutboundCall;
use App\models\Inventory\Customers;
use App\models\CRM\CRMLeads;

class OutboundController extends Controller
{
    
  
    /**
     * Page to control Outbound Calls Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        
        $lst_users = Users::whereUIsDeleted(0)->get();
        $lst_leads = CRMLeads::whereClIsDeleted(0)->get();
        $lst_customers_info = Customers::whereIcIsDeleted(0)->get();
        
        $data = array(
            "lst_users" => $lst_users,
            "lst_leads" => $lst_leads,
            "lst_customers_info" => $lst_customers_info,
        );
        return Response()->view('callcenter.outboundcalls',$data);
    }
    
    
    /**
     * Display list of outbound call saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
   
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $oc_agent_id            = $request->input('oc_agent_id');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
            
        $outboundcall_cond = OutboundCall::whereOcIsDeleted(0);

        if(strlen($general_search) > 0)
        {
            $outboundcall_cond = $outboundcall_cond->where('oc_call_outcome','LIKE','%' . $general_search . '%');
            $outboundcall_cond = $outboundcall_cond->orWhere('oc_notes','LIKE','%' . $general_search . '%');
        }


        $outboundcall_count = $outboundcall_cond->count();


        $total_pages = ceil( $outboundcall_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_outboundcall_info = $outboundcall_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('oc_id', 'asc')->get();

        $data = array(
            "lst_outboundcall_info" => $lst_outboundcall_info
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("callcenter.listoutbound",$data)->render();

        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new outbound call category
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {  
        $lst_leads = CRMLeads::whereClIsDeleted(0)->get();
        $lst_agents = Users::where('u_is_active',1)->where('u_is_deleted',0)->get();
        
        
        $data = array(
            "lst_leads" => $lst_leads,
            "lst_agents" => $lst_agents,
        );
        return view('callcenter.addoutboundcall',$data);
    }
    
    
    /**
     * Save outbound call Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveOutboundCallInfo(Request $request)
    {
        $oc_id                              = $request->input('oc_id');
        $oc_lead_id                         = $request->input('oc_lead_id');
        $oc_agent_id                        = $request->input('oc_agent_id');
        $oc_call_subject                    = $request->input('oc_call_subject');
        $oc_call_date                       = $request->input('oc_call_date');
        $oc_call_start_time                 = $request->input('oc_call_start_time');
        $oc_call_end_time                   = $request->input('oc_call_end_time');
        $oc_call_duration                   = $request->input('oc_call_duration');
        $oc_call_purpose                    = $request->input('oc_call_purpose');
        $oc_call_outcome                    = $request->input('oc_call_outcome');
        $oc_follow_up_required              = $request->has('oc_follow_up_required') ? 1 : 0;
        $oc_follow_up_date                  = $request->input('oc_follow_up_date');
        $oc_notes                           = $request->input('oc_notes');
   
        
        $result_array = array();
 
        
        $outboundcall_info = new OutboundCall();
        if( $oc_id != null )
        {
            $outboundcall_info = OutboundCall::find($oc_id);
            $outboundcall_info->oc_last_updated_by = session('user_id'); 
            $outboundcall_info->oc_last_updated_date = date('Y-m-d'); 
        }
        else
        {
            $outboundcall_info->oc_created_by = session('user_id'); 
            $outboundcall_info->oc_created_at = date('Y-m-d');
        }
         
        $outboundcall_info->oc_lead_id                          = $oc_lead_id;
        $outboundcall_info->oc_agent_id                         = $oc_agent_id;
        $outboundcall_info->oc_call_subject                     = $oc_call_subject;
        $outboundcall_info->oc_call_date                        = $oc_call_date;
        $outboundcall_info->oc_call_start_time                  = $oc_call_start_time;
        $outboundcall_info->oc_call_end_time                    = $oc_call_end_time;
        $outboundcall_info->oc_call_duration                    = $oc_call_duration;
        $outboundcall_info->oc_call_purpose                     = $oc_call_purpose;
        $outboundcall_info->oc_call_outcome                     = $oc_call_outcome;
        $outboundcall_info->oc_follow_up_required               = $oc_follow_up_required;
        $outboundcall_info->oc_follow_up_date                   = $oc_follow_up_date;
        $outboundcall_info->oc_notes                            = $oc_notes;
 
        
        $outboundcall_info->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Outbound Call Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Cost Center category Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $tc_id
     */
    public function EditForm( $oc_id )
    {
        $outboundcall_info          = OutboundCall::find($oc_id);
        $lst_leads = CRMLeads::whereClIsDeleted(0)->get();
        $lst_agents = Users::where('u_is_active',1)->where('u_is_deleted',0)->get();
        
        
        $data = array(
            "outboundcall_info" => $outboundcall_info,
            "lst_leads" => $lst_leads,
            "lst_agents" => $lst_agents,
        );
        return view('callcenter.editoutboundcall',$data);
    }
    
    
    /**
     * Delete Cost Center information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteOutboundCallInformation(Request $request)
    {
        $result_array = array();
        $oc_id= $request->input('oc_id');
         
        $outboundcall_info = OutboundCall::find( $oc_id );
        $outboundcall_info->oc_is_deleted          = 1;
        $outboundcall_info->oc_deleted_by          = Session('user_id');
        $outboundcall_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
}