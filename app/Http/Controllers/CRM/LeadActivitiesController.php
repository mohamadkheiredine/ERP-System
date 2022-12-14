<?php
/***********************************************************
LeadActivitiesController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2019
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
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeads;
use App\models\Users\Users; 
use App\models\System\Industry;
use App\Library\LeadsManager;
use App\models\CRM\CRMLeadSources;
use App\models\System\Countries;
use App\models\CRM\CRMLeadActivities;
use Config;
use App\models\CRM\CRMActivityTypes;
use App\models\CRM\CRMContacts;
use App\models\CRM\CRMActivityPurpose;
use App\Library\CRMLogsManager;


class LeadActivitiesController extends Controller
{
    /**
     * Display Lead Activity tabs saved for this lead
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayLeadActivityTab(Request $request)
    {
        $cl_id                      = $request->input("cl_id");
        $lst_activities             = CRMLeadActivities::whereFkLeadId($cl_id)->whereCaIsDeleted(0)->get();
        
        $result_array   = array();
        
        $data = array(
            "lst_activities" => $lst_activities
        );
        
        $result_array['is_error']   = 0;
        $result_array['display']    = view("Leads.leadactivities",$data)->render();
        return Response()->json($result_array);
    }
    
    
    /**
     * Page to manage and display list of activities already registered 
     * on the system
     * 
     * @author Moe Mantach
     * @access public
     */
    public function Activities()
    {
        
        $lst_leads          = CRMLeads::whereClIsDeleted(0)->get();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        
        $data = array(
            "lst_leads" => $lst_leads,
            "lst_users" => $lst_users
        );
        return Response()->view('Leads.activities',$data);
    }
    
    /**
     * Edit Lead Activity Form
     * 
     * @author Moe Mantach
     * @access public
     * @param number $ca_id
     */
    public function EditForm( $ca_id )
    {
        $LeadActivity = CRMLeadActivities::find($ca_id);
        $lst_leads          = CRMLeads::whereClIsDeleted(0)->get();
        $lst_contacts       = CRMContacts::whereCcIsDeleted(0)->get();
        $lst_activity_types = CRMActivityTypes::whereAtIsDeleted(0)->get();
        $lst_activity_purpose = CRMActivityPurpose::whereApIsDeleted(0)->get();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        
        $data = array(
            "LeadActivity" => $LeadActivity,
            "lst_leads" => $lst_leads,
            "lst_contacts" => $lst_contacts,
            "lst_users" => $lst_users,
            "lst_activity_types" => $lst_activity_types,
            "lst_activity_purpose" => $lst_activity_purpose,
        );
        return view("Leads.editleadactivity",$data);
    }
    
    public function AddForm()
    {
        $lst_leads          = CRMLeads::whereClIsDeleted(0)->get();
        $lst_contacts       = CRMContacts::whereCcIsDeleted(0)->get();
        $lst_activity_types = CRMActivityTypes::whereAtIsDeleted(0)->get();
        $lst_activity_purpose = CRMActivityPurpose::whereApIsDeleted(0)->get();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        
        $data = array( 
            "lst_leads" => $lst_leads,
            "lst_contacts" => $lst_contacts,
            "lst_users" => $lst_users,
            "lst_activity_types" => $lst_activity_types,
            "lst_activity_purpose" => $lst_activity_purpose,
        );
        return view("Leads.addleadactivity",$data);
    }
    
    
    /**
     * Save Lead Activities to the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveLeadActivityInfo(Request $request)
    {
        
        $result_array = array();
        $ca_id                  = $request->input("ca_id");
        $fk_lead_id             = $request->input("fk_lead_id");
        $fk_contact_id          = $request->input("fk_contact_id");
        $ca_activity_type       = $request->input("ca_activity_type");
        $ca_activity_purpose    = $request->input("ca_activity_purpose");
        $ca_activity_subject    = $request->input("ca_activity_subject");
        $fk_owner_id            = $request->input("fk_owner_id");
        $ca_activity_date       = $request->input("ca_activity_date");
        $ca_activity_date       = date( "Y-m-d",strtotime( $ca_activity_date ) );
        $ca_activity_duration_hours = $request->input("ca_activity_duration_hours");
        $ca_activity_duration_min   = $request->input("ca_activity_duration_min");
        $activity_detail            = $request->input("activity_detail");
        $ca_activity_description    = $request->input("ca_activity_description");
        $ca_activity_result         = $request->input("ca_activity_result");
        
        
        $LeadActivity = new CRMLeadActivities();
        if($ca_id != null)
        {
            $LeadActivity = CRMLeadActivities::find($ca_id);
        }
        $LeadActivity->fk_lead_id                   = $fk_lead_id;
        $LeadActivity->fk_contact_id                = $fk_contact_id;
        $LeadActivity->ca_activity_type             = $ca_activity_type;
        $LeadActivity->ca_activity_purpose          = $ca_activity_purpose;
        $LeadActivity->ca_activity_subject          = $ca_activity_subject;
        $LeadActivity->fk_owner_id                  = $fk_owner_id;
        $LeadActivity->ca_activity_date             = $ca_activity_date;
        $LeadActivity->ca_activity_duration_hours   = $ca_activity_duration_hours;
        $LeadActivity->ca_activity_duration_min     = $ca_activity_duration_min;
        $LeadActivity->ca_activity_details          = $activity_detail;
        $LeadActivity->ca_activity_description      = $ca_activity_description;
        $LeadActivity->ca_activity_result           = $ca_activity_result;
        $LeadActivity->save();
        
        // add log for lead activity
        $CRMLogs = new CRMLogsManager();
        $params_array = array(
            'fk_lead_id' => $fk_lead_id,
            'log_type' => CRMLogsManager::LOG_TYPE_ADD_LEAD_ACTIVITY
        );
        $CRMLogs->InsertCRMLog($params_array);
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']    = "Operation Completed Successfully";
        return Response()->json($result_array);
    }
    
    
    public function DisplayList(Request $request)
    {
        $activities_lead = $request->input("activities_lead");
        $activities_user = $request->input('activities_user');
        $result_array    = array();
        
        $lst_activities             = CRMLeadActivities::whereCaIsDeleted(0);
 
        if($activities_lead != 0)
        {
            $lst_activities = $lst_activities->whereFkLeadId($activities_lead);
        }
        
        
        if($activities_user != 0)
        {
            $lst_activities = $lst_activities->whereFkOwnerId($activities_user);
        }
        
        $lst_activities             = $lst_activities->get();
        
        
        $data = array(
            "lst_activities" => $lst_activities
        );
        $result_array['is_error']   = 0;
        $result_array['display']    = view("Leads.leadactivities",$data)->render();
        return Response()->json($result_array);
        
    }
    
    
    /**
     * Delete lead activity 
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteLeadActivity(Request $request)
    {
        $ca_id = $request->input("ca_id");
        
        $Activity = CRMLeadActivities::find($ca_id);
        $Activity->ca_is_deleted    = 1;
        $Activity->ca_deleted_by    = session("user_id");
        $Activity->save();
        
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']    = "Operation Completed Successfully";
        return Response()->json($result_array);
        
    }
}