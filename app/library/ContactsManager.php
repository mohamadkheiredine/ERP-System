<?php
/***********************************************************
ContactsManager.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2019
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
use App\models\Inventory\Products;
use App\models\CRM\CRMContacts;


class ContactsManager
{
    
    /**
     * Upload Avatar of Contact Category
     * 
     * @author Moe mantach
     * @access public
     * @param Integer $rc_id
     * @return Array $result_array
     */
    public function UploadContactAvatar( $cc_id)
    {
        $result_array = array();
   
        
        if( $cc_id != null ){
            
            $this->DeleteContactAvatar( $cc_id );////Delete The old Avatar in case of edit
            
        }
        
        $response  = array();
        $file_name = $_FILES['cc_avatar_pic']['name'];
        $file_type = $_FILES['cc_avatar_pic']['type'];
        $file_tmp  = $_FILES['cc_avatar_pic']['tmp_name'];
        $base_dir  = date('Y/m/d/');
        $directory = public_path() . "/" . Config::get('constants.CONTACT_PATH') . $base_dir;
        $main_url  = url('/') . "/" . Config::get('constants.CONTACT_PATH') . $base_dir;
        
        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }
        
        $file_info = explode(".", $file_name);
        $extention = $file_info[count($file_info) - 1];
        $file_name = md5(date("Y-m-d H:i:s")) . "_" . date("YmdHis") . "_" . rand(0, 8888888);
        $file_path = $directory . $file_name . "." . $extention;
        $image_url = $main_url . $file_name . "." . $extention;
        
        
        if (move_uploaded_file($file_tmp, $file_path)) {
            
            $cc_image_base_src      = $base_dir;
            $cc_image_file_name     = $file_name;
            $cc_image_extension     = $extention;
            $result_array['is_error']= 0;
        }
        
        if ($result_array['is_error'] == 0) {
            
            $data = array(
                "cc_image_base_src" => $cc_image_base_src,
                "cc_image_file_name" => $cc_image_file_name,
                "cc_image_extension" => $cc_image_extension,
            );
            $result_array['data'] = $data;
        }

        return $result_array;
    }
    
    
    /**
     * Delete category Avatar file
     * @param unknown $rc_id
     */
    public function DeleteContactAvatar( $cc_id )
    {
        $contact_info = CRMContacts::find( $cc_id );
        $cc_image_base_src      = $contact_info->cc_image_base_src;
        $cc_image_file_name     = $contact_info->cc_image_file_name;
        $cc_image_extension     = $contact_info->cc_image_extension;
        $image_src_path= public_path() . "/" .Config::get('constants.CONTACT_PATH').$cc_image_base_src.$cc_image_file_name.".".$cc_image_extension;
        
        if(file_exists($image_src_path) && strlen( $cc_image_base_src ) > 0 && strlen( $cc_image_file_name ) > 0 && strlen( $cc_image_extension ) > 0 ){/////Find the related image to the product
            unlink($image_src_path);
        }
    }

}