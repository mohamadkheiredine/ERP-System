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
use App\models\Billing\InvoicePayments;
use App\models\CallCenter\InboundCall;
use App\models\CallCenter\InboundCallProducts;
use App\models\System\Areas;
use App\models\System\Nationalities;
use App\models\System\PaperTypes;
use App\models\System\Regions;
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
use App\models\CRM\CRMContractTypes;
use App\models\Accounting\ChartAccounts;
use App\models\CRM\CRMDeals;



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
        $account_category       = $request->input("account_category");
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        $default_company_id = session('default_company_id');


        $accounts_cond = CRMAccounts::whereCaIsDeleted(0)->whereCaCompanyId($default_company_id);

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;

        if( $account_category > 0 )
        {
            $accounts_cond = $accounts_cond->whereCaAccountCategory($account_category);
        }

        if( strlen($general_search)  > 0)
        {
              $accounts_cond = $accounts_cond->where('ca_contract_code','LIKE','%' . $general_search . '%');
              $accounts_cond = $accounts_cond->orWhere('ca_account_name','LIKE','%' . $general_search . '%');
              $accounts_cond = $accounts_cond->orWhere('ca_account_phone','LIKE','%' . $general_search . '%');
              $accounts_cond = $accounts_cond->orWhere('ca_national_id','LIKE','%' . $general_search . '%');
              $accounts_cond = $accounts_cond->orWhere('ca_account_mobile','LIKE','%' . $general_search . '%');
              $accounts_cond = $accounts_cond->orWhere('ca_account_email','LIKE','%' . $general_search . '%');
        }



          $accounts_count = $accounts_cond->count();


         $total_pages = ceil( $accounts_count/$nbr_rows_per_pages );
         $total_pages = intval($total_pages);

        $lst_accounts = $accounts_cond->skip($skip)->take($nbr_rows_per_pages)->get();


        $response_array = array();

        $data = array(
            "lst_accounts" => $lst_accounts
        );
        $response_array['is_error'] = 0;
        $response_array['total_pages'] = $total_pages;
        $response_array['display'] = view('accounts.displaylist',$data)->render();

        return Response()->json($response_array);
    }



    public function GetAccountInfoByCode(Request $request)
    {
        $ad_account_code = $request->input('ad_account_code');
        $default_company_id = session('default_company_id');
        $lst_account_info = CRMAccounts::whereCaIsDeleted(0)->whereCaCompanyId($default_company_id)->where('ca_account_code','LIKE','%' . $ad_account_code . '%')->get();
        $result_array = array();

        if(count($lst_account_info) == 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'No Account Exist for this Account Code';
            return Response()->json($result_array);
        }

        $deal_info = CRMDeals::whereFkAccountId($lst_account_info[0]->ca_id)->whereAdIsDeleted(0)->get();

        $contract_type = $lst_account_info[0]->ca_contract_type;
        $ct_info = CRMContractTypes::find($contract_type);

        $result_array['is_error'] = 0;
        $result_array['account_info'] = array(
            'ca_account_name' => $lst_account_info[0]->ca_account_name,
            'ca_id' => $lst_account_info[0]->ca_id,
            'ca_billing_address' => $lst_account_info[0]->ca_billing_address,
            'ct_contract_type' => $ct_info->ct_contract_type,
        );

        if(count($deal_info) > 0)
        {
            $result_array['account_info']['ad_deal_code'] = $deal_info[0]->ad_deal_code;
        }

        return Response()->json($result_array);
    }




    /**
     * Page for add new account form
     *
     * @author Moe Mantach
     * @access public
     */
    public function AddForm()
    {
        $default_company_id = session('default_company_id');
        $lst_client_categories  = CRMClientCategories::whereCcIsDeleted(0)->get();
        $lst_users              = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereFkCompanyId($default_company_id)->get();
        $lst_leads              = CRMLeads::whereClIsArchive(0)->whereClIsDeleted(0)->get();
        $lst_areas              = Areas::all();
        $lst_regions             = Regions::all();
        $lst_accounts           = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_industry           = Industry::whereSiIsDeleted(0)->get();
        $lst_countries          = Countries::all();
        $lst_account_types      = CRMAccountTypes::whereAtIsDeleted(0)->get();
        $lst_contract_types      = CRMContractTypes::whereCtIsDeleted(0)->get();
        $crm_client_select_lead    = Config::get('appconfig.crm_client_select_lead');
        $crm_telemarketing      = Config::get('appconfig.quick_manage_client');
        $lst_nationalities = Nationalities::all();
        $lst_paper_types      = PaperTypes::wherePtIsDeleted(0)->get();

        $count_accounts = CRMAccounts::whereCaIsDeleted(0)->count();
        $count = $count_accounts + 1;
        $client_code = sprintf("%07d", $count);

        $data = array(
            "crm_client_select_lead" => $crm_client_select_lead,
            "lst_client_categories" => $lst_client_categories,
            "lst_paper_types" => $lst_paper_types,
            "lst_leads" => $lst_leads,
            "client_code" => $client_code,
            "lst_areas" => $lst_areas,
            "lst_regions" => $lst_regions,
            "lst_industry" => $lst_industry,
            "lst_accounts" => $lst_accounts,
            "lst_countries" => $lst_countries,
            "lst_nationalities" => $lst_nationalities,
            "lst_account_types" => $lst_account_types,
            "lst_contract_types" => $lst_contract_types,
            "lst_users" => $lst_users
        );

        if($crm_telemarketing == '0')
            return Response()->view("accounts.addform",$data);
        else
            return Response()->view("accounts.addtform",$data);

    }


    /**
     * View FIle Info For Account
     *
     * @author Moe Mantach
     * @param $ca_id
     * @return
     */
    public function ViewFile( $ca_id )
    {
        $client_info = CRMAccounts::find($ca_id);
        $lst_bills_unpaid = InvoicePayments::whereIpIsDeleted(0)->whereIpClientId($ca_id)->whereIpPaymentStatus(0)->get();
        $lst_bills_partial_paid = InvoicePayments::whereIpIsDeleted(0)->whereIpClientId($ca_id)->whereIpPaymentStatus(1)->get();
        $lst_bills_paid = InvoicePayments::whereIpIsDeleted(0)->whereIpClientId($ca_id)->whereIpPaymentStatus(2)->get();
        $lst_pendingcalls = InboundCall::whereIcIsDeleted(0)->whereIcClosedVoucher(0)->whereIcClientCode($client_info->ca_account_code)->get();
        $lst_closedcalls = InboundCall::whereIcIsDeleted(0)->whereIcClosedVoucher(1)->whereIcClientCode($client_info->ca_account_code)->get();
        $lst_call_products = InboundCallProducts::whereCpClientId($client_info->ca_id)->get();

        $data = array(
           "client_info" => $client_info,
           "lst_bills_unpaid" => $lst_bills_unpaid,
           "lst_bills_partial_paid" => $lst_bills_partial_paid,
           "lst_pendingcalls" => $lst_pendingcalls,
           "lst_call_products" => $lst_call_products,
           "lst_closedcalls" => $lst_closedcalls,
           "lst_bills_paid" => $lst_bills_paid
        );
        return Response()->view("accounts.viewaccountfile",$data);
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
        $lst_areas              = Areas::all();
        $lst_regions             = Regions::all();
        $lst_accounts           = CRMAccounts::whereCaIsDeleted(0)->where('ca_id', '<>', $ca_id)->get();
        $lst_account_types      = CRMAccountTypes::whereAtIsDeleted(0)->get();
        $lst_contract_types      = CRMContractTypes::whereCtIsDeleted(0)->get();
        $lst_paper_types      = PaperTypes::wherePtIsDeleted(0)->get();
        $crm_client_select_lead     = Config::get('appconfig.crm_client_select_lead');
        $crm_telemarketing          = Config::get('appconfig.quick_manage_client');
        $lst_nationalities = Nationalities::all();

        $data = array(
            "crm_client_select_lead" => $crm_client_select_lead,
            "lst_client_categories" => $lst_client_categories,
            "account_info" => $account_info,
            "lst_leads" => $lst_leads,
            "lst_areas" => $lst_areas,
            "lst_regions" => $lst_regions,
            "lst_industry" => $lst_industry,
            "lst_accounts" => $lst_accounts,
            "lst_countries" => $lst_countries,
            "lst_nationalities" => $lst_nationalities,
            "lst_account_types" => $lst_account_types,
            "lst_contract_types" => $lst_contract_types,
            "lst_paper_types" => $lst_paper_types,
            "lst_users" => $lst_users
        );
        if($crm_telemarketing == '0')
            return Response()->view("accounts.editform",$data);
        else
            return Response()->view("accounts.edittform",$data);
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
            "name" => 'ca_billing_region',
            "id" => 'ca_billing_region'
        );

        $result_array['dropdown'] = view('html.dropdown',$data)->render();


        return Response()->json($result_array);
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
        $ca_account_name        = $request->input("ca_account_name");
        $ca_company_name        = $request->input("ca_company_name");
        $ca_account_phone       = $request->input("ca_account_phone");
        $ca_account_website     = $request->input("ca_account_website");
        $ca_account_fax         = $request->input("ca_account_fax");
        $ca_account_email       = $request->input("ca_account_email");
        $ca_account_mobile      = $request->input("ca_account_mobile");
        $ca_account_site        = $request->input("ca_account_site");
        $ca_nbr_of_employees    = $request->input("ca_nbr_of_employees");
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
        $ca_contract_code = $request->input("ca_contract_code");
        $ca_contract_type = $request->input("ca_contract_type");
        $ca_ticker_symbol = $request->input("ca_ticker_symbol");
        $ca_national_id             = $request->input("ca_national_id");
        $ca_nationality_id             = $request->input("ca_nationality_id");
        $ca_billing_area            = $request->input("ca_billing_area");
        $ca_billing_house           = $request->input("ca_billing_house");
        $ca_billing_address         = $request->input("ca_billing_address");
        $ca_account_code          = $request->input("ca_account_code");
        $ca_billing_region          = $request->input("ca_billing_region");
        $ca_paper_type          = $request->input("ca_paper_type");
        $ca_location          = $request->input("ca_location");
        $ca_account_lat          = $request->input("ca_account_lat");
        $ca_account_long          = $request->input("ca_account_long");

        $ca_image_base_src      = "";
        $ca_image_file_name     = "";
        $ca_image_extension     = "";

        $AccountInfo = new CRMAccounts();
        $accounts_obj = new AccountsManager();
        if($ca_id != null)
        {
            $AccountInfo = CRMAccounts::find($ca_id);
        }

        if($ca_id == null)
        {
            $account_info   = ChartAccounts::where("aa_account_ref","=","4111")->get();
            $account_info = $account_info[0];

            $aa_account_ref = $account_info->aa_account . $ca_account_code;

            $accounts_info   = ChartAccounts::where("aa_account_ref","=","4121")->get();
            $accounts_info = $accounts_info[0];

            $aa_account_main = $accounts_info->aa_account . $ca_account_code;


             $AccAccounting = new ChartAccounts();
             $AccAccounting->aa_parent_account   = $account_info->aa_id;
             $AccAccounting->aa_account_ref      = $aa_account_ref;
             $AccAccounting->aa_account          = $aa_account_ref;
             $AccAccounting->aa_sub_account      = $account_info->aa_id;
             $AccAccounting->aa_account_label    = $ca_account_name;
             $AccAccounting->fk_country_id       = 0;
             $AccAccounting->save();
             $aa_id = $AccAccounting->aa_id;
             $AccountInfo->ca_accounting_id = $aa_id;

            $AccAccounting = new ChartAccounts();
            $AccAccounting->aa_parent_account   = $accounts_info->aa_id;
            $AccAccounting->aa_account_ref      = $aa_account_main;
            $AccAccounting->aa_account          = $aa_account_main;
            $AccAccounting->aa_sub_account      = $accounts_info->aa_id;
            $AccAccounting->aa_account_label    = $ca_account_name . " Maintenance Account";
            $AccAccounting->fk_country_id       = 0;
            $AccAccounting->save();
            $aa_id = $AccAccounting->aa_id;
            $AccountInfo->ca_maintenance_account = $aa_id;

        }

        // upload file to the CRM photo
        if(count($_FILES) > 0 )
        {
            $image_data =  $accounts_obj->UploadAvatarAccount($ca_id);

            $AccountInfo->ca_image_base_src    = $image_data['data']['ca_image_base_src'];
            $AccountInfo->ca_image_file_name   = $image_data['data']['ca_image_file_name'];
            $AccountInfo->ca_image_extension   = $image_data['data']['ca_image_extension'];

        }
        $default_company_id = session('default_company_id');
        $AccountInfo->fk_account_owner_id       = $fk_account_owner_id;
        $AccountInfo->ca_company_id       = $default_company_id;
        $AccountInfo->ca_lead_id                = $ca_lead_id;
        $AccountInfo->ca_parent_account         = $ca_parent_account;
        $AccountInfo->ca_account_category       = $ca_account_category;
        $AccountInfo->ca_account_name           = $ca_account_name;
        $AccountInfo->ca_company_name           = $ca_company_name;
        $AccountInfo->ca_account_phone          = $ca_account_phone;
        $AccountInfo->ca_account_website        = $ca_account_website;
        $AccountInfo->ca_account_fax            = $ca_account_fax;
        $AccountInfo->ca_account_email          = $ca_account_email;
        $AccountInfo->ca_account_mobile         = $ca_account_mobile;
        $AccountInfo->ca_account_site           = $ca_account_site;
        $AccountInfo->ca_ticker_symbol          = $ca_ticker_symbol;
        $AccountInfo->ca_nbr_of_employees       = $ca_nbr_of_employees;
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
        $AccountInfo->ca_contract_code          = $ca_contract_code;
        $AccountInfo->ca_contract_type          = $ca_contract_type;
        $AccountInfo->ca_national_id            = $ca_national_id;
        $AccountInfo->ca_billing_area            = $ca_billing_area;
        $AccountInfo->ca_billing_region            = $ca_billing_region;
        $AccountInfo->ca_billing_house            = $ca_billing_house;
        $AccountInfo->ca_billing_address            = $ca_billing_address;
        $AccountInfo->ca_account_code            = $ca_account_code;
        $AccountInfo->ca_nationality_id            = $ca_nationality_id;
        $AccountInfo->ca_paper_type            = $ca_paper_type;
        $AccountInfo->ca_location            = $ca_location;
        $AccountInfo->ca_account_lat            = $ca_account_lat;
        $AccountInfo->ca_account_long            = $ca_account_long;
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
