<?php
/***********************************************************
CRMLogsManager.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 13, 2019
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
use App\models\CRM\CRMLogs;
use App\models\CRM\CRMLeads;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeadNotes;


class CRMLogsManager
{
    const LOG_TYPE_ADD_LEAD             = "ADD_LEAD";
    const LOG_TYPE_LEAD_CHANGE_STATUS   = "LEAD_CHANGE_STATUS";
    const LOG_TYPE_LEAD_ASSIGN_TO       = "LEAD_ASSIGN_TO";
    const LOG_TYPE_ADD_LEAD_NOTES       = "ADD_LEAD_NOTE";
    const LOG_TYPE_ADD_LEAD_FILES       = "ADD_LEAD_FILE";
    const LOG_TYPE_ADD_LEAD_ACTIVITY    = "ADD_LEAD_ACTIVITY";
    const LOG_TYPE_ADD_LEAD_APPOINTMENT = "ADD_LEAD_APPOINTMENT";
    const LOG_TYPE_ADD_LEAD_SERVICE     = "ADD_LEAD_SERVICE";
    
    
    /**
     * generate description to save it into the database
     * 
     * @author Moe Mantach
     * @param unknown $params
     */
    public function GenerateLogDescription( $params )
    {
        $log_type   = $params['log_type'];
        $user_id   = $params['user_id'];
        $lead_id   = $params['lead_id'];
        
        $user_info = Users::find($user_id);
        $lead_info = CRMLeads::find($lead_id);
        
        switch ($log_type)
        {
            case self::LOG_TYPE_ADD_LEAD:
            {
                return $user_info->u_fullname . " Create A new Lead ( " . $lead_info->cl_first_name . " " . $lead_info->cl_last_name . " ) On " . date("Y-m-d H:i:s");
            }
            break;
            case self::LOG_TYPE_LEAD_CHANGE_STATUS:
            {
                $old_lead_status = $params['old_lead_status'];
                $os_info = CRMLeadStatus::find($old_lead_status);
                $new_lead_status = $params['new_lead_status'];
                $ns_info = CRMLeadStatus::find($new_lead_status);
                return $user_info->u_fullname . " change status of Lead '" . $lead_info->cl_first_name . " " . $lead_info->cl_last_name . "' From " . $os_info->ls_status . " to " . $ns_info->ls_status. " On " . date("Y-m-d H:i:s");
            }
            break;
            case self::LOG_TYPE_LEAD_ASSIGN_TO:
            {
                $assign_to  = $params['assign_to'];
                $user_assign = Users::find($assign_to);
                return $user_info->u_fullname . " Assign Lead '" . $lead_info->cl_first_name . " " . $lead_info->cl_last_name . "' To " . $user_assign->u_fullname. " On " . date("Y-m-d H:i:s");
            }
            break;
            case self::LOG_TYPE_ADD_LEAD_NOTES:
            { 
                return $user_info->u_fullname . " Add new Note to Lead '" . $lead_info->cl_first_name . " " . $lead_info->cl_last_name . "' On " . date("Y-m-d H:i:s");
            }
            break;
            case self::LOG_TYPE_ADD_LEAD_FILES:
            { 
                return $user_info->u_fullname . " Upload new File to Lead '" . $lead_info->cl_first_name . " " . $lead_info->cl_last_name . "' On " . date("Y-m-d H:i:s");
            }
            break;
            case self::LOG_TYPE_ADD_LEAD_ACTIVITY:
            { 
                return $user_info->u_fullname . " Add new Activity to Lead '" . $lead_info->cl_first_name . " " . $lead_info->cl_last_name . "' On " . date("Y-m-d H:i:s");
            }
            break;
            case self::LOG_TYPE_ADD_LEAD_SERVICE:
            { 
                return $user_info->u_fullname . " Add new Service to Lead '" . $lead_info->cl_first_name . " " . $lead_info->cl_last_name . "' On " . date("Y-m-d H:i:s");
            }
            break;
            case self::LOG_TYPE_ADD_LEAD_APPOINTMENT:
            { 
                $appt_user = $params['appt_user'];
                $appt_date = $params['appt_date'];
                $appt_info = Users::find($appt_user);
                return $user_info->u_fullname . " Set A new Appointment For " . $appt_info->u_fullname . " to Lead '" . $lead_info->cl_first_name . " " . $lead_info->cl_last_name . "' On " . $appt_date;
            }
            break;
        }
    }
    
    
    /**
     * Insert CRM Log
     * @param unknown $params_array
     */
    public function InsertCRMLog( $params_array )
    {
        $log_type   = $params_array['log_type'];
        $user_id    = session("user_id");
        $lead_id    = $params_array['fk_lead_id'];
        $CRMLogs    = new CRMLogs();
        $CRMLogs->fk_lead_id            = $lead_id;
        $CRMLogs->fk_user_id            = session("user_id");
        
        $CRMLogs->ll_log_date           = date("Y-m-d H:i:s");
        $CRMLogs->ll_log_type           = $log_type;
        
        switch ($log_type)
        {
            case self::LOG_TYPE_ADD_LEAD:
                {
                    $params = array(
                        "lead_id" => $lead_id,
                        "user_id" => $user_id,
                        "log_type" => $log_type,
                    ); 
                    $ll_log_description = $this->GenerateLogDescription($params);
                    $CRMLogs->ll_log_description    = $ll_log_description;
                    
                   
                }
            break;
            case self::LOG_TYPE_LEAD_CHANGE_STATUS:
                {
                    $params = array(
                        "lead_id" => $lead_id,
                        "user_id" => $user_id,
                        "log_type" => $log_type,
                        "old_lead_status" => $params_array["old_lead_status"],
                        "new_lead_status" => $params_array["new_lead_status"]
                    );
                    
                    $ll_log_description = $this->GenerateLogDescription($params);
                    
                    $CRMLogs->ll_log_description    = $ll_log_description;
                    $CRMLogs->ll_lead_status_id     = $params_array["new_lead_status"];
                }
            break;
            case self::LOG_TYPE_LEAD_ASSIGN_TO:
                {
                    $assign_to  = $params_array['assign_to'];
                    $params = array(
                        "lead_id" => $lead_id,
                        "user_id" => $user_id,
                        "log_type" => $log_type,
                        "assign_to" => $assign_to
                    );
                    
                    $ll_log_description = $this->GenerateLogDescription($params);
                    $CRMLogs->ll_log_description    = $ll_log_description;
                }
                break;
            case self::LOG_TYPE_ADD_LEAD_NOTES:
            case self::LOG_TYPE_ADD_LEAD_FILES:
            case self::LOG_TYPE_ADD_LEAD_ACTIVITY: 
                { 
                    $params = array(
                        "lead_id" => $lead_id,
                        "user_id" => $user_id,
                        "log_type" => $log_type
                    );
                    
                    $ll_log_description = $this->GenerateLogDescription($params);
                    $CRMLogs->ll_log_description    = $ll_log_description;
                }
                break;
            case self::LOG_TYPE_ADD_LEAD_SERVICE:
                { 
                    $params = array(
                        "lead_id" => $lead_id,
                        "user_id" => $user_id,
                        "log_type" => $log_type
                    );
                    
                    $ll_log_description = $this->GenerateLogDescription($params);
                    $CRMLogs->ll_log_description    = $ll_log_description;
                }
                break;
            case self::LOG_TYPE_ADD_LEAD_APPOINTMENT:
                {
                    $appt_user = $params_array['appt_user'];
                    $appt_date = $params_array['appt_date'];
                    $params = array(
                        "lead_id" => $lead_id,
                        "user_id" => $user_id,
                        "log_type" => $log_type,
                        "appt_user" => $appt_user,
                        "appt_date" => $appt_date                    );
                    
                    $ll_log_description = $this->GenerateLogDescription($params);
                    $CRMLogs->ll_log_description    = $ll_log_description;
                }
                break;
        }
        
        
        $CRMLogs->save();
    }
    
}