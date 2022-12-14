<?php
/***********************************************************
SuppliersController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\SRM;

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
use App\models\SRM\SupplierCategories;
use App\models\SRM\SupplierStatus;
use App\models\SRM\Suppliers;
use App\models\Accounting\ChartAccounts;
use App\Library\SuppliersManager;
use App\models\Users\Users;
use App\models\System\Countries;
use App\models\System\Industry;



class SuppliersController extends Controller
{

    /**
     * 
     * @return unknown
     */
    public function index()
    {
        $lst_supplier_statuses      = SupplierStatus::whereSsIsDeleted(0)->get();
        $lst_supplier_categories    = SupplierCategories::whereScIsDeleted(0)->get();
        
        $data = array(
            "lst_supplier_statuses" => $lst_supplier_statuses,
            "lst_supplier_categories" => $lst_supplier_categories,
        );
        return Response()->view('srm.suppliers',$data);
    }
    
    
   /**
    * Display list of Suppliers saved in the database
    * 
    * @author Moe Mantach
    * @access public
    * @param Request $request
    * @return unknown
    */
    public function DisplayList(Request $request)
    {         
        $supplier_categories_array   = array();
        $lst_supplier_categories     = SupplierCategories::whereScIsDeleted(0)->get();
        foreach ( $lst_supplier_categories as $key => $sc_info ) 
        {
            $supplier_categories_array[ $sc_info->sc_id ] =  $sc_info->sc_category_title;
        } 
        $supplier_category = $request->input("supplier_category");
        $supplier_status = $request->input("supplier_status");
        $lst_suppliers = Suppliers::whereSsIsDeleted(0);
        
        if(strlen($supplier_category) > 0)
            $lst_suppliers = $lst_suppliers->whereFkCategoryId($supplier_category);
        
        if(strlen($supplier_status) > 0)
            $lst_suppliers = $lst_suppliers->whereFkStatusId($supplier_status);
        
        $lst_suppliers = $lst_suppliers->get();
        $data = array(
            "supplier_categories_array" => $supplier_categories_array,
            "lst_suppliers" => $lst_suppliers
        );
        
        $result_array = array();
        
        $result_array['display'] = view("srm.displaylist",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Category
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $lst_srm_categories         = SupplierCategories::whereScIsDeleted(0)->get();
        $lst_supplier_statuses      = SupplierStatus::whereSsIsDeleted(0)->get();
        $lst_users                  = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_accounts               = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_countries              = Countries::all();
        $lst_industries             = Industry::all();
        
        $data = array(
            "lst_srm_categories" => $lst_srm_categories,
            "lst_supplier_statuses" => $lst_supplier_statuses,
            "lst_accounts" => $lst_accounts,
            "lst_industries" => $lst_industries,
            "lst_countries" => $lst_countries,
            "lst_users" => $lst_users
        );
        return view('srm.addform',$data);
    }
    
    
    /**
     * Save Supplier Categories 
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveSupplierInfo(Request $request)
    {
        $result_array = array();
        $ss_id                      = $request->input("ss_id");
        $fk_category_id             = $request->input("fk_category_id"); 
        $fk_owner_id                = $request->input("fk_owner_id");
        $ss_supplier_name           = $request->input("ss_supplier_name");
        $ss_company_name            = $request->input("ss_company_name");
        $ss_supplier_description    = $request->input("ss_supplier_description");
        $ss_supplier_phone          = $request->input("ss_supplier_phone");
        $ss_supplier_mobile         = $request->input("ss_supplier_mobile");
        $ss_supplier_fax            = $request->input("ss_supplier_fax");
        $ss_supplier_email          = $request->input("ss_supplier_email");
        $ss_skype_id                = $request->input("ss_skype_id");
        $ss_twitter_account         = $request->input("ss_twitter_account");
        $ss_facebook_id             = $request->input("ss_facebook_id");
        $ss_country_id              = $request->input("fk_country_id");
        $ss_city_name               = $request->input("ss_city_name");
        $ss_address                 = $request->input("ss_address");
        $ss_zip_code                = $request->input("ss_zip_code");
        $ss_street_name             = $request->input("ss_street_name");
        $ss_date_creation           = date("Y-m-d");
        $ss_industry                = $request->input("fk_industry_id");
        $ss_sale_account_id         = $request->input("ss_sale_account_id");
        $ss_purchase_account_id     = $request->input("ss_purchase_account_id");

        $ss_logo_base_src       = "";
        $ss_logo_file_name      = "";
        $ss_logo_file_extension = "";
        
        $SupplierInfo = new Suppliers();
        $supplierManager = new SuppliersManager();
        if($ss_id != null)
        {
            $SupplierInfo = Suppliers::find($ss_id);
        }
        
        
        
        // upload file to the CRM photo
        if(count($_FILES) > 0 )
        {
            $image_data =  $supplierManager->UploaSupplierLogo($ss_id);
            
            $SupplierInfo->ss_logo_base_src         = $image_data['data']['ss_logo_base_src'];
            $SupplierInfo->ss_logo_file_name        = $image_data['data']['ss_logo_file_name'];
            $SupplierInfo->ss_logo_file_extension   = $image_data['data']['ss_logo_file_extension'];
            
        }
        
        $SupplierInfo->fk_category_id           = $fk_category_id; 
        $SupplierInfo->fk_owner_id              = $fk_owner_id;
        $SupplierInfo->ss_supplier_name         = $ss_supplier_name;
        $SupplierInfo->ss_company_name          = $ss_company_name;
        $SupplierInfo->ss_supplier_description  = $ss_supplier_description;
        $SupplierInfo->ss_supplier_phone        = $ss_supplier_phone;
        $SupplierInfo->ss_supplier_mobile       = $ss_supplier_mobile;
        $SupplierInfo->ss_supplier_fax          = $ss_supplier_fax;
        $SupplierInfo->ss_supplier_email        = $ss_supplier_email;
        $SupplierInfo->ss_skype_id              = $ss_skype_id;
        $SupplierInfo->ss_twitter_account       = $ss_twitter_account;
        $SupplierInfo->ss_facebook_id           = $ss_facebook_id;
        $SupplierInfo->ss_country_id            = $ss_country_id;
        $SupplierInfo->ss_city_name             = $ss_city_name;
        $SupplierInfo->ss_address               = $ss_address;
        $SupplierInfo->ss_zip_code              = $ss_zip_code;
        $SupplierInfo->ss_street_name           = $ss_street_name;
        $SupplierInfo->ss_date_creation         = $ss_date_creation;
        $SupplierInfo->ss_industry              = $ss_industry;
        $SupplierInfo->ss_sale_account_id       = $ss_sale_account_id;
        $SupplierInfo->ss_purchase_account_id   = $ss_purchase_account_id;
        $SupplierInfo->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
    
    
    
    /**
     * Save Account Accounting and link it to the current customer
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveAccAccounting(Request $request )
    { 
        $parent_account     = $request->input("parent_account");
        $account_label      = $request->input("account_label");
        $country_id         = session("company_country");
        $result_array       = array();
        
        $acc_info = ChartAccounts::find($parent_account);
        
        
        $count_ref_account = ChartAccounts::whereAaAccountRef($parent_account)->count();
        
        
        // check if this account exist
        $account_info = ChartAccounts::whereAaParentAccount($parent_account)->get();

        $new_count = count($account_info) + 1;
        
        $aa_account_ref = $acc_info->aa_account_ref. (String)$new_count;
        $AccAccounting = new ChartAccounts();
        $AccAccounting->aa_parent_account   = $parent_account;
        $AccAccounting->aa_account_ref      = $aa_account_ref;
        $AccAccounting->aa_account          = $aa_account_ref;
        $AccAccounting->aa_sub_account      = $parent_account;
        $AccAccounting->aa_account_label    = $account_label;
        $AccAccounting->fk_country_id       = $country_id;
        $AccAccounting->save();
        
        $aa_id = $AccAccounting->aa_id;
        
        
        $result_array['is_error']           = 0;
        $result_array['error_msg']          = "Operation Complete Successfully";
        $result_array['accounting_label']   = $account_label;
        $result_array['aa_id']              = $aa_id;
        $result_array['account_ref']        = $aa_account_ref;
        return Response()->json($result_array);
    }
    
    
    /**
     * Edit Form Page 
     * @param unknown $ss_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $ss_id )
    {
        $supplier_info              = Suppliers::find($ss_id);
        $lst_srm_categories         = SupplierCategories::whereScIsDeleted(0)->get();
        $lst_supplier_statuses      = SupplierStatus::whereSsIsDeleted(0)->get();
        $lst_users                  = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_accounts               = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_countries              = Countries::all();
        $lst_industries             = Industry::all();
        
        $data = array(
            "lst_srm_categories" => $lst_srm_categories,
            "lst_supplier_statuses" => $lst_supplier_statuses,
            "lst_accounts" => $lst_accounts,
            "supplier_info" => $supplier_info,
            "lst_industries" => $lst_industries,
            "lst_countries" => $lst_countries,
            "lst_users" => $lst_users
        );
        return view('srm.editform',$data);
    }
    
    
    /**
     * Delete Supplier from the database by change flag of the row
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteSupplierInfo(Request $request)
    {
        
        $ss_id= $request->input('ss_id');
         
        $supplier_info = Suppliers::find( $ss_id);
        $supplier_info->ss_is_deleted          = 1;
        $supplier_info->ss_deleted_by          = Session('user_id');
        $supplier_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}