<?php
/***********************************************************
AccountsManager.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 25, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/






namespace App\Library;


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
use App\models\Inventory\ProductCategories;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMAccounts;


class AccountsManager
{
    
    /**
     * Upload Avatar of Account
     * 
     * @author Moe mantach
     * @access public
     * @param Integer $ca_id
     * @return Array $result_array
     */
    public function UploadAvatarAccount( $ca_id)
    {
        $result_array = array();
   
        
        if( $ca_id != null ){
            
            $this->DeleteAccountAvatar($ca_id);////Delete The old Avatar in case of edit
            
        }
        
        $response  = array();
        $file_name = $_FILES['ca_avatar_pic']['name'];
        $file_type = $_FILES['ca_avatar_pic']['type'];
        $file_tmp  = $_FILES['ca_avatar_pic']['tmp_name'];
        $base_dir  = date('Y/m/d/');
        $directory = public_path() . "/" . Config::get('constants.CRM_PATH') . $base_dir;
        $main_url  = url('/') . "/" . Config::get('constants.CRM_PATH') . $base_dir;
        
        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }
        
        $file_info = explode(".", $file_name);
        $extention = $file_info[count($file_info) - 1];
        $file_name = md5(date("Y-m-d H:i:s")) . "_" . date("YmdHis") . "_" . rand(0, 8888888);
        $file_path = $directory . $file_name . "." . $extention;
        $image_url = $main_url . $file_name . "." . $extention;
        
        
        if (move_uploaded_file($file_tmp, $file_path)) {
            
            $ca_image_base_src      = $base_dir;
            $ca_image_file_name     = $file_name;
            $ca_image_extension     = $extention;
            $result_array['is_error']= 0;
        }
        
        if ($result_array['is_error'] == 0) {
            
            $data = array(
                "ca_image_base_src" => $ca_image_base_src,
                "ca_image_file_name" => $ca_image_file_name,
                "ca_image_extension" => $ca_image_extension
            );
            $result_array['data'] = $data;
        }

        return $result_array;
    }
    
    
    /**
     * Delete Account Avatar file
     * @param unknown $ca_id
     */
    public function DeleteAccountAvatar( $ca_id)
    {
        $client_info        = CRMAccounts::find( $ca_id);
        $ca_image_base_src      = $client_info->ca_image_base_src;
        $ca_image_file_name     = $client_info->ca_image_file_name;
        $ca_image_extension     = $client_info->ca_image_extension;
        $image_src_path= public_path() . "/" .Config::get('constants.CRM_PATH').$ca_image_base_src.$ca_image_file_name.".".$ca_image_extension;
        
        if(file_exists($image_src_path) && strlen($ca_image_base_src ) > 0 && strlen($ca_image_file_name) > 0 && strlen( $ca_image_extension) > 0 ){/////Find the related image to the product
            unlink($image_src_path);
        }
    }

}