<?php
/***********************************************************
LeadItemsController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 15, 2019
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
use App\models\CRM\CRMLeadNotes;
use Config;
use App\models\CRM\CRMLeadItems;
use App\models\CRM\CRMServices;
use App\models\CRM\CRMServiceCategories;


class LeadItemsController extends Controller
{
 
    
    /**
     * Insert Lead items and link it to the lead , complete configuration
     * 
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function InsertLeadItems(Request $request)
    {
        $cl_id = $request->input("cl_id");
        $sc_id =  $request->input("sc_id");
        $ss_id =  $request->input("ss_id");
        $result_array = array();
        
        $start_date = date("Y-m-d");
        $end_date = date("Y-m-d");
        
        $LeadItems = new CRMLeadItems();
        $LeadItems->fk_lead_id      = $cl_id;
        $LeadItems->fk_category_id  = $sc_id;
        $LeadItems->fk_item_id      = $ss_id;
        $LeadItems->ci_start_date   = $start_date;
        $LeadItems->ci_end_date     = $end_date;
        $LeadItems->save();
        
        $result_array['ci_id']      = $LeadItems->ci_id;
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return response()->json($result_array);
    }
    
    /**
     * open main page of item configuration for
     * selected lead
     * 
     * @author Moe Mantach
     * @access public
     * @param unknown $ci_id
     */
    public function LeadItemConfigurations( $ci_id )
    {
        $CRMLeadItem    = CRMLeadItems::find($ci_id);
        $lst_leads      = CRMLeads::whereClIsDeleted(0)->get();
        $lead_id        = $CRMLeadItem->fk_lead_id;
        
        $LeadInfo                   = CRMLeads::find($lead_id);
        $LeadService                = CRMServices::find($ci_id);
        $lst_service_categories     = CRMServiceCategories::whereScIsDeleted(0)->get();
        $lst_services               = CRMServices::whereFkCategoryId($CRMLeadItem->fk_category_id)->get();
        
        
        $data = array(
            "LeadInfo" => $LeadInfo,
            "CRMLeadItem" => $CRMLeadItem,
            "lst_leads" => $lst_leads,
            "lst_service_categories" => $lst_service_categories,
            "lst_services" => $lst_services,
            "LeadService" => $LeadService
        );
        
        return response()->view("leads.editleaditeminfo",$data);
    }
    
    
    
    
    /**
     * Save Lead Item Info
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveLeadItemInfo(Request $request)
    {
        $result_array           = array();
        $ci_id                  = $request->input("ci_id");
        $fk_lead_id             = $request->input("fk_lead_id");
        $fk_category_id         = $request->input("fk_category_id");
        $fk_item_id             = $request->input("fk_item_id");
        $ci_start_date          = $request->input("ci_start_date");
        $ci_end_date            = $request->input("ci_end_date");
        $ci_item_nbr_of_hours   = $request->input("ci_item_nbr_of_hours");
        $ci_total_cost          = $request->input("ci_total_cost");
        $ci_total_price         = $request->input("ci_total_price");
        $ci_item_description    = $request->input("ci_item_description");
        
        
        $ci_start_date = strtotime($ci_start_date);
        $ci_start_date = date("Y-m-d",$ci_start_date);
        
        $ci_end_date = strtotime($ci_end_date);
        $ci_end_date = date("Y-m-d",$ci_end_date);
        
        $LeadItems = CRMLeadItems::find($ci_id);
        
        $LeadItems->fk_lead_id              = $fk_lead_id;
        $LeadItems->fk_category_id          = $fk_category_id;
        $LeadItems->fk_item_id              = $fk_item_id;
        $LeadItems->ci_start_date           = $ci_start_date;
        $LeadItems->ci_item_nbr_of_hours    = $ci_item_nbr_of_hours;
        $LeadItems->ci_total_cost           = $ci_total_cost;
        $LeadItems->ci_total_price          = $ci_total_price;
        $LeadItems->ci_end_date             = $ci_end_date;
        $LeadItems->ci_item_description     = $ci_item_description;
        $LeadItems->save();
        
        $result_array['is_error']   = 0;
        $result_array['lead_id']    = $fk_lead_id;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return response()->json($result_array);
    }
    
    /**
     * Display List of Services applyied to this lead
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayLeadServicestab(Request $request)
    {
        $cl_id = $request->input("cl_id");
        $result_array = array();
        
        $LeadServices = CRMLeadItems::whereFkLeadId($cl_id)->whereCiIsDeleted(0)->get();
        
        $lst_services = CRMServices::whereCsIsDeleted(0)->get();
        $services_array = CreateDatabaseArrayByIndex($lst_services , "cs_id");
        
        $data = array(
            'LeadServices' => $LeadServices,
            'services_array' => $services_array
        );
        
        $result_array['is_error'] = 0;
        $result_array['display'] = view('leads.displaylistservices',$data)->render();
        
        return response()->json($result_array);
    }
    
    
    /**
     * Delete Lead Service from the database if we can 
     * 
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function DeleteLeadServiceinfo(Request $request)
    {
        $ci_id = $request->input('ci_id');
        
        $result_array = array();
        
        $CRMLeadItem = CRMLeadItems::find($ci_id);
        $CRMLeadItem->ci_is_deleted = 1;
        $CRMLeadItem->ci_deleted_by= session("user_id");
        $CRMLeadItem->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return response()->json($result_array);
    }
    
}