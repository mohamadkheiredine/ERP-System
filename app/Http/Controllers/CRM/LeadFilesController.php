<?php
/***********************************************************
LeadFilesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 28, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Config;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeads;
use App\models\Users\Users;
use App\models\Inventory\WareHouses;
use App\models\System\Industry;
use App\Library\LeadsManager;
use App\models\CRM\CRMLeadSources;
use App\models\CRM\CRMLeadFiles;
use App\Library\CRMLogsManager;



class LeadFilesController extends Controller
{
    
    /**
     * Disp0lay tab of Lead Notes where you can manage notes of the current Lead
     * 
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function LeadFilesManager(Request $request)
    {
        $cl_id = $request->input("cl_id"); 
        $lst_files = CRMLeadFiles::whereFkLeadId($cl_id)->get();
        $result_array = array();
        
        $lst_users = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $users_array = CreateDatabaseArrayByIndex($lst_users,"id");
        
        $data = array(
            "lst_files" => $lst_files,
            "users_array" => $users_array
        );
        $result_array['is_error'] = 0;
        $result_array['display'] = view("Leads.leadfiles",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Upload Lead file and save it into the database info
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function UploadLeadFile(Request $request)
    {
        $lead_id     = $request->input("lead_id");
        $file_name   = $_FILES['lead_file']['name'];
        $type        = $_FILES['lead_file']['type'];
        $tmp_name    = $_FILES['lead_file']['tmp_name'];
        $size        = $_FILES['lead_file']['size'];
        
        
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
            $LeadFiles_obj->fk_lead_id              = $lead_id;
            $LeadFiles_obj->lf_uploaded_by          = session("user_id");
            $LeadFiles_obj->lf_file_base_src        = $base_dir;
            $LeadFiles_obj->lf_file_name            = $file_name;
            $LeadFiles_obj->lf_file_extension       = $extention;
            $LeadFiles_obj->lf_file_size            = filesize($file_path);
            $LeadFiles_obj->lf_uploaded_date        = date("Y-m-d H:i:s");
            $LeadFiles_obj->lf_file_mime_type       = $type;
            $LeadFiles_obj->save();
            
            // add log for Upload lead File
            $CRMLogs = new CRMLogsManager();
            $params_array = array(
                'fk_lead_id' => $lead_id,
                'log_type' => CRMLogsManager::LOG_TYPE_ADD_LEAD_FILES
            );
            $CRMLogs->InsertCRMLog($params_array);
            
        }
        
        $result_array['is_error']   =  0;
        $result_array['error_msg']  =  "Operation Complete Successfuly";
        $result_array['image_url']  =  $image_url; 
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Delete Lead File
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteLeadFile(Request $request)
    {
        $lf_id = $request->input("lf_id");
        $lead_files = CRMLeadFiles::find($lf_id);
        $directory =  public_path() . "/" . Config::get( 'constants.CRM_PATH') . $lead_files->lf_file_base_src . $lead_files->lf_file_name . "." . $lead_files->lf_file_extension;
        unset($directory);
        $lead_files->lf_is_active = 0;
        $lead_files->save();
        
        
        $result_array['is_error']   =  0;
        $result_array['error_msg']  =  "Operation Complete Successfuly";
        
        return Response()->json($result_array);
    }
    
}