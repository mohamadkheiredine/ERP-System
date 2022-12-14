<?php
/***********************************************************
LeadsStatusController.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\CRM;

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
use App\models\CRM\CRMClientCategories;
use App\Library\ClientsCategoriesManager;
use App\models\Sales\OrderStatus;
use App\models\Production\PlanStatus;
use App\models\CRM\CRMLeadStatus;



class LeadsStatusController extends Controller
{

    /**
     * Page to control Lead Status Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('Leads.status',$data);
    }
    
    
    /**
     * Display list of Leads Status saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
   
        $lst_lead_status = CRMLeadStatus::whereLsIsDeleted(0)->get();
        
        $lead_status_array   = CreateDatabaseArrayByIndex($lst_lead_status, "ls_id");
 
        $data = array(
            "lst_lead_status" => $lst_lead_status,
            "lead_status_array" => $lead_status_array
        );
        
        $result_array = array(); 
        $result_array['display'] = view("Leads.displayliststatus",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Lead Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $lst_lead_status = CRMLeadStatus::whereLsIsDeleted(0)->get();
        
        $data = array(
            "lst_lead_status" => $lst_lead_status,
        );
        return view('Leads.addstatus',$data);
    }
    
    
    /**
     * Save Lead Status Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveStatusInfo(Request $request)
    {
        $ls_id                      = $request->input('ls_id');
        $ls_parent_status           = $request->input('ls_parent_status');
        $ls_status_title            = $request->input('ls_status_title');
        $ls_status_description      = $request->input('ls_status_description');
        $ls_status_color            = $request->input('ls_status_color');
   
        
        $result_array = array();
 
        
        $LeadStatus = new CRMLeadStatus();
        if( $ls_id != null )
        {
            $LeadStatus= CRMLeadStatus::find($ls_id);
        }
         
        $LeadStatus->fk_parent_status           = $ls_parent_status;
        $LeadStatus->ls_status_title            = $ls_status_title;
        $LeadStatus->ls_status_description      = $ls_status_description;
        $LeadStatus->ls_status_color            = $ls_status_color;
        
        $LeadStatus->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Lead Status Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Lead Status Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $ls_id
     */
    public function EditForm( $ls_id )
    {
        $lst_lead_status   = CRMLeadStatus::whereLsIsDeleted(0)->whereNotIn('ls_id',array($ls_id))->get();
        $status_info        = CRMLeadStatus::find($ls_id);
        
        $data = array(
            "lst_lead_status" => $lst_lead_status,
            "status_info" => $status_info
        );
        return view('Leads.editstatus',$data);
    }
    
    
    /**
     * Delete Lead Status information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteStatusInfo(Request $request)
    {
        
        $ls_id= $request->input('ls_id');
         
        $lead_status = CRMLeadStatus::find( $ls_id);
        $lead_status->ls_is_deleted          = 1;
        $lead_status->ls_deleted_by          = Session('user_id');
        $lead_status->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}