<?php
/***********************************************************
DealsController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 29, 2019
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
use App\models\CRM\CRMDeals;
use App\models\CRM\CRMLeads;
use App\models\CRM\CRMContacts;
use App\models\Users\Users;
use App\models\CRM\CRMDealStages;
use App\models\Inventory\Products;
use App\models\Users\UserTypes;
use App\models\System\Currency;


class DealsController extends Controller
{

    /**
     * Page to control Client Categories Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        
        $lst_accounts = CRMAccounts::whereCaIsDeleted(0)->get();
        
        $data = array(
            "lst_accounts" => $lst_accounts
        );
        return Response()->view('accounts.deals',$data);
    }
    
    
    /**
     * Display list of Account Deals
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
   public function DisplayList(Request $request)
    {
        
        $ad_account             = $request->input("ad_account");
        $general_search         = $request->input("general_search");
        $page_number            = $request->input("page_number");
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
            
        
         if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
        
        
        $lst_deals_cond = CRMDeals::whereAdIsDeleted(0);
        
        if( $ad_account > 0 )
        {
            $lst_deals_cond = $lst_deals_cond->whereFkAccountId($ad_account);
        }
        
        $deals_count = $lst_deals_cond->count();
       
        
         $total_pages = ceil( $deals_count/$nbr_rows_per_pages );
         $total_pages = intval($total_pages);
             
         $lst_account_deals = $lst_deals_cond->skip($skip)->take($nbr_rows_per_pages)->get();
          $lst_accounts   = CRMAccounts::whereCaIsDeleted(0)->get();
         
        $accounts_array   = array(); 
        
        foreach ( $lst_accounts as $key => $account_info ) 
        {
            $accounts_array[ $account_info->ca_id ] =  $account_info->ca_account_name;
        } 
        $data = array(
            "lst_account_deals" => $lst_account_deals,
            "accounts_array" => $accounts_array,
        );
        
        $result_array = array();
         
        $result_array['display'] = view("accounts.listdeals",$data)->render();
        $result_array['total_pages'] = $total_pages;
        
        return Response()->json($result_array);
    }
    
     
    
    /**
     * Function of Adding a new Account Deals
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {      
        $lst_accounts   = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_leads      = CRMLeads::whereClIsDeleted(0)->get();
        $lst_contacts   = CRMContacts::whereCcIsDeleted(0)->get();
        $lst_users      = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_deal_stages = CRMDealStages::whereCsIsDeleted(0)->get();
        $lst_products = Products::wherePProductIsDeleted(0)->get();
        $lst_currencies = Currency::all();
        
        $lst_telemarketing = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        
        
        
        $data = array(
            "lst_accounts" => $lst_accounts,
            "lst_products" => $lst_products,
            "lst_leads" => $lst_leads,
            "lst_users" => $lst_users,
            "lst_deal_stages" => $lst_deal_stages,
            "lst_user_telemarketing" => $lst_telemarketing,
            "lst_user_sales" => $lst_sales,
            "lst_currencies" => $lst_currencies,
            "lst_contacts" => $lst_contacts
        );
        return Response()->view('accounts.adddeals',$data);
    }
    
    
    /**
     * Save Client Category Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveDealsInfo(Request $request)
    {
        $ad_id                      = $request->input('ad_id');
        $fk_account_id              = $request->input('fk_account_id');
        $fk_contact_id              = $request->input('fk_contact_id');
        $fk_lead_id                 = $request->input('fk_lead_id');
        $ad_deal_code               = $request->input('ad_deal_code');
        $ad_deal_title              = $request->input('ad_deal_title');
        $ad_deal_description        = $request->input('ad_deal_description');
        $ad_deal_owner              = $request->input('ad_deal_owner');
        $ad_deal_amount             = $request->input('ad_deal_amount');
        $ad_closing_date            = $request->input('ad_closing_date');
        $ad_closing_date            = date("Y-m-d",strtotime($ad_closing_date));
        $ad_deal_stage              = $request->input('ad_deal_stage');
        $ad_deal_type               = $request->input('ad_deal_type');
        $ad_deal_probability        = $request->input('ad_deal_probability');
        $ad_next_step               = $request->input('ad_next_step');
        $ad_expected_revenue        = $request->input('ad_expected_revenue');
        $fk_sales_id                = $request->input('fk_sales_id');
        $fk_telemarketing_id        = $request->input('fk_telemarketing_id');
        $ad_currency_id        = $request->input('ad_currency_id');
        
        $result_array = array();
         
        $AccountDeal = new CRMDeals();
        if($ad_id != null)
        {
            $AccountDeal= CRMDeals::find($ad_id);
        }
         
        $AccountDeal->fk_account_id         =   $fk_account_id;
        $AccountDeal->fk_contact_id         =   $fk_contact_id;
        $AccountDeal->fk_lead_id            =   $fk_lead_id;
        $AccountDeal->ad_deal_code          =   $ad_deal_code;
        $AccountDeal->ad_deal_title         =   $ad_deal_title;
        $AccountDeal->ad_deal_description   =   $ad_deal_description;
        $AccountDeal->ad_deal_owner         =   $ad_deal_owner;
        $AccountDeal->ad_deal_amount        =   $ad_deal_amount;
        $AccountDeal->ad_closing_date       =   $ad_closing_date;
        $AccountDeal->ad_deal_stage         =   $ad_deal_stage;
        $AccountDeal->ad_deal_type          =   $ad_deal_type;
        $AccountDeal->ad_deal_probability   =   $ad_deal_probability;
        $AccountDeal->ad_next_step          =   $ad_next_step;
        $AccountDeal->ad_expected_revenue   =   $ad_expected_revenue;
        $AccountDeal->fk_sales_id           =   $fk_sales_id;
        $AccountDeal->fk_telemarketing_id   =   $fk_telemarketing_id;
        $AccountDeal->ad_currency_id        =   $ad_currency_id;
        
 
        $AccountDeal->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Account Deal Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Account Deals Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $cc_id
     */
    public function EditForm( $ad_id )
    {
        $lst_accounts   = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_leads      = CRMLeads::whereClIsDeleted(0)->get();
        $lst_contacts   = CRMContacts::whereCcIsDeleted(0)->get();
        $deal_info      = CRMDeals::find($ad_id);
        $lst_users      = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_deal_stages = CRMDealStages::whereCsIsDeleted(0)->get();
        $lst_products = Products::wherePProductIsDeleted(0)->get();
        $lst_currencies = Currency::all();
        
        $lst_telemarketing = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        
        $data = array(
            "lst_accounts" => $lst_accounts,
            "lst_leads" => $lst_leads,
            "lst_products" => $lst_products,
            "deal_info" => $deal_info,
            "lst_users" => $lst_users,
            "lst_deal_stages" => $lst_deal_stages,
            "lst_currencies" => $lst_currencies,
            "lst_user_telemarketing" => $lst_telemarketing,
            "lst_deal_stages" => $lst_deal_stages,
            "lst_contacts" => $lst_contacts
        );
        return Response()->view('accounts.editdeals',$data);
    }
    
    
    /**
     * Delete Account Deal
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteDealsInfo(Request $request)
    {
        
        $ad_id= $request->input('ad_id');
         
        $deal_info = CRMDeals::find( $ad_id);
        $deal_info->ad_is_deleted           = 1;
        $deal_info->ad_deleted_by           = Session('user_id');
        $deal_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}