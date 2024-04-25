<?php
/***********************************************************
SuppliersController.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Oct 3, 2023
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2023

Page Description :
{Enter page description Here}
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
use App\models\SRM\Suppliers;


class SuppliersController  extends Controller
{
    
    
    /**
     * get list of vendors saved in the database
     * 
     * @author Moe Mantach
     * @access public
     * 
     * @return json result_array
     */
    public function GetListSuppliers(Request $request)
    {
        
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $current_page        = $request->input('current_page');
        
        
        $nbr_rows_per_pages    = 10;
        if($current_page > 1)
            $skip = ( $current_page - 1 ) * $nbr_rows_per_pages ;
            else
                $skip = 0;
                
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
                
                
                $suppliers = array();
                
                $suppliers_cond = Suppliers::where('ss_is_deleted',0); 
                
                $suppliers_count = $suppliers_cond->count();
                
                $total_pages = ceil( $suppliers_count/$nbr_rows_per_pages );
                $total_pages = intval($total_pages);
                
                $lst_suppliers = $suppliers_cond->skip($skip)->take($nbr_rows_per_pages)->get();
                
                
                foreach ( $lst_suppliers as $key => $supplier_info )
                {
                    $suppliers[ $supplier_info->ss_id ]['ss_supplier_name']                   = $supplier_info->ss_supplier_name;
                    $suppliers[ $supplier_info->ss_id ]['ss_company_name']                   = $supplier_info->ss_company_name;
                    $suppliers[ $supplier_info->ss_id ]['ss_supplier_phone']               = $supplier_info->ss_supplier_phone;
                    $suppliers[ $supplier_info->ss_id ]['ss_supplier_mobile']              = $supplier_info->ss_supplier_mobile;
                    $suppliers[ $supplier_info->ss_id ]['ss_supplier_email']     = $supplier_info->ss_supplier_email;
                    $suppliers[ $supplier_info->ss_id ]['ss_city_name']          = $supplier_info->ss_city_name;
                    $suppliers[ $supplier_info->ss_id ]['ss_address']                    = $supplier_info->ss_address;
                    $suppliers[ $supplier_info->ss_id ]['ss_street_name']                 = $supplier_info->ss_street_name;
                    
                    $image_src_url  = url('/')."/".Config::get('constants.SRM_PATH').$supplier_info->ss_logo_base_src.$supplier_info->ss_logo_file_name.".".$supplier_info->ss_logo_file_extension;
                    $image_src_path = public_path(). "/" .Config::get('constants.SRM_PATH').$supplier_info->ss_logo_base_src.$supplier_info->ss_logo_file_name.".".$supplier_info->ss_logo_file_extension;
                    if(strlen($supplier_info->ss_logo_base_src) > 0 ){
                        $img_src = $image_src_url;
                    }else{
                        $img_src = url('images/NoImageAvailable.jpg');
                    }
                    
                    $suppliers[ $supplier_info->p_id ]['supplier_avatar']   = $img_src;
                }
                
                $result_array['is_error']       = 0;
                $result_array['suppliers']       = $suppliers;
                $result_array['total_pages']       = $total_pages;
                
                
                return Response()->json($result_array);
    }
    
    /**
     * get Supplier info of a supplier_id and send it in the json response
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * 
     * @return JSON $result_array
     */
    public function GetSupplierInfo(Request $request)
    {
        $user_id             = $request->input('user_id');
        $supplier_id             = $request->input('supplier_id');
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
        
        
        $supplier = array();
        
        $supplier_info = Suppliers::find($supplier_id);
        

        
        
        
        $supplier['ss_supplier_name']                   = $supplier_info->ss_supplier_name;
        $supplier['ss_company_name']                   = $supplier_info->ss_company_name;
        $supplier['ss_supplier_phone']               = $supplier_info->ss_supplier_phone;
        $supplier['ss_supplier_mobile']              = $supplier_info->ss_supplier_mobile;
        $supplier['ss_supplier_email']     = $supplier_info->ss_supplier_email;
        $supplier['ss_city_name']          = $supplier_info->ss_city_name;
        $supplier['ss_address']                    = $supplier_info->ss_address;
        $supplier['ss_street_name']                 = $supplier_info->ss_street_name;
        
        $image_src_url  = url('/')."/".Config::get('constants.SRM_PATH').$supplier_info->ss_logo_base_src.$supplier_info->ss_logo_file_name.".".$supplier_info->ss_logo_file_extension;
        $image_src_path = public_path(). "/" .Config::get('constants.SRM_PATH').$supplier_info->ss_logo_base_src.$supplier_info->ss_logo_file_name.".".$supplier_info->ss_logo_file_extension;
        if(strlen($supplier_info->ss_logo_base_src) > 0 ){
            $img_src = $image_src_url;
        }else{
            $img_src = url('images/NoImageAvailable.jpg');
        }
        
        $supplier['supplier_avatar']   = $img_src;
        
        $result_array['is_error']       = 0;
        $result_array['supplier_info']       = $supplier;
        
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Save Supplier in the database and if exist we update the existing information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveSupplierInfo( Request $request )
    {
        $user_id                        = $request->input('user_id'); 
        $supplier_id                    = $request->input('supplier_id');
        $ss_supplier_name               = $request->input('ss_supplier_name');
        $ss_company_name                = $request->input('ss_company_name');
        $ss_supplier_description        = $request->input('ss_supplier_description');
        $ss_supplier_phone              = $request->input('ss_supplier_phone');
        $ss_supplier_mobile             = $request->input('ss_supplier_mobile');
        $ss_supplier_email              = $request->input('ss_supplier_email');
        $ss_city_name                   = $request->input('ss_city_name');
        $ss_address                     = $request->input('ss_address');
        $g_hash                         = $request->input('g_hash');
        $user_info                      = Users::find($user_id);
        
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
        

        if( $supplier_id != 0 )
        {
            $supplier_info = Suppliers::find($supplier_id);
        }
        else 
        {
            $supplier_info= new Suppliers();
        }
        
        $supplier_info->ss_supplier_name            = $ss_supplier_name; 
        $supplier_info->ss_company_name             = $ss_company_name; 
        $supplier_info->ss_supplier_description     = $ss_supplier_description; 
        $supplier_info->ss_supplier_phone           = $ss_supplier_phone; 
        $supplier_info->ss_supplier_mobile          = $ss_supplier_mobile; 
        $supplier_info->ss_supplier_email           = $ss_supplier_email; 
        $supplier_info->ss_city_name                = $ss_city_name; 
        $supplier_info->ss_address                  = $ss_address; 
        $supplier_info->save();
        
        
        $result_array['is_error']       = 0;
        $result_array['supplier_id']    = $supplier_info->ss_id;
        $result_array['supplier_name']  = $ss_supplier_name;
        return Response()->json($result_array);
    }
    
    
    /**
     * Delete Supplier from the database by changing flag ss_is_deleted to 1
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteSupplier(Request $request)
    {
        $supplier_id           = $request->input('supplier_id');
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
        
        $supplier_info  = Suppliers::find($supplier_id);
        $supplier_info->ss_is_deleted = 1;
        $supplier_info->ss_deleted_by = $user_id;
        $supplier_info->save();
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Completed Successfully";
        
        return Response()->json($result_array);
    }
    
}