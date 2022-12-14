<?php
/***********************************************************
CompaniesManager.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 6, 2019
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
use Illuminate\Http\Request;
use App\models\Inventory\WareHouses;
use App\models\Inventory\WareHouseEmployees;
use App\models\System\Companies;


class CompaniesManager
{
    
    /**
     * Upload Logo of Company
     *
     * @author Moe mantach
     * @access public
     * @param Integer $cd_id
     * @return Array $result_array
     */
    public function UploadCompanyLogo( $cd_id)
    {
        $result_array = array();
        
        
        if( $cd_id != null ){
            
            $this->DeleteCompanyLogo( $cd_id);////Delete The old Avatar in case of edit
            
        }
        
        $response  = array();
        $file_name = $_FILES['cd_logo_pic']['name'];
        $file_type = $_FILES['cd_logo_pic']['type'];
        $file_tmp  = $_FILES['cd_logo_pic']['tmp_name'];
        $base_dir  = date('Y/m/d/');
        $directory = public_path() . "/" . Config::get('constants.COMPANY_PATH') . $base_dir;
        $main_url  = url('/') . "/" . Config::get('constants.COMPANY_PATH') . $base_dir;
        
        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }
        
        $file_info = explode(".", $file_name);
        $extention = $file_info[count($file_info) - 1];
        $file_name = md5(date("Y-m-d H:i:s")) . "_" . date("YmdHis") . "_" . rand(0, 8888888);
        $file_path = $directory . $file_name . "." . $extention;
        $image_url = $main_url . $file_name . "." . $extention;
        
        
        if (move_uploaded_file($file_tmp, $file_path)) {
            
            $cd_logo_base_src       = $base_dir;
            $cd_logo_file_name      = $file_name;
            $cd_logo_file_extension = $extention;
            $result_array['is_error']= 0;
        }
        
        if ($result_array['is_error'] == 0) {
            
            $data = array(
                "cd_logo_base_src" => $cd_logo_base_src,
                "cd_logo_file_name" => $cd_logo_file_name,
                "cd_logo_file_extension" => $cd_logo_file_extension,
            );
            $result_array['data'] = $data;
        }
        
        return $result_array;
    }
    
    
    /**
     * Delete Company logo file
     * @param unknown $cd_id
     */
    public function DeleteCompanyLogo( $cd_id )
    {
        $company_info = Companies::find( $cd_id );
        $cd_logo_base_src       = $company_info->cd_logo_base_src;
        $cd_logo_file_name      = $company_info->cd_logo_file_name;
        $cd_logo_file_extension = $company_info->cd_logo_file_extension;
        $image_src_path= public_path() . "/" .Config::get('constants.COMPANY_PATH').$cd_logo_base_src.$cd_logo_file_name.".".$cd_logo_file_extension;
        
        if(file_exists($image_src_path) && strlen($cd_logo_base_src) > 0 && strlen($cd_logo_file_name) > 0 && strlen( $cd_logo_file_extension) > 0 ){/////Find the related image to the product
            unlink($image_src_path);
        }
    }

}