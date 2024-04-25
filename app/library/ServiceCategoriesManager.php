<?php
/***********************************************************
ServiceCategoriesManager.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 11, 2019
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
use App\models\Inventory\ProductCategories;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMServiceCategories;


class ServiceCategoriesManager
{
    /**
     * Upload Avatar of Clients Category
     *
     * @author Moe mantach
     * @access public
     * @param Integer $cc_id
     * @return Array $result_array
     */
    public function UploadAvatarCategory( $sc_id )
    {
        $result_array = array();
        
        
        if( $sc_id!= null ){
            
            $this->DeleteCategoryAvatar($sc_id);////Delete The old Avatar in case of edit
            
        }
        
        $response  = array();
        $file_name = $_FILES['sc_avatar_pic']['name'];
        $file_type = $_FILES['sc_avatar_pic']['type'];
        $file_tmp  = $_FILES['sc_avatar_pic']['tmp_name'];
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
            
            $sc_avatar_base_src   = $base_dir;
            $sc_avatar_filename   = $file_name;
            $sc_avatar_extentions = $extention;
            $result_array['is_error']= 0;
        }
        
        if ($result_array['is_error'] == 0) {
            
            $data = array(
                "sc_avatar_base_src" => $sc_avatar_base_src,
                "sc_avatar_file_name" => $sc_avatar_filename,
                "sc_avatar_extentions" => $sc_avatar_extentions
            );
            $result_array['data'] = $data;
        }
        
        return $result_array;
    }
    
    
    /**
     * Delete category Avatar file
     * @param unknown $sc_id
     */
    public function DeleteCategoryAvatar( $sc_id )
    {
        $services_cat_info        = CRMServiceCategories::find( $sc_id );
        $sc_profile_base_src    = $services_cat_info->cc_profile_base_src;
        $sc_profile_file_name   = $services_cat_info->cc_profile_file_name;
        $sc_profile_extension   = $services_cat_info->cc_profile_extension;
        $image_src_path= public_path() . "/" .Config::get('constants.CRM_PATH').$sc_profile_base_src.$sc_profile_file_name.".".$sc_profile_extension;
        
        if(file_exists($image_src_path) && strlen($sc_profile_base_src) > 0 && strlen($sc_profile_file_name) > 0 && strlen( $sc_profile_extension) > 0 ){/////Find the related image to the product
            unlink($image_src_path);
        }
    }
}