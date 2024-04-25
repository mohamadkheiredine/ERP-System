<?php
/***********************************************************
VendorsController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 22, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\Http\Controllers\Inventory;

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
use App\models\Inventory\Vendors;
use App\models\Inventory\WareHouses;
use App\models\System\Industry;
use Maatwebsite\Excel\Facades\Excel;
use App\models\CRM\CRMServiceCategories;
use App\library\VendorsManager;
use App\models\System\Countries;
use App\models\Accounting\VatAccounts;
use App\models\Accounting\ChartAccounts;


class VendorsController extends Controller
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
        
        $data = array();
        return Response()->view("vendors.vendors",$data);
    }


    /**
     * Display list of the Vendors based on selected fields
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
        
        $lst_vendors_obj    = Vendors::whereIvIsDeleted(0);
        $lst_vendors_obj    = $lst_vendors_obj->get();
        

        $response_array = array();

        $data = array(
            "lst_vendors_obj" => $lst_vendors_obj
        );
        $response_array['is_error'] = 0;
        $response_array['display'] = view('vendors.displaylist',$data)->render();

        return Response()->json($response_array);
    }

    /**
     * Open form of add new vendor
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function AddForm()
    {
        $lst_warehouses     = WareHouses::whereWIsDeleted(0)->get();
        $lst_countries      = Countries::all();
        $lst_vat_tax        = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->get();
        $VendorManagement   = new VendorsManager();
        $vendor_code = $VendorManagement->GenerateVendorCode();
        
        
        $data = array(
            'lst_warehouses' => $lst_warehouses,
            'vendor_code' => $vendor_code,
            'lst_accounts' => $lst_accounts,
            'lst_countries' => $lst_countries,
            'lst_vat_tax' => $lst_vat_tax
        );

        return Response()->view('vendors.addform',$data);
    }

    /**
     * get information of selected vendor and open the edit form fields
     * @param unknown $iv_id
     * @return \Illuminate\Http\Response
     */
    public function EditForm($iv_id)
    {
        $vendor_info        = Vendors::find($iv_id);
        $lst_warehouses    = WareHouses::whereWIsDeleted(0)->get();
        $lst_countries      = Countries::all();
        $lst_vat_tax       = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->get();
        
        $data = array(
            'lst_warehouses' => $lst_warehouses,
            'vendor_info' => $vendor_info,
            'lst_countries' => $lst_countries,
            'lst_vat_tax' => $lst_vat_tax,
            'lst_accounts' => $lst_accounts
        );
        
        return Response()->view('vendors.editform',$data);

    }


    /**
     * function to save data of Vendor to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveVendorInfo(Request $request)
    { 
        
        $iv_id                  = $request->input('iv_id');
        $iv_warehouse_id        = $request->input('iv_warehouse_id');
        $iv_vendor_name         = $request->input('iv_vendor_name');
        $iv_vendor_description  = $request->input('iv_vendor_description');
        $iv_vendor_code         = $request->input('iv_vendor_code');
        $iv_vendor_address      = $request->input('iv_vendor_address');
        $iv_vendor_country      = $request->input('iv_vendor_country');
        $iv_vendor_email        = $request->input('iv_vendor_email');
        $iv_vendor_website      = $request->input('iv_vendor_website');
        $iv_vendor_phone        = $request->input('iv_vendor_phone');
        $iv_vendor_mobile       = $request->input('iv_vendor_mobile');
        $iv_image_base_src      = $request->input('iv_image_base_src');
        $iv_image_file_name     = $request->input('iv_image_file_name');
        $iv_image_extension     = $request->input('iv_image_extension');
        $iv_vendor_sales_tax    = $request->input('iv_vendor_sales_tax');
        $iv_vendor_tax_id       = $request->input('iv_vendor_tax_id');
      //  $iv_vendor_account_id   = $request->input('iv_vendor_account_id');
 
        $VendorInfo = new Vendors();
        $VendorManager = new VendorsManager(); 
        if($iv_id != null)
        {
            $VendorInfo = Vendors::find( $iv_id );
        }
        else 
        {
            $VendorInfo->iv_date_creation = date("Y-m-d");
            
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
            $AccAccounting->aa_account_label    = $iv_vendor_name;
            $AccAccounting->fk_country_id       = 0;
            $AccAccounting->save();
            $aa_id = $AccAccounting->aa_id;
            $VendorInfo->iv_vendor_account_id = $aa_id;
            
        }
        
        // upload file to the CRM photo
        if(count($_FILES) > 0 )
        {
            $image_data =  $VendorManager->UploadVendorsAvatar($iv_id); 
            $VendorInfo->iv_image_base_src      = $image_data['data']['iv_image_base_src'];
            $VendorInfo->iv_image_file_name     = $image_data['data']['iv_file_name'];
            $VendorInfo->iv_image_extension     = $image_data['data']['iv_file_extension'];
            
        }
        
        
        $VendorInfo->iv_warehouse_id        = $iv_warehouse_id; 
        $VendorInfo->iv_vendor_name         = $iv_vendor_name; 
        $VendorInfo->iv_vendor_description  = $iv_vendor_description; 
        $VendorInfo->iv_vendor_code         = $iv_vendor_code; 
        $VendorInfo->iv_vendor_address      = $iv_vendor_address; 
        $VendorInfo->iv_vendor_country      = $iv_vendor_country; 
        $VendorInfo->iv_vendor_email        = $iv_vendor_email; 
        $VendorInfo->iv_vendor_website      = $iv_vendor_website; 
        $VendorInfo->iv_vendor_phone        = $iv_vendor_phone; 
        $VendorInfo->iv_vendor_mobile       = $iv_vendor_mobile; 
        $VendorInfo->iv_vendor_sales_tax    = $iv_vendor_sales_tax; 
        $VendorInfo->iv_vendor_tax_id       = $iv_vendor_tax_id; 

        
        
        
        $VendorInfo->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);

    }
    
    
    /**
     * Save Vendor in the database and if exist we update the existing information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveMainVendorInfo( Request $request )
    {
        $user_id                = Session('user_id');
        $vendor_id              = $request->input('vendor_id');
        $iv_vendor_name         = $request->input('iv_vendor_name');
        $iv_vendor_address      = $request->input('iv_vendor_address');
        $iv_vendor_email        = $request->input('iv_vendor_email');
        $iv_vendor_website      = $request->input('iv_vendor_website');
        $iv_vendor_phone        = $request->input('iv_vendor_phone');
        $iv_vendor_mobile       = $request->input('iv_vendor_mobile');
        $user_info              = Users::find($user_id);
        
        $result_array        = array();
        $customer_array      = array();
        
        
        $vendors_manager = new VendorsManager();
        $params = array(
            'company_id' => $user_info->fk_company_id
        );
        $iv_vendor_code = $vendors_manager->GenerateVendorCode($params);
        
        if($vendor_id!= 0)
        {
            $vendor_info = Vendors::find($vendor_id);
        }
        else
        {
            $vendor_info= new Vendors();
        }
        
        
        
        $vendor_info->iv_vendor_code    = $iv_vendor_code;
        $vendor_info->iv_vendor_name    = $iv_vendor_name;
        $vendor_info->iv_vendor_address = $iv_vendor_address;
        $vendor_info->iv_vendor_email   = $iv_vendor_email;
        $vendor_info->iv_vendor_website = $iv_vendor_website;
        
        
        if(count($_FILES) > 0 )
        {
            $image_data =  $vendors_manager->UploadVendorsAvatar($vendor_id);
            $vendor_info->iv_image_base_src      = $image_data['data']['iv_image_base_src'];
            $vendor_info->iv_image_file_name     = $image_data['data']['iv_file_name'];
            $vendor_info->iv_image_extension     = $image_data['data']['iv_file_extension'];
            
        }
        
        $vendor_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['vendor_id']  = $vendor_info->iv_id;
        $result_array['vendor_name']  = $iv_vendor_name;
        return Response()->json($result_array);
    }
    
    
     
    
    /**
     * Delete Vendor  info and check all condition before begin deleted
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteVendorInfo(Request $request)
    {
        $iv_id = $request->input('iv_id');
        $result_array = array();

        
        $vendor_info = Vendors::find($iv_id);
        $vendor_info->iv_is_deleted   = 1;
        $vendor_info->iv_deleted_by   = session('user_id');
        $vendor_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
}