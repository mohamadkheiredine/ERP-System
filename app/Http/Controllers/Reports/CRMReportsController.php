<?php
/***********************************************************
CRMReportsController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
List Reports for CRM Module
***********************************************************/

namespace App\Http\Controllers\Reports;

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
use App\models\Users\Users;
use App\models\System\Industry;
use App\models\CRM\CRMLeadSources;
use App\models\System\Countries;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeads;
use App\models\CRM\CRMAccountTypes;
use App\models\CRM\CRMAccounts;


class CRMReportsController extends Controller
{
    
    /**
     * Page of Leads Reports
     * 
     * @author Moe Mantach
     * @access public
     */
    public function ListLeads()
    {
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_industries     = Industry::whereSiIsDeleted(0)->get();
        $lst_lead_source    = CRMLeadSources::whereLsIsDeleted(0)->get();
        $lst_countries      = Countries::all();
        $lead_categories    = CRMClientCategories::whereCcIsDeleted(0)->get();
        $lead_statuses      = CRMLeadStatus::whereLsIsDeleted(0)->get();
        
        $data = array(
            "lst_users" => $lst_users,
            "lst_industries" => $lst_industries,
            "lst_lead_source" => $lst_lead_source,
            "lead_categories" => $lead_categories,
            "lead_statuses" => $lead_statuses,
            "lst_countries" => $lst_countries
        );
        return Response()->view("reports.crm.leadsreport",$data);
    }
    
    
    /**
     * Page to display Page for Accounts Report
     * 
     * @author Moe Mantach
     * @access public
     * 
     */
    public function ListAccounts()
    {
        $lst_users              = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_account_categories = CRMClientCategories::whereCcIsDeleted(0)->get();
        $lst_account_types      = CRMAccountTypes::whereAtIsDeleted(0)->get();
        $lst_leads              = CRMLeads::whereClIsDeleted(0)->get();
        $lst_industries     = Industry::whereSiIsDeleted(0)->get();
        
        $data = array(
            "lst_users" => $lst_users,
            "lst_account_categories" => $lst_account_categories,
            "lst_industries" => $lst_industries,
            "lst_account_types" => $lst_account_types,
            "lst_leads" => $lst_leads, 
        );
        return Response()->view("reports.crm.accountsreport",$data);
    }
    
    
    /**
     * function to get report result for Accounts Report
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayAccountsReport(Request $request)
    {
        $account_category   = $request->input("account_category");
        $account_lead       = $request->input("account_lead");
        $account_owner      = $request->input("account_owner");
        $account_industry   = $request->input("account_industry");
        $account_date_from  = $request->input("account_date_from");
        $account_date_to    = $request->input("account_date_to");
        
        $result_array       = array();
        $report_accounts    = new CRMAccounts();
        $report_accounts_obj = CRMAccounts::whereCaIsDeleted(0);
        
        if( $account_category != null && count($account_category) > 0 )
        {
            $report_accounts_obj = $report_accounts_obj->whereIn("ca_account_category" , $account_category);
        }
        
        
        if( $account_lead != null && count( $account_lead ) > 0 )
        {
            $report_accounts_obj = $report_accounts_obj->whereIn("ca_lead_id" , $account_lead );
        }
        
        
        if( $account_owner != null && count( $account_owner ) > 0 )
        {
            $report_accounts_obj = $report_accounts_obj->whereIn("fk_account_owner_id" , $account_owner );
        }
        
        if( $account_industry != null && count( $account_industry ) > 0 )
        {
            $report_accounts_obj = $report_accounts_obj->whereIn("ca_account_industry" , $account_industry );
        }
        
        if($account_date_from != "")
        {
            $report_accounts_obj = $report_accounts_obj->where("ca_creation_date",">",$account_date_from);
        }
        
        if($account_date_to != "")
        {
            $report_accounts_obj = $report_accounts_obj->where("ca_creation_date","<",$account_date_to );
        }
        
        
        $report_accounts = $report_accounts_obj->get();
        
        
        $data = array(
            "report_accounts" => $report_accounts
        );
        $result_array['display'] = view("reports.crm.listaccounts",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * function to get report result based on parameters selected
     * @param Request $request
     * @return unknown
     */
    public function DisplayLeadReports(Request $request)
    {
        $lead_category  = $request->input("lead_category");
        $lead_status    = $request->input("lead_status");
        $lead_owner     = $request->input("lead_owner");
        $lead_industry  = $request->input("lead_industry");
        $lead_source    = $request->input("lead_source");
        $lead_date_from = $request->input("lead_date_from");
        $lead_date_to   = $request->input("lead_date_to"); 
        $result_array   = array(); 
        $report_leads = new CRMLeads();
        $report_leads_obj = CRMLeads::whereClIsDeleted(0)->whereClIsArchive(0);

        if($lead_category != null && count($lead_category) > 0)
        {
            $report_leads_obj = $report_leads_obj->whereIn("cl_category_id",$lead_category);
        }
   
        if($lead_status != null && count($lead_status) > 0)
        {
            $report_leads_obj = $report_leads_obj->whereIn("fk_lead_status_id",$lead_status);
        }
        
        if($lead_owner != null && count($lead_owner) > 0 )
        {
            $report_leads_obj = $report_leads_obj->whereIn("fk_assign_to",$lead_owner);
        }
        
        if($lead_industry != null && count($lead_industry) > 0 )
        {
            $report_leads_obj = $report_leads_obj->whereIn("fk_industry_id",$lead_industry);
        }

        
        if($lead_source != null && count($lead_source) > 0 )
        {
            $report_leads_obj = $report_leads_obj->whereIn("fk_lead_source_id",$lead_source);
        }
        
        
        if($lead_date_from != "")
        {
            $report_leads_obj = $report_leads_obj->where("cl_date_creation",">",$lead_date_from);
        }
        
        if($lead_date_to != "")
        {
            $report_leads_obj = $report_leads_obj->where("cl_date_creation","<",$lead_date_to);
        }
        
        $report_leads = $report_leads_obj->get();
    
        $data = array(
            "report_leads" => $report_leads
        );
        $result_array['display'] = view("reports.crm.listleads",$data)->render();
        
        return Response()->json($result_array);
    }
}