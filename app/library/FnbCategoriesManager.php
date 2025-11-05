<?php
/***********************************************************
ProductCategoriesManager.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 14, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\library;

use App\models\FnB\MenuCategories;
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


class FnbCategoriesManager
{
    public function UploadAvatarCategory( $rc_id)
    {
        $result_array = array();


        if( $rc_id != null ){

            $this->DeleteCategoryAvatar($rc_id);////Delete The old Avatar in case of edit

        }

        $response  = array();
        $file_name = $_FILES['pc_avatar_pic']['name'];
        $file_type = $_FILES['pc_avatar_pic']['type'];
        $file_tmp  = $_FILES['pc_avatar_pic']['tmp_name'];
        $base_dir  = date('Y/m/d/');
        $directory = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $base_dir;
        $main_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $base_dir;

        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }

        $file_info = explode(".", $file_name);
        $extention = $file_info[count($file_info) - 1];
        $file_name = md5(date("Y-m-d H:i:s")) . "_" . date("YmdHis") . "_" . rand(0, 8888888);
        $file_path = $directory . $file_name . "." . $extention;
        $image_url = $main_url . $file_name . "." . $extention;


        if (move_uploaded_file($file_tmp, $file_path)) {

            $pc_avatar_base_src   = $base_dir;
            $pc_avatar_filename   = $file_name;
            $pc_avatar_extentions = $extention;
            $result_array['is_error']= 0;
        }

        if ($result_array['is_error'] == 0) {

            $data = array(
                "pc_avatar_base_src" => $pc_avatar_base_src,
                "pc_avatar_file_name" => $pc_avatar_filename,
                "pc_avatar_extentions" => $pc_avatar_extentions,
            );
            $result_array['data'] = $data;
        }

        return $result_array;
    }


    public function DeleteCategoryAvatar( $pc_id )
    {
        $product_cat_info = MenuCategories::find( $pc_id);
        $pc_avatar_base_src     = $product_cat_info->pc_avatar_base_src;
        $pc_avatar_file_name    = $product_cat_info->pc_avatar_file_name;
        $pc_avatar_extension    = $product_cat_info->pc_avatar_extension;
        $image_src_path= public_path() . "/" .Config::get('constants.PRODUCTS_PATH').$pc_avatar_base_src.$pc_avatar_file_name.".".$pc_avatar_extension;

        if(file_exists($image_src_path) && strlen($pc_avatar_base_src) > 0 && strlen($pc_avatar_file_name) > 0 && strlen( $pc_avatar_extension) > 0 ){/////Find the related image to the product
            unlink($image_src_path);
        }
    }

}
