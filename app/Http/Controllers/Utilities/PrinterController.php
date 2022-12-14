<?php
/***********************************************************
PrinterController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 4, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Controller where the user can send request to print list of data
***********************************************************/


namespace App\Http\Controllers\Utilities;

use App\User;
use Validator;
use Input;
use Request;
use Session;
use Config;
use Redirect;
use File;
use DB;
use App\models\System\Appconfig;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\models\Timesheet\TimesheetRecords;
use App\Library\TimesheetManager;
use App\models\Users\Users;



class PrinterController extends Controller
{
    
    
    /**
     * Print List of data in show in the search to any page
     * 
     * @author Moe Mantach
     * @access public
     * 
     */
    public function PrintData($data_type , $params)
    {
        switch ($data_type)
        {
            case "transportationemployees":
                {
                    $date_array     = explode("-", $params);
                    
                    $year   = $date_array[0];
                    $month  = $date_array[1];
                    
                    $lst_timesheet_info                 = TimesheetRecords::whereYear('ts_timesheet_date',$year)->whereMonth('ts_timesheet_date',$month)->get();
                    $TimesheetManager                   = new TimesheetManager();
                    $lst_users                          = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
                    $users_array                        = CreateDatabaseArrayByIndex($lst_users, "id");
                    $timesheet_array                    = $TimesheetManager->GetTimesheetArray($lst_timesheet_info);
                    $params_array = array(
                        "lst_timesheet_info" => $lst_timesheet_info,
                        "users_array" => $users_array,
                    );
                    $transportation_employees_array     = $TimesheetManager->GetTransportationEmployeeArray($params_array);
                }
            break;
        }
    }
    
}