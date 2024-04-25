<?php
/***********************************************************
SuppliersManager.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Class of Supplier Manager
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
use App\models\CRM\CRMAccounts;
use App\models\SRM\Suppliers;
use App\models\Accounting\ChartAccounts;


class SuppliersManager
{
    
    /**
     * Upload Logo of Supplier
     * 
     * @author Moe mantach
     * @access public
     * @param Integer $ss_id
     * @return Array $result_array
     */
    public function UploaSupplierLogo( $ss_id)
    {
        $result_array = array();
   
        
        if( $ss_id != null ){
            
            $this->DeleteSupplierLogo($ss_id);////Delete The old Avatar in case of edit
            
        }
        
        $response  = array();
        $file_name = $_FILES['ss_logo_pic']['name'];
        $file_type = $_FILES['ss_logo_pic']['type'];
        $file_tmp  = $_FILES['ss_logo_pic']['tmp_name'];
        $base_dir  = date('Y/m/d/');
        $directory = public_path() . "/" . Config::get('constants.SRM_PATH') . $base_dir;
        $main_url  = url('/') . "/" . Config::get('constants.SRM_PATH') . $base_dir;
        
        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }
        
        $file_info = explode(".", $file_name);
        $extention = $file_info[count($file_info) - 1];
        $file_name = md5(date("Y-m-d H:i:s")) . "_" . date("YmdHis") . "_" . rand(0, 8888888);
        $file_path = $directory . $file_name . "." . $extention;
        $image_url = $main_url . $file_name . "." . $extention;
        
        
        if (move_uploaded_file($file_tmp, $file_path)) {
            
            $ss_logo_base_src       = $base_dir;
            $ss_logo_file_name      = $file_name;
            $ss_logo_file_extension = $extention;
            $result_array['is_error']= 0;
        }
        
        if ($result_array['is_error'] == 0) {
            
            $data = array(
                "ss_logo_base_src" => $ss_logo_base_src,
                "ss_logo_file_name" => $ss_logo_file_name,
                "ss_logo_file_extension" => $ss_logo_file_extension
            );
            $result_array['data'] = $data;
        }

        return $result_array;
    }
    
    
    /**
     * Delete Account Avatar file
     * @param unknown $ca_id
     */
    public function DeleteSupplierLogo( $ss_id)
    {
        $supplier_info          = Suppliers::find( $ss_id);
        $ss_logo_base_src       = $supplier_info->ss_logo_base_src;
        $ss_logo_file_name      = $supplier_info->ss_logo_file_name;
        $ss_logo_file_extension = $supplier_info->ss_logo_file_extension;
        
        $image_src_path= public_path() . "/" .Config::get('constants.SRM_PATH').$ss_logo_base_src.$ss_logo_file_name.".".$ss_logo_file_extension;
        
        if(file_exists($image_src_path) && strlen($ss_logo_base_src) > 0 && strlen($ss_logo_file_name) > 0 && strlen( $ss_logo_file_extension) > 0 ){/////Find the related image to the product
            unlink($image_src_path);
        }
    }
    
    
    public function GenerateNewSupplierAcc(Array $params_array )
    { 
        $account_label      = $params_array["account_label"];
        $country_id         = session("company_country");
        $result_array       = array();
        $parent_account = 0;
        
        // get parent account info
        $parent_account_info = ChartAccounts::whereAaAccount("401")->get();
        
        $parent_account  = $parent_account_info[0]->aa_id;
  
        
        
        $count_ref_account = ChartAccounts::whereAaAccountRef($parent_account)->count();
        
        
        // check if this account exist
        $account_info = ChartAccounts::whereAaParentAccount($parent_account)->get();
        
        $new_count = count($account_info) + 1;
        
        $aa_account_ref = "4011". (String)$new_count;
        $AccAccounting = new ChartAccounts();
        $AccAccounting->aa_parent_account   = $parent_account;
        $AccAccounting->aa_account_ref      = $aa_account_ref;
        $AccAccounting->aa_account          = $aa_account_ref;
        $AccAccounting->aa_sub_account      = $parent_account;
        $AccAccounting->aa_account_label    = $account_label;
        $AccAccounting->fk_country_id       = $country_id;
        $AccAccounting->save();
        
        $aa_id = $AccAccounting->aa_id;
        
        
        return $aa_id;
    }

}