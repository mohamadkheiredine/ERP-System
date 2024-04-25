<?php
/***********************************************************
VendorsManager.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 28, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\library;


use Validator;
use Input;
use Config;
use Session;
use Redirect;
use Crypt;
use Cookie;
use Auth;
use DB;
use File;
use App\models\Users\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\models\Logistics\Vehicules;
use App\models\Inventory\Vendors;
use App\models\System\Companies;


class VendorsManager
{
    
    
    /**
     * Upload Avatar of Vehicule
     *
     * @author Moe mantach
     * @access public
     * @param Integer $lv_id
     * @return Array $result_array
     */
    public function UploadVendorsAvatar( $iv_id)
    {
        $result_array = array();
        
        
        if( $iv_id != null ){
            
            $this->DeleteVendorAvatar( $iv_id );////Delete The old Avatar in case of edit
            
        }
        
        $response  = array();
        $file_name = $_FILES['iv_avatar_pic']['name'];
        $file_type = $_FILES['iv_avatar_pic']['type'];
        $file_tmp  = $_FILES['iv_avatar_pic']['tmp_name'];
        $base_dir  = date('Y/m/d/');
        $directory = public_path() . "/" . Config::get('constants.VENDORS_PATH') . $base_dir;
        $main_url  = url('/') . "/" . Config::get('constants.VENDORS_PATH') . $base_dir;
        
        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }
        
        $file_info = explode(".", $file_name);
        $extention = $file_info[count($file_info) - 1];
        $file_name = md5(date("Y-m-d H:i:s")) . "_" . date("YmdHis") . "_" . rand(0, 8888888);
        $file_path = $directory . $file_name . "." . $extention;
        $image_url = $main_url . $file_name . "." . $extention;
        
        
        if (move_uploaded_file($file_tmp, $file_path)) {
            
            $iv_image_base_src      = $base_dir;
            $iv_file_name           = $file_name;
            $iv_file_extension      = $extention;
            $result_array['is_error']= 0;
        }
        
        if ($result_array['is_error'] == 0) {
            
            $data = array(
                "iv_image_base_src" => $iv_image_base_src,
                "iv_file_name" => $iv_file_name,
                "iv_file_extension" => $iv_file_extension,
            );
            $result_array['data'] = $data;
        }
        
        return $result_array;
    }
    
    
    /**
     * Delete Vendor Avatar file
     * @param unknown $iv_id
     */
    public function DeleteVendorAvatar( $iv_id)
    {
        $vendor_info = Vendors::find( $iv_id );
        $vendor_info->iv_is_deleted = 1;
        $vendor_info->iv_deleted_by= session("user_id");
        $vendor_info->save();
    }
    
    
    public function GenerateVendorCode()
    {
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_vendors = Vendors::whereIvIsDeleted(0)->count();
        
        $index = $count_vendors+ 1;
        
        
        $vendor_code = "ven" . $cd_company_name[0] . $year . "-" . sprintf('%04d', $index);
        
        return $vendor_code;
        
    }

}