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


use App\models\CRM\CRMLeads;
use App\models\Expenses\Expenses;
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


class ExpensesManager
{


    /**
     * Upload voucher file of Expenses
     *
     * @author Moe mantach
     * @access public
     * @param Integer $ex_id
     * @return Array $result_array
     */
    public function UploadExtensesVoucher( $ex_id )
    {
        $result_array = array();
        if(!$_FILES['attachment'])
        {
            return $result_array;
        }


        if( $ex_id != null ){

            $this->DeleteExpensesVoucher( $ex_id );////Delete The old Avatar in case of edit

        }

        $response  = array();

        $file_name = $_FILES['attachment']['name'];
        $file_type = $_FILES['attachment']['type'];
        $file_tmp  = $_FILES['attachment']['tmp_name'];
        $base_dir  = date('Y/m/d/');
        $directory = public_path() . "/" . Config::get('constants.EXPENSES_PATH') . $base_dir;
        $main_url  = url('/') . "/" . Config::get('constants.EXPENSES_PATH') . $base_dir;

        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }

        $file_info = explode(".", $file_name);
        $extention = $file_info[count($file_info) - 1];
        $file_name = md5(date("Y-m-d H:i:s")) . "_" . date("YmdHis") . "_" . rand(0, 8888888);
        $file_path = $directory . $file_name . "." . $extention;
        $image_url = $main_url . $file_name . "." . $extention;


        if (move_uploaded_file($file_tmp, $file_path)) {

            $ac_base_src      = $base_dir;
            $ac_file_name           = $file_name;
            $ac_extension     = $extention;
            $result_array['is_error']= 0;
        }

        if ($result_array['is_error'] == 0) {

            $data = array(
                "ac_base_src" => $ac_base_src,
                "ac_file_name" => $ac_file_name,
                "ac_extension" => $ac_extension,
            );
            $result_array['data'] = $data;
        }

        return $result_array;
    }


    /**
     * Delete Expenses Avatar file
     * @param unknown $ex_id
     */
    public function DeleteExpensesVoucher( $ex_id)
    {
        $expenses_info = Expenses::find( $ex_id );
        $ac_base_src      = $expenses_info->ac_base_src;
        $ac_file_name     = $expenses_info->ac_file_name;
        $ac_extension     = $expenses_info->ac_extension;
        $image_src_path= public_path() . "/" .Config::get('constants.EXPENSES_PATH').$ac_base_src.$ac_file_name.".".$ac_extension;

        if(file_exists($image_src_path) && strlen($ac_base_src) > 0 && strlen($ac_file_name) > 0 && strlen( $ac_extension ) > 0 ){/////Find the related image to the product
            unlink($image_src_path);
        }
    }


}
