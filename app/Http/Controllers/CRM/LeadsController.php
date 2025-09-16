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
use App\models\System\Areas;
use App\models\System\Regions;
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
use App\models\CRM\CRMLeadTypes;
use Maatwebsite\Excel\Facades\Excel;
use App\models\CRM\CRMServiceCategories;
use App\models\Users\UserTypes;
use App\models\CRM\CRMLeadResults;
use App\models\CallCenter\ApptResults;
use App\library\CRMLogsManager;
use Config;


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
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_appt_results      = ApptResults::whereArIsDeleted(0)->get();
        $lst_lead_types      = CRMLeadTypes::whereLtIsDeleted(0)->get();
        $lst_areas = Areas::all();

        $data = array(
            "lead_categories" => $lead_categories,
            "lst_appt_results" => $lst_appt_results,
            "lst_lead_types" => $lst_lead_types,
            "lst_areas" => $lst_areas,
            "lst_users" => $lst_users,
            "lst_sales" => $lst_sales,
            "lead_statuses" => $lead_statuses
        );
        if(Config::get('appconfig.crm_telemarketing') == "0")
        {
            return Response()->view("leads.manageleads",$data);
        }
        else
        {
            return Response()->view("leads.leads",$data);
        }

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
        $lead_status            = $request->input("lead_status");
        $cl_sales_id            = $request->input("cl_sales_id");
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $cl_lead_types          = $request->input('cl_lead_types');
        $cl_area                = $request->input('cl_area');
        $lead_mobile            = $request->input('lead_mobile');
        $referred_by            = $request->input('referred_by');
        $lead_region            = $request->input('lead_region');
        $cl_lead_result            = $request->input('cl_lead_result');
        $lead_name              = $request->input('lead_name');
        $sheet_number             = $request->input('sheet_number');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');



        DB::enableQueryLog();

        $leads_cond = CRMLeads::whereClIsDeleted(0);

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;

        if( $lead_status > 0 )
        {
            $leads_cond = $leads_cond->whereFkLeadStatusId($lead_status);
        }

        if( $cl_lead_types > 0 )
        {
            $leads_cond = $leads_cond->whereClLeadTypeId($cl_lead_types);
        }


         if( $cl_sales_id > 0 )
        {
            $leads_cond = $leads_cond->whereClSalesId($cl_sales_id);
        }


        if( strlen($sheet_number)  > 0) {
            $leads_cond = $leads_cond->where('cl_sheet_number', '=', $sheet_number);
        }

        if( $cl_lead_result  > 0) {
            $leads_cond = $leads_cond->where('cl_lead_results', '=', $cl_lead_result);
        }

        if( strlen($lead_name)  > 0) {
            $leads_cond = $leads_cond->where('cl_first_name','LIKE','%' . $lead_name . '%');
            $leads_cond = $leads_cond->orWhere('cl_last_name','LIKE','%' . $lead_name . '%');
        }

        if( strlen($lead_mobile)  > 0) {
            $leads_cond = $leads_cond->where('cl_mobile','LIKE','%' . $lead_mobile . '%');
        }

        if( $cl_area  > 0) {
            $leads_cond = $leads_cond->where('cl_area','LIKE','%' . $cl_area . '%');
        }

        if( strlen($lead_region)  > 0) {
            $leads_cond = $leads_cond->where('cl_region','LIKE','%' . $lead_region . '%');
        }

        if( strlen($referred_by)  > 0) {
            $leads_cond = $leads_cond->where('cl_referred_by','LIKE','%' . $referred_by . '%');
        }

        if( strlen($general_search)  > 0)
        {
            $leads_cond = $leads_cond->where('cl_sheet_number','LIKE','%' . $general_search . '%');
            $leads_cond = $leads_cond->orWhere('cl_first_name','LIKE','%' . $general_search . '%');
            $leads_cond = $leads_cond->orWhere('cl_last_name','LIKE','%' . $general_search . '%');
            $leads_cond = $leads_cond->orWhere('cl_mobile','LIKE','%' . $general_search . '%');
            $leads_cond = $leads_cond->orWhere('cl_lead_description','LIKE','%' . $general_search . '%');
            $leads_cond = $leads_cond->orWhere('cl_area','LIKE','%' . $general_search . '%');
            $leads_cond = $leads_cond->orWhere('cl_region','LIKE','%' . $general_search . '%');
        }

        $leads_count = $leads_cond->count();


         $total_pages = ceil( $leads_count/$nbr_rows_per_pages );
         $total_pages = intval($total_pages);

         if(Config::get('appconfig.crm_telemarketing')  == 0)
             $lst_leads = $leads_cond->skip($skip)->take($nbr_rows_per_pages)->get();
         else
             $lst_leads = $leads_cond->orderBy('cl_date_creation','DESC')->get();

        $response_array = array();

        $data = array(
            "lst_leads" => $lst_leads
        );
        $response_array['is_error'] = 0;
        $response_array['total_pages'] = $total_pages;

        if(Config::get('appconfig.crm_telemarketing') == "0")
        {
            $response_array['display'] = view('leads.lstmanageleads',$data)->render();
        }
        else
        {
            $response_array['display'] = view('leads.listleads',$data)->render();
        }


        return Response()->json($response_array);
    }


    /**
     * Add Lead Result to database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function Addleadresult( Request $request )
    {
        $lr_lead_id = $request->input('lr_lead_ids');
        $lr_next_date = $request->input('lr_next_date');
        $lr_text_result = $request->input('lr_text_result');
        $cl_lead_notes = $request->input('cl_lead_notes');
        $result_array = array();

        $lead_info = CRMLeads::find($lr_lead_id);


        $lead_results                             = new CRMLeadResults();
        $lead_results->lr_lead_id                 = $lr_lead_id;
        $lead_results->lr_text_result             = $lr_text_result;
        $lead_results->lr_text_notes             = $cl_lead_notes;
        $lead_results->lr_next_call               = $lr_next_date;
        $lead_results->lr_result_date               = date('Y-m-d H:i:s');
        $lead_results->lr_telemarketing_id        = Session('user_id');
        $lead_results->lr_sales_id                = $lead_info->cl_sales_id;
        $lead_results->save();

        // change lead info
        $lead_info->cl_last_result_id = $lead_info->cl_lead_results;
        $lead_info->cl_lead_results = $lr_text_result;
        if($lr_text_result == 2)
        {
            $lead_info->cl_next_call_date = $lr_next_date;

        }
        else
        {
            $lead_info->cl_last_call_date = date("Y-m-d");
        }

        $lead_info->save();

        $result_array['is_error'] = 0;
        $result_array['new_result'] = $lr_text_result;
        $result_array['lr_lead_id'] = $lr_lead_id;
        $result_array['error_msg'] = "Operation Completed Successfully";
        return Response()->json($result_array);

    }


    public function GetLeadInfo(Request $request)
    {
        $lead_id = $request->input('lead_id');
        $lead_info = CRMLeads::find($lead_id);
        $result_array = array();
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        $result_array['data'] = array(
            "cl_id" => $lead_id,
            "cl_lead_results" => $lead_info->cl_lead_results,
            "cl_lead_notes" => $lead_info->cl_lead_notes,
            "cl_sales_id" => $lead_info->cl_sales_id,
            "cl_telemarketing_id" => $lead_info->cl_telemarketing_id,
            "cl_first_name" => $lead_info->cl_first_name,
            "cl_last_name" => $lead_info->cl_last_name,
            "cl_mobile" => $lead_info->cl_mobile,
            "cl_lead_description" => $lead_info->cl_lead_description,
            "cl_lead_status" => $lead_info->cl_lead_status,
            "cl_area" => $lead_info->cl_area,
            "cl_nbr_employees" => $lead_info->cl_nbr_employees,
            "cl_referred_by" => $lead_info->cl_referred_by
        );
        return Response()->json($result_array);
    }


    /**
     * Get Display List Lead Results
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetDisplayListLeadResults(Request $request)
    {
        $lead_id = $request->input('lead_id');
        $lead_info = CRMLeads::find($lead_id);

        $mobile_number = $lead_info->cl_mobile;

        $list_same_mobiles = CRMLeads::whereClMobile($mobile_number)->get();

        $lead_ids = array();
        foreach($list_same_mobiles as $mobile)
            $lead_ids[] = $mobile->cl_id;

        $lst_lead_results = CRMLeadResults::whereIn('lr_lead_id',$lead_ids)->whereLrIsDeleted(0)->orderBy('lr_id','DESC')->get();


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        $data = array(
            "lst_lead_results" => $lst_lead_results
        );
        $result_array['display'] = view('leads.listleadresults',$data)->render();
        return Response()->json($result_array);
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
        $lst_lead_types    = CRMLeadTypes::whereLtIsDeleted(0)->get();
        $lst_countries      = Countries::all();
        $lst_areas              = Areas::all();
        $lst_regions             = Regions::all();
        $lst_telemarketing = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();


        $crm_telemarketing  = Config::get('appconfig.crm_telemarketing');
        $data = array(
            'lead_categories' => $lead_categories,
            'lead_statuses' => $lead_statuses,
            'lst_users' => $lst_users,
            'lst_industries' => $lst_industries,
            'lst_telemarketing' => $lst_telemarketing,
            'lst_lead_types' => $lst_lead_types,
            'lst_areas' => $lst_areas,
            'lst_regions' => $lst_regions,
            'lst_sales' => $lst_sales,
            'lst_countries' => $lst_countries,
            'lst_lead_source' => $lst_lead_source
        );
        if($crm_telemarketing == '0')
            return Response()->view('leads.addlead',$data);
        else
            return Response()->view('leads.addtlead',$data);
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
        $lst_telemarketing = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_industries                 = Industry::whereSiIsDeleted(0)->get();
        $lst_lead_source                = CRMLeadSources::whereLsIsDeleted(0)->get();
        $lst_countries                  = Countries::all();
        $lst_areas              = Areas::all();
        $lst_regions             = Regions::all();
        $lst_service_categories         = CRMServiceCategories::whereScIsDeleted(0)->get();
        $crm_telemarketing              = Config::get('appconfig.crm_telemarketing');
        $lst_lead_types    = CRMLeadTypes::whereLtIsDeleted(0)->get();

        $data = array(
            'lead_categories' => $lead_categories,
            'lead_statuses' => $lead_statuses,
            'lst_users' => $lst_users,
            'lst_telemarketing' => $lst_telemarketing,
            'lst_sales' => $lst_sales,
            'lst_areas' => $lst_areas,
            'lst_regions' => $lst_regions,
            'lst_lead_types' => $lst_lead_types,
            'lst_industries' => $lst_industries,
            'lst_lead_source' => $lst_lead_source,
            'lst_countries' => $lst_countries,
            'lst_service_categories' => $lst_service_categories,
            'lead_info' => $lead_info
        );
        if($crm_telemarketing == '0')
            return Response()->view('leads.editlead',$data);
        else
            return Response()->view('leads.edittlead',$data);
    }


    public function GetRegionArea(Request $request)
    {
        $lr_area = $request->input('lr_area');



        $lst_regions = Regions::whereLrArea($lr_area)->get();
        $regions_array = array();

        foreach ($lst_regions as $key => $value)
        {
            $regions_array[$value->lr_region] = $value->lr_region;
        }


        $data = array(
            "html_array" => $regions_array,
            "name" => 'cl_region',
            "id" => 'CL_REGION'
        );

        $result_array['dropdown'] = view('html.dropdown',$data)->render();


        return Response()->json($result_array);
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
        $cl_region              = $request->input('cl_region');
        $cl_area              = $request->input('cl_area');
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

        $cl_referred_by       = $request->input('cl_referred_by');
        $cl_lead_type_id       = $request->input('cl_lead_type_id');
        $cl_sales_id       = $request->input('cl_sales_id');

        $cl_date_creation       = $request->input('cl_date_creation');
        $cl_type_items       = $request->input('cl_type_items');
        $cl_telemarketing_id       = $request->input('cl_telemarketing_id');
        $cl_sheet_number       = $request->input('cl_sheet_number');
        $cl_full_name       = $request->input('cl_full_name');
        $crm_telemarketing              = Config::get('appconfig.crm_telemarketing');
        if($cl_full_name != null)
        {
            $name = explode(' ', $cl_full_name);
           if(count($name) == 2)
           {
              $cl_first_name = $name[0];
                $cl_last_name = $name[1];
           }
            else if(count($name) == 3)
            {
                $cl_first_name = $name[0] . " " . $name[1];
                $cl_last_name = $name[2];
            }
           else if(count($name) == 1)
           {
               $cl_first_name = $name[0];
                $cl_last_name = "";
           }
           else
           {
                $cl_first_name = "";
                $cl_last_name = "";
           }

        }



        $is_new = true;
        $LeadInfo = new CRMLeads();
        $leads_obj = new LeadsManager();
        if($cl_id != null)
        {
            $LeadInfo = CRMLeads::find($cl_id);
            $is_new = false;
        }
        else{
             $count_exist_leads = CRMLeads::whereClMobile($cl_mobile)->whereClIsDeleted(0)->get();

            if(count($count_exist_leads) >= 1)
            {
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = "Phone Number Already Exist Please Add a new Phone Number";

                //return Response()->json($result_array);
            }
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
        $LeadInfo->cl_region            = $cl_region;
        $LeadInfo->cl_area              = $cl_area;
        $LeadInfo->cl_referred_by              = $cl_referred_by;
        $LeadInfo->cl_sales_id              = $cl_sales_id;
        $LeadInfo->cl_lead_type_id              = $cl_lead_type_id;
        $LeadInfo->cl_sheet_number              = $cl_sheet_number;
        $LeadInfo->cl_telemarketing_id              = $cl_telemarketing_id;

        if($is_new == true)
        {
            $LeadInfo->cl_date_creation     = $cl_date_creation;
        }

        $LeadInfo->save();

        if($crm_telemarketing == '0')
        {
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
        }


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);

    }


    public function CheckLeadExistByMobile(Request $request)
    {
        $cl_mobile = $request->input('cl_mobile');
        $leads_info = CRMLeads::whereClMobile($cl_mobile)->whereClIsDeleted(0)->get();

        $result_array = array();
        if(count($leads_info) == 0)
        {
            $result_array['is_error'] = 0;
            $result_array['error_msg'] = "Lead Not Exist, we can add it ";

            return Response()->json($result_array);
        }
        $result_array['is_error'] = 1;
        $data = array(
            'lst_leads' => $leads_info
        );
        $result_array['display'] = view('leads.displayexistinglead',$data)->render();

        return Response()->json($result_array);
    }

    /**
     * Quick function to create Lead from inbound call
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Response
     */
    public function QuickAddLead(Request $request)
    {
        $cl_customer_id         = $request->input('cl_customer_id');
        $cl_agent_id            = $request->input('cl_agent_id');
        $cl_mobile              = $request->input('cl_mobile');
        $cl_phone               = $request->input('cl_phone');
        $cl_email               = $request->input('cl_email');
        $cl_salesman_id         = $request->input('cl_salesman_id');
        $cl_first_name          = $request->input('cl_first_name');
        $cl_last_name           = $request->input('cl_last_name');
        $cl_lead_code           = $request->input('cl_lead_code');


         $count_exist_leads = CRMLeads::whereClMobile($cl_mobile)->whereClIsDeleted(0)->get();

        $result_array = array();
        if(count($count_exist_leads) >= 1)
        {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = "Phone Number Already Exist Please Add a new Phone Number";

            return Response()->json($result_array);
        }


        $lead_info = new CRMLeads();
        $lead_info->fk_lead_owner       = $cl_agent_id;
        $lead_info->cl_lead_code        = $cl_lead_code;
        $lead_info->cl_company_name     = "";
        $lead_info->fk_assign_to        = $cl_salesman_id;
        $lead_info->cl_referred_by      = $cl_customer_id;
        $lead_info->cl_first_name       = $cl_first_name;
        $lead_info->cl_last_name        = $cl_last_name;
        $lead_info->cl_mobile           = $cl_mobile;
        $lead_info->cl_email            = $cl_email;
        $lead_info->cl_phone            = $cl_phone;
        $lead_info->fk_lead_status_id   = 1;
        $lead_info->save();

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
