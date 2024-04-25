<?php
/***********************************************************
VendorsController.php
Product :
Version : 1.0
Release : 1
Date Created : May 29, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Config;
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
use App\models\Inventory\Customers;
use App\models\Inventory\WareHouses;
use App\models\System\Industry;
use Maatwebsite\Excel\Facades\Excel;
use App\models\CRM\CRMServiceCategories;
use App\library\CustomersManager;
use App\models\System\Countries;
use App\models\Accounting\VatAccounts;
use App\models\Inventory\Vendors;
use App\models\Accounting\ChartAccounts;
use App\models\Accounting\DefaultAccounts;
use App\library\VendorsManager;


class VendorsController extends Controller
{
    
    
    /**
     * get list of vendors saved in the database
     * 
     * @author Moe Mantach
     * @access public
     * 
     * @return json result_array
     */
    public function GetListVendors(Request $request)
    {
        
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $vendor_search     = $request->input('vendor_search');
        $user_info           = Users::find($user_id);
        
        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256',$c_hash);
        $result_array        = array();
        
        
        // validate hash sequence for loggedin user
        if( $c_hash != $g_hash )
        {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
            
            return Response()->json($result_array);
        }
        
        
        $lst_vendors_obj    = Vendors::whereIvIsDeleted(0);
        if(strlen($vendor_search) > 0)
            $lst_vendors_obj = $lst_vendors_obj->where('iv_vendor_name','LIKE','%' . $vendor_search. '%');
            $lst_vendors_obj    = $lst_vendors_obj->get();
        $result_array = array();
        $vendors_array = array();
        
        foreach ($lst_vendors_obj as $index => $vendor_info) 
        {
            $vendors_array[$index]['iv_id']             = $vendor_info->iv_id;
            $vendors_array[$index]['vendor_code']       = $vendor_info->iv_vendor_code;
            $vendors_array[$index]['vendor_name']       = $vendor_info->iv_vendor_name;
            $vendors_array[$index]['vendor_address']    = $vendor_info->iv_vendor_address;
            $vendors_array[$index]['vendor_email']      = $vendor_info->iv_vendor_email;
            $vendors_array[$index]['vendor_website']    = $vendor_info->iv_vendor_website;
            $vendors_array[$index]['vendor_phone']      = $vendor_info->iv_vendor_phone;
            $vendors_array[$index]['vendor_mobile']     = $vendor_info->iv_vendor_mobile;
            $vendors_array[$index]['vendor_account_id'] = $vendor_info->iv_vendor_account_id;
            $vendors_array[$index]['vendor_account_title'] = $vendor_info->accounts->aa_account_label;
            
            $image_src_url  = url('/')."/".Config::get('constants.VENDORS_PATH').$vendor_info->iv_image_base_src.$vendor_info->iv_image_file_name.".".$vendor_info->iv_image_extension;
            $image_src_path = public_path(). "/" .Config::get('constants.VENDORS_PATH').$vendor_info->iv_image_base_src.$vendor_info->iv_image_file_name.".".$vendor_info->iv_image_extension;
            if(strlen($vendor_info->iv_image_base_src) > 0 ){
                $img_src = $image_src_url;
            }else{
                $img_src = url('images/NoImageAvailable.jpg');
            }
            
            $vendors_array[$index]['vendor_profile']    = $img_src;
        }
        
        $result_array['is_error']               = 0;
        $result_array['vendors_array']        = $vendors_array;
        
        return Response()->json($result_array);
    }
    
    /**
     * get vendor info of a vendor_id and send it in the json response
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * 
     * @return JSON $result_array
     */
    public function GetVendorInfo(Request $request)
    {
        $user_id             = $request->input('user_id');
        $vendor_id           = $request->input('vendor_id');
        $g_hash              = $request->input('g_hash');
        $user_info           = Users::find($user_id);
        
        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256',$c_hash);
        $result_array        = array();
        $vendor_array        = array();
        
        // validate hash sequence for loggedin user
        if( $c_hash != $g_hash )
        {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
            
            return Response()->json($result_array);
        }
        
        $vendor_info = Vendors::find($vendor_id);
        
        $vendor_array['iv_id']             = $vendor_info->iv_id;
        $vendor_array['vendor_code']       = $vendor_info->iv_vendor_code;
        $vendor_array['vendor_name']       = $vendor_info->iv_vendor_name;
        $vendor_array['vendor_address']    = $vendor_info->iv_vendor_address;
        $vendor_array['vendor_email']      = $vendor_info->iv_vendor_email;
        $vendor_array['vendor_website']    = $vendor_info->iv_vendor_website;
        $vendor_array['vendor_phone']      = $vendor_info->iv_vendor_phone;
        $vendor_array['vendor_mobile']     = $vendor_info->iv_vendor_mobile;
        $vendor_array['vendor_account_id'] = $vendor_info->iv_vendor_account_id;
        $vendor_array['vendor_account_title'] = $vendor_info->accounts->aa_account_label;
        
        $image_src_url  = url('/')."/".Config::get('constants.VENDORS_PATH').$vendor_info->iv_image_base_src.$vendor_info->iv_image_file_name.".".$vendor_info->iv_image_extension;
        $image_src_path = public_path(). "/" .Config::get('constants.VENDORS_PATH').$vendor_info->iv_image_base_src.$vendor_info->iv_image_file_name.".".$vendor_info->iv_image_extension;
        if(strlen($vendor_info->iv_image_base_src) > 0 ){
            $img_src = $image_src_url;
        }else{
            $img_src = url('images/NoImageAvailable.jpg');
        }
        
        $vendors_array['vendor_profile']    = $img_src;
        
        $result_array['is_error']       = 0;
        $result_array['vendor_info']  = $vendor_array;
        
        unset($vendor_array);
        $vendor_array= null;
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Save Vendor in the database and if exist we update the existing information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveVendorInfo( Request $request )
    {
        $user_id                = $request->input('user_id'); 
        $vendor_id              = $request->input('vendor_id');
        $iv_vendor_name         = $request->input('iv_vendor_name');
        $iv_vendor_address      = $request->input('iv_vendor_address');
        $iv_vendor_email        = $request->input('iv_vendor_email');
        $iv_vendor_website      = $request->input('iv_vendor_website');
        $iv_vendor_phone        = $request->input('iv_vendor_phone');
        $iv_vendor_mobile       = $request->input('iv_vendor_mobile');
        $g_hash                 = $request->input('g_hash');
        $user_info              = Users::find($user_id);
        
        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256',$c_hash);
        $result_array        = array();
        $customer_array      = array();
        
        // validate hash sequence for loggedin user
        if( $c_hash != $g_hash )
        {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
            
            return Response()->json($result_array);
        }
        
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
            $vendor_info->iv_vendor_account_id = $aa_id;
            
        }
        
        
        
        $vendor_info->iv_vendor_code    = $iv_vendor_code;
        $vendor_info->iv_vendor_name    = $iv_vendor_name;
        $vendor_info->iv_vendor_address = $iv_vendor_address;
        $vendor_info->iv_vendor_email   = $iv_vendor_email;
        $vendor_info->iv_vendor_website = $iv_vendor_website;
        $vendor_info->iv_vendor_phone = $iv_vendor_phone;
        $vendor_info->iv_vendor_mobile = $iv_vendor_mobile;
        
        
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
     * Delete Vendor from the database by changing flag iv_is_deleted to 1
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteVendor(Request $request)
    {
        $vendor_id           = $request->input('vendor_id');
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $user_info           = Users::find($user_id);
        
        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256',$c_hash);
        $result_array        = array(); 
        
        // validate hash sequence for loggedin user
        if( $c_hash != $g_hash )
        {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
            
            return Response()->json($result_array);
        }
        
        $vendor_info  = Vendors::find($vendor_id);
        $vendor_inf->iv_is_deleted = 1;
        $vendor_info->iv_deleted_by = $user_id;
        $vendor_info->save();
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Completed Successfully";
        
        return Response()->json($result_array);
    }
    
}