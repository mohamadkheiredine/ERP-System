<?php
/***********************************************************
UploaderController.php
Product :
Version : 1.0
Release : 1
Date Created : Apr 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\Http\Controllers\Utilities;

use App\User;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Config;
use Redirect;
use File;
use DB;
use App\models\System\Appconfig;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\models\Restaurants\RestaurantGallery;
use App\models\CRM\CRMLeadFiles;



class UploaderController extends Controller
{
    
   public function UploadFile( $key , Request $request )
   {
       $result_array = array();
       
       
       switch($key)
       {
           case 'lead_files':
               { 
                   
                   $lead_id     = $request->input("lead_id");
                   $file_name   = $_FILES['file']['name'];
                   $type        = $_FILES['file']['type'];
                   $tmp_name    = $_FILES['file']['tmp_name'];
                   $size        = $_FILES['file']['size'];
                
                   
                   $base_dir = "leads/" . $lead_id . "/";
                   
                   $directory = public_path(). "/" . Config::get( 'constants.CRM_PATH') . $base_dir;
                   $image_url =url('/'). "/" . Config::get( 'constants.CRM_PATH') . $base_dir;
                   
                   if(!is_dir($directory))
                   {
                       $result = File::makeDirectory($directory, 0777, true);
                   }
                   
                   $file_info = explode(".", $file_name);
                   
                   $extention  = $file_info[count($file_info) - 1];
                   $file_name  = md5(date("Y-m-d H:i:s") ). "_" . date("YmdHis") . "_" . rand(0, 999999);
                   
                   $file_path = $directory . $file_name . "." . $extention;
                   $image_url = $image_url . $file_name . "." . $extention;
                   if(move_uploaded_file($tmp_name, $file_path))
                   { 
                       $LeadFiles_obj = new CRMLeadFiles();
                       $LeadFiles_obj->fk_lead_id               = $lead_id;
                       $LeadFiles_obj->lf_uploaded_by           = session("user_id");
                       $LeadFiles_obj->lf_file_base_src         = $base_dir;
                       $LeadFiles_obj->lf_file_name             = $file_name;
                       $LeadFiles_obj->lf_file_extension        = $extention; 
                       $LeadFiles_obj->lf_uploaded_date         = date("Y-m-d H:i:s");
                       $LeadFiles_obj->lf_file_size             = filesize($file_path);
                       $LeadFiles_obj->lf_file_mime_type        = $type; 
                       $LeadFiles_obj->save();
                        
                   } 
                    
                   $result_array['is_error']   =  0;
                   $result_array['error_msg']  =  "Operation Complete Successfuly";
                   $result_array['image_url']  =  $image_url; 
               }
           break;
       }
       
       
       
       return Response()->json($result_array);
       
   }
}