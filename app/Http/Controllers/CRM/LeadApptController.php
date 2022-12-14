<?php
/***********************************************************
LeadApptController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 4, 2019
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
use Config;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeads;
use App\models\Users\Users;
use App\models\Inventory\WareHouses;
use App\models\System\Industry;
use App\Library\LeadsManager;
use App\models\CRM\CRMLeadSources;
use App\models\CRM\CRMLeadFiles;
use App\models\CRM\CRMLeadAppointments;
use function Illuminate\Foundation\Testing\Concerns\render;
use App\Library\CRMLogsManager;



class LeadApptController extends Controller
{
  
    /**
     * Display list of appointments saved in the database for this current lead
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayLeadApptTab(Request $request)
    {
        $cl_id          = $request->input("cl_id");
        $display_type   = $request->input("display_type");
        $result_array = array();
        $LeadAppointments = CRMLeadAppointments::whereFkLeadId($cl_id)->get();
        
   
        
        $result_array['is_error'] = 0;
        switch($display_type)
        {
            case "list":
                {
                    $data = array(
                        "LeadAppointments" => $LeadAppointments
                    );
                    $result_array["display"] = view("Leads.lstappointments",$data)->render(); 
                }
            break;
            case "calendar":
                {
                    // generate xml file for calendar
                    $AptManager = new LeadsManager();
                    $calendar_path = $AptManager->GenerateAppointmentCalendarFile($cl_id);
                    $result_array['calendar_path'] = $calendar_path;
                    $data = array(
                        "LeadAppointments" => $LeadAppointments
                    );
                    $result_array["display"] = view("Leads.calendarappt",$data)->render(); 
                }
            break;
        } 
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Page to display Calendar of Appointment for loggedin user
     * 
     * @author Moe Mantach
     * @access public
     */
    public function MyCalendar()
    {
        
        $user_id = session('user_id');
        $AptManager = new LeadsManager();
        $calendar_path = $AptManager->GenerateAppointmenMytCalendarFile($user_id); 
        $data = array(
            "calendar_path" => $calendar_path,
        );
        return Response()->view("Leads.leadscalendarappt",$data); 
    }
    
    
    /**
     * Delete Appointment from the database by change flag  ca_is_deleted to 1
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteAppointmentInfo(Request $request)
    {
        $result_array = array();
        $ca_id = $request->input("ca_id");
        
        $crm_appointment = CRMLeadAppointments::find($ca_id);
        $crm_appointment->ca_is_deleted = 1;
        $crm_appointment->ca_deleted_by=  session("user_id");
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        
        return Response()->json($result_array);
    }
    
    
    /**
     * form to add a new appointment to the current lead
     * 
     * @author Moe Mantach
     * @access public
     * @param unknown $cl_id
     */
    public function AddNewAppointmentForm( $cl_id )
    {
        $lst_leads = CRMLeads::whereClIsDeleted(0)->get();
        $lst_users  = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        
        $data = array(
            "cl_id" => $cl_id,
            "lst_leads" => $lst_leads,
            "lst_users" => $lst_users
        );
        return response()->view("Leads.addappointment",$data);
    }
    
    
    /**
     * Edit appointment info for selected lead
     * 
     * @param Request $request
     */
    public function EditLeadAppointmentForm( $ca_id )
    {
        $appt_info = CRMLeadAppointments::find($ca_id);
        $lst_leads = CRMLeads::whereClIsDeleted(0)->get();
        $lst_users  = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        
        $data = array(
            "appt_info" => $appt_info,
            "lst_leads" => $lst_leads,
            "lst_users" => $lst_users
        );
        return response()->view("Leads.editappointment",$data);
        
    }
    
    
    public function SaveAppointmentInfo(Request $request)
    {
        $ca_id          = $request->input("ca_id");
        $fk_lead_id     = $request->input("fk_lead_id");
        $fk_assigned_to = $request->input("fk_assigned_to");
        $ca_appointment_subject     = $request->input("ca_appointment_subject");
        $ca_appointment_date        = $request->input("ca_appointment_date");
        $ca_appointment_date        =  date("Y-m-d",strtotime($ca_appointment_date));
        $ca_appointment_start_time  = $request->input("ca_appointment_start_time");
        $ca_appointment_end_time    = $request->input("ca_appointment_end_time");
        $ca_appointment_description = $request->input("ca_appointment_description");
        $ca_appointment_results     = $request->input("ca_appointment_results");
        $result_array = array();
        $lead_appointment = new CRMLeadAppointments();
        
        if($ca_id !== null)
        {
            $lead_appointment = CRMLeadAppointments::find($ca_id);
        }
        
        $lead_appointment->fk_lead_id                   = $fk_lead_id;
        $lead_appointment->fk_assigned_to               = $fk_assigned_to;
        $lead_appointment->ca_appointment_subject       = $ca_appointment_subject;
        $lead_appointment->ca_appointment_date          = $ca_appointment_date;
        $lead_appointment->ca_appointment_start_time    = $ca_appointment_start_time;
        $lead_appointment->ca_appointment_end_time      = $ca_appointment_end_time;
        $lead_appointment->ca_appointment_description   = $ca_appointment_description;
        $lead_appointment->ca_appointment_results       = $ca_appointment_results;
        $lead_appointment->save();
        
        
        // add log for add lead appointment
        $CRMLogs = new CRMLogsManager();
        $params_array = array(
            'fk_lead_id' => $lead_id,
            'log_type' => CRMLogsManager::LOG_TYPE_ADD_LEAD_APPOINTMENT
        );
        $CRMLogs->InsertCRMLog($params_array);
        

        $result_array['is_error'] = 0;
       
        $result_array['error_msg'] = "Operation Completed Successfully";
        
        return Response()->json($result_array);
    }
}