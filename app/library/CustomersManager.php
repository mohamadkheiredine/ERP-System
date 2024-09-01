<?php
/***********************************************************
CustomersManager.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 14, 2019
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
use App\models\Inventory\Customers;


class CustomersManager 
{
    
    
    /**
     * Upload Logo of Customers
     *
     * @author Moe mantach
     * @access public
     * @param Integer $lv_id
     * @return Array $result_array
     */
    public function UploadCustomersAvatar( $ic_id)
    {
        $result_array = array();
        
        if($_FILES['ic_avatar_pic'])
        {
            return $result_array;
        }
        
        
        if( $ic_id != null ){
            
            $this->DeleteCustomerAvatar( $ic_id );////Delete The old Avatar in case of edit
            
        }
        
        $response  = array();
        
        $file_name = $_FILES['ic_avatar_pic']['name'];
        $file_type = $_FILES['ic_avatar_pic']['type'];
        $file_tmp  = $_FILES['ic_avatar_pic']['tmp_name'];
        $base_dir  = date('Y/m/d/');
        $directory = public_path() . "/" . Config::get('constants.CUSTOMERS_PATH') . $base_dir;
        $main_url  = url('/') . "/" . Config::get('constants.CUSTOMERS_PATH') . $base_dir;
        
        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }
        
        $file_info = explode(".", $file_name);
        $extention = $file_info[count($file_info) - 1];
        $file_name = md5(date("Y-m-d H:i:s")) . "_" . date("YmdHis") . "_" . rand(0, 8888888);
        $file_path = $directory . $file_name . "." . $extention;
        $image_url = $main_url . $file_name . "." . $extention;
        
        
        if (move_uploaded_file($file_tmp, $file_path)) {
            
            $ic_image_base_src      = $base_dir;
            $ic_file_name           = $file_name;
            $ic_file_extension      = $extention;
            $result_array['is_error']= 0;
        }
        
        if ($result_array['is_error'] == 0) {
            
            $data = array(
                "ic_image_base_src" => $ic_image_base_src,
                "ic_file_name" => $ic_file_name,
                "ic_file_extension" => $ic_file_extension,
            );
            $result_array['data'] = $data;
        }
        
        return $result_array;
    }
    
    
    /**
     * Delete Customer Avatar file
     * @param unknown $iv_id
     */
    public function DeleteCustomerAvatar( $ic_id)
    {
        $customer_info = Customers::find( $ic_id );
        $customer_info->ic_is_deleted = 1;
        $customer_info->ic_deleted_by= session("user_id");
        $customer_info->save();
    }
    
    
    public function GenerateCustomerCode($params = array())
    { 
        $company_id     = isset( $params['company_id']) ? $params['company_id'] : session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_customers = Customers::whereIcIsDeleted(0)->count();
        
        $index = $count_customers + 1;
        
        
        $customer_code = "S-" . sprintf('%04d', $index);
        
        // check if customer code exist
        
        $code_count = Customers::where('ic_customer_code',$customer_code)->count();
        if($code_count > 0)
        {
            $index = $count_customers + 2;
            
            
             $customer_code = "S-" . sprintf('%04d', $index);
        }
        
        unset($code_count);
        
        return $customer_code;
        
    }

}