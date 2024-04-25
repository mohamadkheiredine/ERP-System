<?php
/***********************************************************
LeadsController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 22, 2019
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
use App\models\Inventory\WareHouses;
use App\models\System\Industry;
use App\library\LeadsManager;
use App\models\CRM\CRMLeadSources;
use App\models\System\Countries;
use App\models\CRM\CRMLeadActivities;
use App\models\CRM\CRMContacts;
use App\models\CRM\CRMActivityTypes;
use App\models\CRM\CRMActivityPurpose;
use Maatwebsite\Excel\Facades\Excel;
use App\models\CRM\CRMServiceCategories;
use App\library\CRMLogsManager;


class LeadsController extends Controller
{
    
    /**
     * Main Page to display the leads management
     * 
     * @author Moe Mantach
     * @access public
     * @return Response
     */
    public function index()
    {
        
        // get dropdowns filter data
        $lead_categories = CRMClientCategories::whereCcIsDeleted(0)->get();
        $lead_statuses  = CRMLeadStatus::whereLsIsDeleted(0)->get();
        $lst_users  = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        
        $data = array(
            "lead_categories" => $lead_categories,
            "lst_users" => $lst_users,
            "lead_statuses" => $lead_statuses
        );
        return Response()->view("leads.leads",$data);
    }


    /**
     * Display list of the Leads based on selected fields
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
        $lead_category  = $request->input("lead_category");
        $lead_status    = $request->input("lead_status");
        $lead_user      = $request->input("lead_user");
        
        $lst_leads_obj = CRMLeads::whereClIsDeleted(0);
        
        if( $lead_category > 0 )
        {
            $lst_leads_obj = $lst_leads_obj->whereClCategoryId($lead_category);
        }
        
        if( $lead_status > 0 )
        {
            $lst_leads_obj = $lst_leads_obj->whereFkLeadStatusId($lead_status);
        }
        
        if( $lead_user > 0 )
        {
            $lst_leads_obj = $lst_leads_obj->whereFkAssignTo($lead_user);
        }
        
        
        $lst_leads = $lst_leads_obj->get();
        

        $response_array = array();

        $data = array(
            "lst_leads" => $lst_leads
        );
        $response_array['is_error'] = 0;
        $response_array['display'] = view('leads.listleads',$data)->render();

        return Response()->json($response_array);
    }

    /**
     * Open form of add new Lead
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function AddForm()
    {
        $lead_categories    = CRMClientCategories::whereCcIsDeleted(0)->get();
        $lead_statuses      = CRMLeadStatus::whereLsIsDeleted(0)->whereFkParentStatus(null)->get();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_industries     = Industry::whereSiIsDeleted(0)->get(); 
        $lst_lead_source    = CRMLeadSources::whereLsIsDeleted(0)->get(); 
        $lst_countries      = Countries::all(); 

        $data = array(
            'lead_categories' => $lead_categories,
            'lead_statuses' => $lead_statuses,
            'lst_users' => $lst_users,
            'lst_industries' => $lst_industries,
            'lst_countries' => $lst_countries,
            'lst_lead_source' => $lst_lead_source
        );

        return Response()->view('leads.addlead',$data);
    }

    /**
     * get information of selected lead and open the edit form fields
     * @param unknown $cl_id
     * @return \Illuminate\Http\Response
     */
    public function EditForm($cl_id)
    {
        $lead_info = CRMLeads::find($cl_id);
        
        $lead_categories                = CRMClientCategories::whereCcIsDeleted(0)->get();
        
        $lead_status                    =  $lead_info->fk_lead_status_id;
        
        $lead_statuses                  = CRMLeadStatus::whereLsIsDeleted(0)->whereFkParentStatus($lead_status)->orWhere('ls_id',$lead_status)->get();
        $lst_users                      = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_industries                 = Industry::whereSiIsDeleted(0)->get();
        $lst_lead_source                = CRMLeadSources::whereLsIsDeleted(0)->get();
        $lst_countries                  = Countries::all();
        $lst_service_categories         = CRMServiceCategories::whereScIsDeleted(0)->get();
        
 
        $data = array(
            'lead_categories' => $lead_categories,
            'lead_statuses' => $lead_statuses,
            'lst_users' => $lst_users,
            'lst_industries' => $lst_industries,
            'lst_lead_source' => $lst_lead_source,
            'lst_countries' => $lst_countries,
            'lst_service_categories' => $lst_service_categories,
            'lead_info' => $lead_info
        );
        
        return Response()->view('leads.editlead',$data);

    }


    /**
     * function to save data of warehouse to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveLeadInfo(Request $request)
    { 
        
        $cl_id              = $request->input('cl_id');
        $fk_lead_owner      = $request->input('fk_lead_owner');
        $cl_company_name    = $request->input('cl_company_name');
        $fk_assign_to       = $request->input('fk_assign_to');
        $cl_category_id     = $request->input('cl_category_id');
        $cl_first_name      = $request->input('cl_first_name');
        $cl_last_name       = $request->input('cl_last_name');
        $cl_email           = $request->input('cl_email');
        $cl_phone           = $request->input('cl_phone');
        $cl_mobile          = $request->input('cl_mobile');
        $cl_fax             = $request->input('cl_fax');
        $cl_website         = $request->input('cl_website');
        $fk_lead_source_id  = $request->input('fk_lead_source');
        $fk_lead_status_id  = $request->input('fk_lead_status_id');
        $fk_industry_id     = $request->input('fk_industry_id');
        $cl_nbr_employees   = $request->input('cl_nbr_employees');
        $cl_anual_revenue   = $request->input('cl_anual_revenue');
        $cl_rating          = $request->input('cl_rating');
        $cl_skype_id        = $request->input('cl_skype_id');
        $cl_twitter_account = $request->input('cl_twitter_account');
        $cl_facebook_id     = $request->input('cl_facebook_id');
        $cl_country_id      = $request->input('cl_country_id');
        $cl_city            = $request->input('cl_city');
        $cl_state           = $request->input('cl_state');
        $cl_zip_code        = $request->input('cl_zip_code');
        $cl_lead_description= $request->input('cl_lead_description');
        $cl_type_items      = $request->input('cl_type_items');
       $need_shipment       = $request->input('need_shipment');
       $ini_status_id       = $request->input('ini_status_id');
       $ini_assign_to       = $request->input('ini_assign_to');
       
        $is_new = true; 
        $LeadInfo = new CRMLeads();
        $leads_obj = new LeadsManager(); 
        if($cl_id != null)
        {
            $LeadInfo = CRMLeads::find($cl_id);
            $is_new = false;
        }
        
        
        // upload file to the CRM photo
        if(count($_FILES) > 0 )
        {
            $image_data =  $leads_obj->UploadLeadAvatar($cl_id);
            
            $LeadInfo->cl_image_base_src    = $image_data['data']['cl_image_base_src'];
            $LeadInfo->cl_image_file_name   = $image_data['data']['cl_image_file_name'];
            $LeadInfo->cl_image_extension   = $image_data['data']['cl_image_extension'];
            
        }
        
        
        $LeadInfo->fk_lead_owner        = $fk_lead_owner; 
        $LeadInfo->fk_assign_to         = $fk_assign_to;
        $LeadInfo->cl_category_id       = $cl_category_id;
        $LeadInfo->cl_first_name        = $cl_first_name;
        $LeadInfo->cl_last_name         = $cl_last_name;
        $LeadInfo->cl_email             = $cl_email;
        $LeadInfo->cl_phone             = $cl_phone;
        $LeadInfo->cl_mobile            = $cl_mobile;
        $LeadInfo->cl_fax               = $cl_fax;
        $LeadInfo->cl_website           = $cl_website;
        $LeadInfo->fk_lead_source_id    = $fk_lead_source_id;
        $LeadInfo->fk_lead_status_id    = $fk_lead_status_id;
        $LeadInfo->fk_industry_id       = $fk_industry_id;
        $LeadInfo->cl_nbr_employees     = $cl_nbr_employees;
        $LeadInfo->cl_anual_revenue     = $cl_anual_revenue;
        $LeadInfo->cl_rating            = $cl_rating;
        $LeadInfo->cl_skype_id          = $cl_skype_id;
        $LeadInfo->cl_twitter_account   = $cl_twitter_account;
        $LeadInfo->cl_facebook_id       = $cl_facebook_id;
        $LeadInfo->cl_country_id        = $cl_country_id;
        $LeadInfo->cl_city              = $cl_city;
        $LeadInfo->cl_state             = $cl_state;
        $LeadInfo->cl_zip_code          = $cl_zip_code;
        $LeadInfo->cl_lead_description  = $cl_lead_description;
        $LeadInfo->cl_company_name      = $cl_company_name;
        $LeadInfo->cl_type_items        = $cl_type_items;
        $LeadInfo->cl_need_shipment     = $need_shipment;
        
        if($is_new == true)
        {
            $LeadInfo->cl_date_creation     = date("Y-m-d");
        }
        
        
        
        $LeadInfo->save();
        $CRMLogs = new CRMLogsManager();
        if($is_new == true)
        {
            $cl_id = $LeadInfo->cl_id;
            $params_array = array(
                'fk_lead_id' => $cl_id,
                'log_type' => CRMLogsManager::LOG_TYPE_ADD_LEAD
            );
            $CRMLogs->InsertCRMLog($params_array);
        }
        else 
        {
            if($ini_status_id != $fk_lead_status_id)
            {
                $params_array= array(
                    "fk_lead_id" => $cl_id,
                    "user_id" => session('user_id'),
                    "log_type" => CRMLogsManager::LOG_TYPE_LEAD_CHANGE_STATUS,
                    "old_lead_status" =>$ini_status_id,
                    "new_lead_status" => $fk_lead_status_id
                );
                $CRMLogs->InsertCRMLog($params_array);
            }
            
        }

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);

    }
    
    
    /**
     * Add Lead Activity from lead page
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function AddLeadActivityForm(Request $request)
    {
        $cl_id              = $request->input("cl_id");
        $lst_leads          = CRMLeads::whereClIsDeleted(0)->get();
        $lst_contacts       = CRMContacts::whereCcIsDeleted(0)->get();
        $lst_activity_types = CRMActivityTypes::whereAtIsDeleted(0)->get();
        $lst_activity_purpose = CRMActivityPurpose::whereApIsDeleted(0)->get();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $data = array(
            "cl_id" => $cl_id,
            "lst_leads" => $lst_leads,
            "lst_contacts" => $lst_contacts,
            "lst_users" => $lst_users,
            "lst_activity_types" => $lst_activity_types,
            "lst_activity_purpose" => $lst_activity_purpose,
        );
        return view("leads.addleadactivity",$data);
    }

    
    /**
     * Change lead status of ids sent to the function
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function ChangeLeadStatus(Request $request)
    {
        $cs_lead_ids        = $request->input("cs_lead_ids");
        $cs_lead_status_id  = $request->input("cs_lead_status_id");
        $lead_ids_array     = explode(",", $cs_lead_ids);
        
        foreach ($lead_ids_array as $key => $lead_id) {
            $Lead_Obj =CRMLeads::find($lead_id);
            
            $old_lead_status = $Lead_Obj->fk_lead_status_id;
            $Lead_Obj->fk_lead_status_id = $cs_lead_status_id;
            $Lead_Obj->save();
            
            if($old_lead_status != $cs_lead_status_id)
            {
                //insert log of change status
                $CRMLogs = new CRMLogsManager();
                $params_array = array(
                    'fk_lead_id' => $lead_id,
                    'old_lead_status' => $old_lead_status,
                    'new_lead_status' => $cs_lead_status_id,
                    'log_type' => CRMLogsManager::LOG_TYPE_LEAD_CHANGE_STATUS
                );
                $CRMLogs->InsertCRMLog($params_array);
            }

            
        }
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
    
    
    public function LeadAssignTo(Request $request)
    {
        $la_lead_ids        = $request->input("la_lead_ids");
        $la_fk_assign_to    = $request->input("la_fk_assign_to");
        $lead_ids_array     = explode(",", $la_lead_ids);
        
        foreach ($lead_ids_array as $key => $lead_id) {
            $Lead_Obj =CRMLeads::find($lead_id);
            $Lead_Obj->fk_assign_to = $la_fk_assign_to;
            $Lead_Obj->save();
            
            
            //insert log of Assign Lead
            $CRMLogs = new CRMLogsManager();
            $params_array = array(
                'fk_lead_id' => $lead_id,
                'assign_to' => $la_fk_assign_to,
                'log_type' => CRMLogsManager::LOG_TYPE_LEAD_ASSIGN_TO
            );
            $CRMLogs->InsertCRMLog($params_array);
        }
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
    
    /**
     * Delete Lead  info and check all condition before begin deleted
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteLeadInfo(Request $request)
    {
        $cl_id = $request->input('cl_id');
        $result_array = array();

        
        $lead_info = CRMLeads::find($cl_id);
        $lead_info->cl_is_deleted   = 1;
        $lead_info->cl_deleted_by   = session('user_id');
        $lead_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
}