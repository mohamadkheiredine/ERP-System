<?php
/***********************************************************
AccountsController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 22, 2019
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
use App\library\ClientsCategoriesManager;
use App\models\CRM\CRMAccounts;
use App\models\Users\Users;
use App\models\CRM\CRMLeads;
use App\models\System\Industry;
use App\models\System\Countries;
use App\models\CRM\CRMAccountTypes;
use App\library\AccountsManager;
use App\library\LeadsStatusManager;
use App\models\CRM\CRMLeadSources;



class AccountsController extends Controller
{

    public function index()
    {
        $lst_client_categories = CRMClientCategories::whereCcIsDeleted(0)->get();
        $data = array(
            "lst_client_categories" => $lst_client_categories
        );
        return Response()->view('accounts.accounts',$data);
    }
    
    /**
     * display list of clients saved in the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayList(Request $request)
    {
        $account_category = $request->input("account_category"); 
        
        $lst_accounts_obj = CRMAccounts::whereCaIsDeleted(0);
        
        if( $account_category > 0 )
        {
            $lst_accounts_obj = $lst_accounts_obj->whereCaAccountCategory($account_category);
        }
        

        
        $lst_accounts = $lst_accounts_obj->get();
        
        
        $response_array = array();
        
        $data = array(
            "lst_accounts" => $lst_accounts
        );
        $response_array['is_error'] = 0;
        $response_array['display'] = view('accounts.displaylist',$data)->render();
        
        return Response()->json($response_array);
    }
    
    
    
    /**
     * Page for add new account form 
     * 
     * @author Moe Mantach
     * @access public
     */
    public function AddForm()
    {
        $lst_client_categories  = CRMClientCategories::whereCcIsDeleted(0)->get();
        $lst_users              = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_leads              = CRMLeads::whereClIsArchive(0)->whereClIsDeleted(0)->get();
        $lst_accounts           = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_industry           = Industry::whereSiIsDeleted(0)->get();
        $lst_countries          = Countries::all();
        $lst_account_types      = CRMAccountTypes::whereAtIsDeleted(0)->get();
         
        
        $data = array(
            "lst_client_categories" => $lst_client_categories,
            "lst_leads" => $lst_leads,
            "lst_industry" => $lst_industry,
            "lst_accounts" => $lst_accounts,
            "lst_countries" => $lst_countries,
            "lst_account_types" => $lst_account_types,
            "lst_users" => $lst_users
        );
        return Response()->view("accounts.addform",$data);
    }
    
    
    /**
     * Page for Edit Form 
     * @param unknown $ca_id
     */
    public function EditForm( $ca_id )
    {
        $lst_client_categories  = CRMClientCategories::whereCcIsDeleted(0)->get();
        $account_info           = CRMAccounts::find($ca_id);
        $lst_users              = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_leads              = CRMLeads::whereClIsArchive(0)->whereClIsDeleted(0)->get();
        $lst_industry           = Industry::whereSiIsDeleted(0)->get();
        $lst_countries          = Countries::all();
        $lst_accounts           = CRMAccounts::whereCaIsDeleted(0)->where('ca_id', '<>', $ca_id)->get();
        $lst_account_types      = CRMAccountTypes::whereAtIsDeleted(0)->get();
        
        $data = array(
            "lst_client_categories" => $lst_client_categories,
            "account_info" => $account_info,
            "lst_leads" => $lst_leads,
            "lst_industry" => $lst_industry,
            "lst_accounts" => $lst_accounts,
            "lst_countries" => $lst_countries,
            "lst_account_types" => $lst_account_types,
            "lst_users" => $lst_users
        );
        return Response()->view("accounts.editform",$data);
    }

    
    /**
     * Save Account Info and Upload Image Profile of this Account to the Main Server
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveAccountInfo(Request $request)
    {
        $result_array = array();
        $ca_id                  = $request->input("ca_id");
        $fk_account_owner_id    = $request->input("fk_account_owner_id");
        $ca_lead_id             = $request->input("ca_lead_id");
        $ca_parent_account      = $request->input("ca_parent_account");
        $ca_account_category    = $request->input("ca_account_category");
        $ca_account_type_id     = $request->input("ca_account_type_id");
        $ca_account_ownership   = $request->input("ca_account_ownership");
        $ca_account_rating      = $request->input("ca_account_rating");
        $ca_account_name        = $request->input("ca_account_name");
        $ca_company_name        = $request->input("ca_company_name");
        $ca_account_phone       = $request->input("ca_account_phone");
        $ca_account_website     = $request->input("ca_account_website");
        $ca_account_fax         = $request->input("ca_account_fax");
        $ca_account_email       = $request->input("ca_account_email");
        $ca_account_mobile      = $request->input("ca_account_mobile");
        $ca_account_site        = $request->input("ca_account_site");
        $ca_account_number      = $request->input("ca_account_number");
        $ca_ticker_symbol       = $request->input("ca_ticker_symbol");
        $ca_nbr_of_employees    = $request->input("ca_nbr_of_employees");
        $ca_annual_revenue      = $request->input("ca_annual_revenue");
        $ca_account_sic_code    = $request->input("ca_account_sic_code");
        $ca_billing_country     = $request->input("ca_billing_country");
        $ca_billing_city        = $request->input("ca_billing_city");
        $ca_billing_state       = $request->input("ca_billing_state");
        $ca_billing_code        = $request->input("ca_billing_code");
        $ca_billing_street      = $request->input("ca_billing_street");
        $ca_shipping_country    = $request->input("ca_shipping_country");
        $ca_shipping_city       = $request->input("ca_shipping_city");
        $ca_shipping_state      = $request->input("ca_shipping_state");
        $ca_shipping_code       = $request->input("ca_shipping_code");
        $ca_shipping_street     = $request->input("ca_shipping_street");
        $ca_account_description = $request->input("ca_account_description");
        
        $ca_image_base_src      = "";
        $ca_image_file_name     = "";
        $ca_image_extension     = "";
        
        $AccountInfo = new CRMAccounts();
        $accounts_obj = new AccountsManager();
        if($ca_id != null)
        {
            $AccountInfo = CRMAccounts::find($ca_id); 
        }
     
        
        
        // upload file to the CRM photo
        if(count($_FILES) > 0 )
        {
            $image_data =  $accounts_obj->UploadAvatarAccount($ca_id);
            
            $AccountInfo->ca_image_base_src    = $image_data['data']['ca_image_base_src'];
            $AccountInfo->ca_image_file_name   = $image_data['data']['ca_image_file_name'];
            $AccountInfo->ca_image_extension   = $image_data['data']['ca_image_extension'];
            
        }
        
        $AccountInfo->fk_account_owner_id       = $fk_account_owner_id;
        $AccountInfo->ca_lead_id                = $ca_lead_id;
        $AccountInfo->ca_parent_account         = $ca_parent_account;
        $AccountInfo->ca_account_category       = $ca_account_category;
        $AccountInfo->ca_account_type_id        = $ca_account_type_id;
        $AccountInfo->ca_account_ownership      = $ca_account_ownership;
        $AccountInfo->ca_account_rating         = $ca_account_rating;
        $AccountInfo->ca_account_name           = $ca_account_name;
        $AccountInfo->ca_company_name           = $ca_company_name;
        $AccountInfo->ca_account_phone          = $ca_account_phone;
        $AccountInfo->ca_account_website        = $ca_account_website;
        $AccountInfo->ca_account_fax            = $ca_account_fax;
        $AccountInfo->ca_account_email          = $ca_account_email;
        $AccountInfo->ca_account_mobile         = $ca_account_mobile;
        $AccountInfo->ca_account_site           = $ca_account_site;
        $AccountInfo->ca_account_number         = $ca_account_number;
        $AccountInfo->ca_ticker_symbol          = $ca_ticker_symbol;
        $AccountInfo->ca_nbr_of_employees       = $ca_nbr_of_employees;
        $AccountInfo->ca_annual_revenue         = $ca_annual_revenue;
        $AccountInfo->ca_account_sic_code       = $ca_account_sic_code;
        $AccountInfo->ca_billing_country        = $ca_billing_country;
        $AccountInfo->ca_billing_city           = $ca_billing_city;
        $AccountInfo->ca_billing_state          = $ca_billing_state;
        $AccountInfo->ca_billing_code           = $ca_billing_code;
        $AccountInfo->ca_billing_street         = $ca_billing_street;
        $AccountInfo->ca_shipping_country       = $ca_shipping_country;
        $AccountInfo->ca_shipping_city          = $ca_shipping_city;
        $AccountInfo->ca_shipping_state         = $ca_shipping_state;
        $AccountInfo->ca_shipping_code          = $ca_shipping_code;
        $AccountInfo->ca_shipping_street        = $ca_shipping_street;
        $AccountInfo->ca_account_description    = $ca_account_description;
        $AccountInfo->save();
       
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
        
    }
    
    
    /**
     * Delete Account Info from the database by changing the flag from 0 of is_deleted from 0 to 1
     * @param Request $request
     */
    public function DeleteAccountInfo(Request $request)
    {
        $ca_id = $request->input('ca_id');
        $result_array = array();
        
        
        $account_info = CRMAccounts::find($ca_id);
        $account_info->ca_is_deleted   = 1;
        $account_info->ca_deleted_by   = session('user_id');
        $account_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
    /**
     * Convert list of leads to accounts and  complete all the configurations related
     * 
     * @author Moe Mantach
     * @access public
     * @param unknown $cl_ids
     */
    public function ConvertToAccounts( $cl_ids )
    {
       
        $data = array(
            "cl_ids" => $cl_ids
        );
       return response()->view("leads.converttoaccounts",$data);
    }
    
    
    
    public function ConvertLeadtoAccount(Request $request)
    {
        $cl_ids = $request->input("al_ids");
        $lead_ids_array = explode(",", $cl_ids);
        $result_array = array();
        $lst_leads_info = CRMLeads::whereIn('cl_id',$lead_ids_array)->get();
        
        foreach ( $lst_leads_info as $li_index => $li_info )
        {
            
            // check if lead is not converted to client is stop and receive the notificaiton
            if($li_info->fk_lead_status_id != LeadsStatusManager::STATUS_CONVERSION_TO_CLIENT)
            {
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = "Invalid Status,you need to select conversion to client status";
                
                return Response()->json($result_array);
            }
           
            $cl_id =  $li_info->cl_id;
            $AccountObj = new CRMAccounts();
            $AccountObj->fk_account_owner_id    =  $li_info->fk_lead_owner;
            $AccountObj->ca_lead_id             =  $li_info->cl_id;
            $AccountObj->ca_parent_account      =  0;
            $AccountObj->ca_account_category    =  1;
            $AccountObj->ca_account_rating      =  0;
            $AccountObj->ca_account_name        =  $li_info->cl_first_name . " " . $li_info->cl_last_name;
            $AccountObj->ca_company_name        =  $li_info->cl_company_name;
            $AccountObj->ca_account_description =  $li_info->cl_lead_description;
            $AccountObj->ca_account_phone       =  $li_info->cl_phone;
            $AccountObj->ca_account_website     =  $li_info->cl_website;
            $AccountObj->ca_account_fax         =  $li_info->cl_fax;
            $AccountObj->ca_account_email       =  $li_info->cl_email;
            $AccountObj->ca_account_mobile      =  $li_info->cl_mobile;
            $AccountObj->ca_account_site        =  "";
            $AccountObj->ca_ticker_symbol       =  "";
            $AccountObj->ca_account_type_id     =  1;
            $AccountObj->ca_account_ownership   =  $li_info->fk_lead_owner;
            $AccountObj->ca_account_industry    =  $li_info->fk_industry_id;
            $AccountObj->ca_nbr_of_employees    =  $li_info->cl_nbr_employees;
            $AccountObj->ca_annual_revenue      =  $li_info->cl_anual_revenue;
            $AccountObj->ca_account_sic_code    =  "";
            $AccountObj->ca_image_base_src      =  $li_info->cl_image_base_src;
            $AccountObj->ca_image_file_name     =  $li_info->cl_image_file_name;
            $AccountObj->ca_image_extension     =  $li_info->cl_image_extension;
            $AccountObj->ca_billing_country     =  $li_info->cl_country_id;
            $AccountObj->ca_billing_city        =  $li_info->cl_city;
            $AccountObj->ca_billing_state       =  $li_info->cl_state;
            $AccountObj->ca_billing_code        =  $li_info->cl_zip_code;
            $AccountObj->ca_billing_street      =  $li_info->cl_street_name;
            $AccountObj->ca_shipping_country    =  $li_info->cl_country_id;
            $AccountObj->ca_shipping_city       =  $li_info->cl_city;
            $AccountObj->ca_shipping_state      =  $li_info->cl_state;
            $AccountObj->ca_shipping_code       =  $li_info->cl_zip_code;
            $AccountObj->ca_shipping_street     =  $li_info->cl_street_name;
            $AccountObj->save();
            
           // change status of lead to converted to client
            $lead_converted = CRMLeads::find($cl_id);
            $lead_converted->fk_lead_status_id = LeadsStatusManager::STATUS_CONVERTED_TO_CLIENT;
            $lead_converted->save();
            
            $lead_converted = null;
            unset($lead_converted);
        }
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        
        return Response()->json($result_array);
    }
}