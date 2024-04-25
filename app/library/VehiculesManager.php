<?php
/***********************************************************
VehiculesManager.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 7, 2019
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


class VehiculesManager
{
    
    
    /**
     * Upload Avatar of Vehicule
     *
     * @author Moe mantach
     * @access public
     * @param Integer $lv_id
     * @return Array $result_array
     */
    public function UploadVehiculeAvatar( $lv_id)
    {
        $result_array = array();
        
        
        if( $lv_id!= null ){
            
            $this->DeleteVehiculeAvatar( $lv_id );////Delete The old Avatar in case of edit
            
        }
        
        $response  = array();
        $file_name = $_FILES['lv_avatar_pic']['name'];
        $file_type = $_FILES['lv_avatar_pic']['type'];
        $file_tmp  = $_FILES['lv_avatar_pic']['tmp_name'];
        $base_dir  = date('Y/m/d/');
        $directory = public_path() . "/" . Config::get('constants.LOGISTICS_PATH') . $base_dir;
        $main_url  = url('/') . "/" . Config::get('constants.LOGISTICS_PATH') . $base_dir;
        
        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }
        
        $file_info = explode(".", $file_name);
        $extention = $file_info[count($file_info) - 1];
        $file_name = md5(date("Y-m-d H:i:s")) . "_" . date("YmdHis") . "_" . rand(0, 8888888);
        $file_path = $directory . $file_name . "." . $extention;
        $image_url = $main_url . $file_name . "." . $extention;
        
        
        if (move_uploaded_file($file_tmp, $file_path)) {
            
            $lv_image_base_src      = $base_dir;
            $lv_file_name           = $file_name;
            $lv_file_extension      = $extention;
            $result_array['is_error']= 0;
        }
        
        if ($result_array['is_error'] == 0) {
            
            $data = array(
                "lv_image_base_src" => $lv_image_base_src,
                "lv_file_name" => $lv_file_name,
                "lv_file_extension" => $lv_file_extension,
            );
            $result_array['data'] = $data;
        }
        
        return $result_array;
    }
    
    
    /**
     * Delete Vehicule Avatar file
     * @param unknown $lv_id
     */
    public function DeleteVehiculeAvatar( $lv_id )
    {
        $vehicule_info = Vehicules::find( $lv_id);
        $lv_image_base_src     = $vehicule_info->lv_image_base_src;
        $lv_file_name          = $vehicule_info->lv_file_name;
        $lv_file_extension     = $vehicule_info->lv_file_extension;
        $image_src_path= public_path() . "/" .Config::get('constants.LOGISTICS_PATH').$lv_image_base_src.$lv_file_name.".".$lv_file_extension;
        
        if(file_exists($image_src_path) && strlen($lv_image_base_src) > 0 && strlen($lv_file_name) > 0 && strlen( $lv_file_extension ) > 0 ){/////Find the related image to the product
            unlink($image_src_path);
        }
    }

}