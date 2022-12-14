<?php
/***********************************************************
LeadsManager.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 22, 2019
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
use App\models\Inventory\Stocks;
use App\models\Inventory\StockMovements;
use App\models\CRM\CRMLeads;
use App\models\CRM\CRMLeadAppointments;


class LeadsManager
{
    
    /**
     * Upload Avatar of the lead
     *
     * @author Moe mantach
     * @access public
     * @param Integer $cl_id
     * @return Array $result_array
     */
    public function UploadLeadAvatar( $cl_id)
    {
        $result_array = array();
        
        
        if( $cl_id != null ){
            
            $this->DeleteLeadsAvatar($cl_id);////Delete The old Avatar in case of edit
            
        }
        
        $response  = array();
        $file_name = $_FILES['cl_avatar_pic']['name'];
        $file_type = $_FILES['cl_avatar_pic']['type'];
        $file_tmp  = $_FILES['cl_avatar_pic']['tmp_name'];
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
            
            $cl_image_base_src          = $base_dir;
            $cl_image_file_name         = $file_name;
            $cl_image_extension         = $extention;
            $result_array['is_error']   = 0;
        }
        
        if ($result_array['is_error'] == 0) {
            
            $data = array(
                "cl_image_base_src"     => $cl_image_base_src,
                "cl_image_file_name"    => $cl_image_file_name,
                "cl_image_extension"    => $cl_image_extension
            );
            $result_array['data'] = $data;
        }
        
        return $result_array;
    }
    
    
    /**
     * Delete Lead Avatar
     * @param unknown $cl_id
     */
    public function DeleteLeadsAvatar( $cl_id )
    {
        $lead_info = CRMLeads::find( $cl_id );
        $cl_image_base_src      = $lead_info->cl_image_base_src;
        $cl_image_file_name     = $lead_info->cl_image_file_name;
        $cl_image_extension     = $lead_info->cl_image_extension;
        $image_src_path= public_path() . "/" .Config::get('constants.CRM_PATH').$cl_image_base_src.$cl_image_file_name.".".$cl_image_extension;
        
        if(file_exists($image_src_path) && strlen($cl_image_base_src) > 0 && strlen($cl_image_file_name) > 0 && strlen( $cl_image_extension ) > 0 ){/////Find the related image to the product
            unlink($image_src_path);
        }
    }
    
    /**
     * Generate Xmlo for appointment of selected 
     */
    public function GenerateAppointmentCalendarFile( $lead_id )
    {
        $calendar_src_path= public_path() . "/" .Config::get('constants.CALENDAR_PATH') . "appointments.json";
        $directory =  public_path() . "/" .Config::get('constants.CALENDAR_PATH');
        $url = url("/") . "/" .Config::get('constants.CALENDAR_PATH') .  "appointments.json";
        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }
        
        $lst_appointments = CRMLeadAppointments::whereFkLeadId($lead_id)->get();
        
        $appt_json_array = array();
        $index = 0;
        foreach ( $lst_appointments as $key => $apt_info ) 
        {
            $appt_json_array['data'][$index]['id'] = $apt_info->ca_id; 
            $appt_json_array['data'][$index]['text'] = $apt_info->ca_appointment_subject;
            $appt_json_array['data'][$index]['start_date'] = $apt_info->ca_appointment_date . " " . $apt_info->ca_appointment_start_time;
            $appt_json_array['data'][$index]['end_date'] = $apt_info->ca_appointment_date . " " . $apt_info->ca_appointment_end_time;
        }
        
        $fp =fopen($calendar_src_path, "w");
        fwrite($fp, json_encode($appt_json_array));
        fclose($fp);
        
        
        return base64_encode($url);
    }
    
    
    /**
     * Generate Calendar path for loggedin user
     * 
     * @author Moe mantach
     * @access public
     * @param unknown $user_id
     * @return string
     */
    public function GenerateAppointmenMytCalendarFile( $user_id )
    {
        $calendar_src_path= public_path() . "/" .Config::get('constants.CALENDAR_PATH') . "appointments.json";
        $directory =  public_path() . "/" .Config::get('constants.CALENDAR_PATH');
        $url = url("/") . "/" .Config::get('constants.CALENDAR_PATH') .  "appointments.json";
        if (!is_dir($directory)) {
            $result = File::makeDirectory($directory, 0777, true);
        }
        
        $lst_appointments = CRMLeadAppointments::whereFkAssignedTo($user_id)->get();
        
        $appt_json_array = array();
        $index = 0;
        foreach ( $lst_appointments as $key => $apt_info )
        {
            $appt_json_array['data'][$index]['id'] = $apt_info->ca_id;
            $appt_json_array['data'][$index]['text'] = $apt_info->ca_appointment_subject;
            $appt_json_array['data'][$index]['start_date'] = $apt_info->ca_appointment_date . " " . $apt_info->ca_appointment_start_time;
            $appt_json_array['data'][$index]['end_date'] = $apt_info->ca_appointment_date . " " . $apt_info->ca_appointment_end_time;
        }
        
        $fp =fopen($calendar_src_path, "w");
        fwrite($fp, json_encode($appt_json_array));
        fclose($fp);
        
        
        return base64_encode($url);
    }
}