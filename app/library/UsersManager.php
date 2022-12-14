<?php
/***********************************************************
UsersManager.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 1, 2019
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


class UsersManager
{
    
    /**
     * Upload Avatar of User
     * 
     * @author Moe mantach
     * @access public
     * @param Integer $user_id
     * @return Array $result_array
     */
    public function UploadAvatarUsers( $user_id)
    {
        $result_array = array();
   
        
        if( $user_id!= null ){
            
            $this->DeleteUserAvatar( $user_id );////Delete The old Avatar in case of edit
            
        }
        
        $response  = array();
        $file_name = $_FILES['u_profile_pic']['name'];
        $file_type = $_FILES['u_profile_pic']['type'];
        $file_tmp  = $_FILES['u_profile_pic']['tmp_name'];
        $base_dir  = date('Y/m/d/');
        $directory = public_path() . "/" . Config::get('constants.USERS_PATH') . $base_dir;
        $main_url  = url('/') . "/" . Config::get('constants.USERS_PATH') . $base_dir;
        
        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }
        
        $file_info = explode(".", $file_name);
        $extention = $file_info[count($file_info) - 1];
        $file_name = md5(date("Y-m-d H:i:s")) . "_" . date("YmdHis") . "_" . rand(0, 8888888);
        $file_path = $directory . $file_name . "." . $extention;
        $image_url = $main_url . $file_name . "." . $extention;
        
        
        if (move_uploaded_file($file_tmp, $file_path)) {
            
            $u_avatar_base_src          = $base_dir;
            $u_avatar_filename          = $file_name;
            $u_avatar_extentions        = $extention;
            $result_array['is_error']   = 0;
        }
        
        if ($result_array['is_error'] == 0) {
            
            $data = array(
                "u_avatar_base_src"     => $u_avatar_base_src,
                "u_avatar_filename"     => $u_avatar_filename,
                "u_avatar_extentions"   => $u_avatar_extentions,
            );
            $result_array['data'] = $data;
        }

        return $result_array;
    }
    
    
    /**
     * Delete User Avatar file
     * @param unknown $user_id
     */
    public function DeleteUserAvatar( $user_id )
    {
        $user_info = Users::find( $user_id );
        $u_avatar_base_src      = $user_info->u_avatar_base_src;
        $u_avatar_filename      = $user_info->u_avatar_filename;
        $u_avatar_extentions    = $user_info->u_avatar_extentions;
        
        $image_src_path= public_path() . "/" .Config::get('constants.USERS_PATH').$u_avatar_base_src.$u_avatar_filename.".".$u_avatar_extentions;
        
        if(file_exists($image_src_path) && strlen($u_avatar_base_src) > 0 && strlen($u_avatar_filename) > 0 && strlen( $u_avatar_extentions) > 0 ){/////Find the related image to the product
            unlink($image_src_path);
        }
    }

}