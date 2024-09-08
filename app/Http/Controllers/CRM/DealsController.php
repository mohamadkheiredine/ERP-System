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
use App\models\CRM\CRMDealProducts;
use App\library\AccountingManager;
use App\models\Billing\InvoicePayments;

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
    
    
       public function GetProductInfo( Request $request )
    {
        $product_id         = $request->input('product_id');
        
        $result_array        = array();
        $product_array       = array();
        
        $product_info = Products::find($product_id);
        
        $product_array['product_id']                   = $product_id;
        $product_array['reference']                   = $product_info->p_product_ref;
        $product_array['p_barcode']                   = $product_info->p_barcode;
        $product_array['p_barcode_img']               = $product_info->p_barcode_img;
        $product_array['p_product_name']              = $product_info->p_product_name;
        $product_array['category_id']                 = $product_info->fk_pc_id;
        $product_array['category_name']               = $product_info->Category->pc_category;
        $product_array['p_product_selling_price']     = $product_info->p_product_selling_price;
        $product_array['p_product_cost_price']     = $product_info->p_product_cost_price;
        
        
        $product_array['p_product_tax_rate']          = $product_info->p_product_tax_rate;
        $product_array['currency_code']               = $product_info->Currency->cc_currency_code;
        $product_array['currency']                    = $product_info->p_product_currency;
        
        $image_src_url  = url('/')."/".Config::get('constants.PRODUCTS_PATH').$product_info->p_product_profile_base_src.$product_info->p_product_profile_file_name.".".$product_info->p_product_profile_extention;
        $image_src_path = public_path(). "/" .Config::get('constants.PRODUCTS_PATH').$product_info->p_product_profile_base_src.$product_info->p_product_profile_file_name.".".$product_info->p_product_profile_extention;
        if(strlen($product_info->p_product_profile_base_src) > 0 ){
            $img_src = $image_src_url;
        }else{
            $img_src = url('images/NoImageAvailable.jpg');
        }
        
        $product_array['product_avatar']   = $img_src; 
        
        
        $result_array['is_error']       = 0;
        $result_array['product_info']   = $product_array;
        
        unset($product_array);
        $product_array = null;
        
        return Response()->json($result_array);
        
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
        $ad_currency_id             = $request->input('ad_currency_id');
        $ad_down_payment            = $request->input('ad_down_payment');
        $ad_nbr_of_payments         = $request->input('ad_nbr_of_payments');
        $ad_is_approved         = $request->has('ad_is_approved') ? 1 : 0;
        $deals                      = $request->input('deals');
        
        $result_array = array();
        
        
        // validate that code exist 
        
        $count_deals = CRMDeals::whereAdIsDeleted(0)->whereAdDealCode($ad_deal_code)->count();
        if($count_deals == 0)
        {
            $result_array['is_error'] = 0;
            $result_array['error_msg'] ="CRM Deal Code Not Exist !!" ;
            
            return Response()->json($result_array);
        }
        
        // Validate 
         
        $account_deal = new CRMDeals();
        if($ad_id != null)
        {
            $account_deal= CRMDeals::find($ad_id);
        }
         
        $account_deal->fk_account_id         =   $fk_account_id;
        $account_deal->fk_contact_id         =   $fk_contact_id;
        $account_deal->fk_lead_id            =   $fk_lead_id;
        $account_deal->ad_deal_code          =   $ad_deal_code;
        $account_deal->ad_deal_title         =   $ad_deal_title;
        $account_deal->ad_deal_description   =   $ad_deal_description;
        $account_deal->ad_deal_owner         =   $ad_deal_owner;
        $account_deal->ad_deal_amount        =   $ad_deal_amount;
        $account_deal->ad_closing_date       =   $ad_closing_date;
        $account_deal->ad_deal_stage         =   $ad_deal_stage;
        $account_deal->ad_deal_type          =   $ad_deal_type;
        $account_deal->ad_deal_probability   =   $ad_deal_probability;
        $account_deal->ad_next_step          =   $ad_next_step;
        $account_deal->ad_expected_revenue   =   $ad_expected_revenue;
        $account_deal->fk_sales_id           =   $fk_sales_id;
        $account_deal->fk_telemarketing_id   =   $fk_telemarketing_id;
        $account_deal->ad_currency_id        =   $ad_currency_id;
        $account_deal->ad_down_payment       =   $ad_down_payment;
        $account_deal->ad_nbr_of_payments    =   $ad_nbr_of_payments;
        $account_deal->ad_is_approved        =   $ad_is_approved;
        
 
        $account_deal->save();
        $ad_id = $account_deal->ad_id;
        
        
        // save product deals
        if(strlen($deals) > 0)
        {
            $product_deals = explode(",", $deals);
            $ad_id = $account_deal->ad_id;
            
            $delete_product = CRMDealProducts::whereDpDealId($ad_id)->delete();
            foreach ($product_deals as $key => $product_id) 
            {
                $d_products_obj = new CRMDealProducts();
                $d_products_obj->dp_deal_id = $ad_id;
                $d_products_obj->dp_product_id = $product_id;
                $d_products_obj->save();
            }
        }
        
        // when approve create invoice and generate receipts and payment for all number of
        // payments
        if($ad_is_approved > 0)
        {
            // Create Accounting Account
            $crm_account = CRMAccounts::find($fk_account_id);
            
            
                $account_info   = ChartAccounts::where("aa_account_ref","=","41")->get();
                $account_info = $account_info[0];

                $count   = ChartAccounts::where("aa_account_ref","LIKE","41%")->count();

                $new_count      = $count + 1;
                $aa_account_ref = $account_info->aa_account . (String)$new_count;

                $AccAccounting = new ChartAccounts();
                $AccAccounting->aa_parent_account   = $account_info->aa_id;
                $AccAccounting->aa_account_ref      = $aa_account_ref;
                $AccAccounting->aa_account          = $aa_account_ref;
                $AccAccounting->aa_sub_account      = $account_info->aa_id;
                $AccAccounting->aa_account_label    = $crm_account->ca_account_name;
                $AccAccounting->fk_country_id       = 0;
                $AccAccounting->save(); 
                $aa_id = $AccAccounting->aa_id;
            
                
                $creation_date = date("Y-m-d H:i:s");
                $company_id = Session('company_id');
                $AccountingManager = AccountingManager();
                $params_array = array(
                    'company_id' => $company_id
                );
               $invoice_code = $AccountingManager->GenerateInvoiceCode($params_array); 
                $invoice_info = new Invoices();
                $invoice_info->bi_invoice_ref       = $invoice_code;
                $invoice_info->bi_invoice_code      = $invoice_code;
                $invoice_info->fk_account_id        = $aa_id;
                $invoice_info->fk_customer_id       = 0;
                $invoice_info->bi_invoice_date      = $creation_date;
                $invoice_info->bi_due_date          = $creation_date;
                $invoice_info->bi_payment_terms     = 1;
                $invoice_info->bi_payment_type      = 2;
                $invoice_info->bi_invoice_note      = $ad_deal_description;
                $invoice_info->bi_total_cost        = $ad_deal_amount;
                $invoice_info->bi_vat_id            = 1;
                $invoice_info->bi_discount          = 0;
                $invoice_info->bi_total_price       = $ad_deal_amount;
                $invoice_info->bi_invoice_currency  = $ad_currency_id;
                $invoice_info->bi_invoice_note      = "New Invoice #" . $invoice_code;
                $invoice_info->bi_invoice_paid      = 1;
                $invoice_info->bi_number_payments   = 1;
                $invoice_info->save();
                $bi_id = $invoice_info->bi_id;

                $lst_deal_items = CRMDealProducts::whereDpDealId($ad_id)->get();
                foreach ($lst_deal_items as $key => $item_info ) 
                {
                    $invoice_items = new InvoiceProducts();
                    
                    
                    $invoice_items->fk_invoice_id        = $bi_id;
                    $invoice_items->ii_item_id           = -1;
                    $invoice_items->ii_stock_id          = -1;
                    $invoice_items->ii_item_type         = 1;
                    $invoice_items->ii_item_label        = $item_info->Product->p_product_name;
                    $invoice_items->ii_item_price        = $item_info->Product->p_product_selling_price;
                    $invoice_items->ii_item_qyt          = 1;
                    $invoice_items->ii_price_currency    = $ad_currency_id;
                    $invoice_items->save();
                }
                
                
                // Save invoice Payments 
                $remaining_amount = $ad_deal_amount - $ad_down_payment;
                $payment_amount = $remaining_amount / $ad_nbr_of_payments;
                
                $percentage_amount = ( $payment_amount/$ad_deal_amount ) * 100;
                
                $downpayment_percentage = ($ad_down_payment/$ad_deal_amount ) * 100;
                
                
                // create invoice down payment 
                $invoice_payment = new InvoicePayments();
                $invoice_payment->fk_invoice_id = $bi_id;
                $invoice_payment->ip_payment_percentage = $downpayment_percentage;
                $invoice_payment->ip_payment_amount = $ad_down_payment;
                $invoice_payment->ip_payment_type = 1;
                $invoice_payment->ip_payment_label = "Downpayment of Deal Code #" . $ad_deal_code;
                $invoice_payment->save();
                
                for ($index = 1; $index < $ad_nbr_of_payments - 1; $index++) 
                {
                    $invoice_payment = new InvoicePayments();
                    $invoice_payment->fk_invoice_id = $bi_id;
                    $invoice_payment->ip_payment_percentage = $percentage_amount;
                    $invoice_payment->ip_payment_amount = $percentage_amount;
                    $invoice_payment->ip_payment_type = 1;
                    $invoice_payment->ip_payment_label = "Payment of Deal Code #" . $ad_deal_code;
                    $invoice_payment->save();
                }
                
        }
        
        
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
        $lst_deal_stages    = CRMDealStages::whereCsIsDeleted(0)->get();
        $lst_products       = Products::wherePProductIsDeleted(0)->get();
        $lst_currencies     = Currency::all();
        
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
            "lst_user_sales" => $lst_sales,
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