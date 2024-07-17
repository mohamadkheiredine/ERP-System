<?php
/***********************************************************
HolidayRequestsController.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 2, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/






namespace App\Http\Controllers\Timesheet;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use Config;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use App\library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\Timesheet\EmploymentType;
use App\models\Timesheet\Holidays;
use App\models\Users\Users;
use App\models\Timesheet\HolidayRequests;
use App\models\Timesheet\PersonalHolidays;
use App\models\Timesheet\TimesheetRecords;



class HolidayRequestsController extends Controller
{

    /**
     * Form Page of Send Holiday Request from the employe to the manager of his department
     * 
     * @author Moe Mantach
     * @access public
     * 
     */
    public function SendHolidayRequest()
    {

        
        $data = array();
        return Response()->view("timesheet.sendholidayrequest",$data);
    }
    
    
    /**
     * Save Holiday Request and send it to the responsible of the department
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveHolidayRequest(Request $request)
    {
        $user_id                = session("user_id");
        $u_department_id        = session("u_department_id");
        $tr_holiday_date_from   = $request->input("tr_holiday_date_from");
        $tr_holiday_date_from   = date("Y-m-d",strtotime($tr_holiday_date_from));
        $tr_holiday_date_to     = $request->input("tr_holiday_date_to");
        $tr_holiday_date_to     = date("Y-m-d",strtotime($tr_holiday_date_to));
        $tr_reason_for_holiday  = $request->input("tr_reason_for_holiday");
        
        // check if this date range is valid
        if($tr_holiday_date_from > $tr_holiday_date_to )
        {
            $result_array['is_error']   = 1;
            $result_array['error_msg']  = "Please Select A Valid Holiday Range !!";
        }
        
        
        $result_array = array();
        
        $HolidayRequest = new HolidayRequests();
        $HolidayRequest->tr_user_id             = $user_id;
        $HolidayRequest->tr_department_id       = $u_department_id;
        $HolidayRequest->tr_reason_for_holiday  = $tr_reason_for_holiday;
        $HolidayRequest->tr_holiday_date_from   = $tr_holiday_date_from;
        $HolidayRequest->tr_holiday_date_to     = $tr_holiday_date_to;
        $HolidayRequest->tr_creation_date       = date("Y-m-d");
        $HolidayRequest->save();
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Holiday Request Has Been Sent !!";
        
        return Response()->json($result_array);
    }
    
    /**
     * Page of Holiday Requests , all requests sent by employees
     * 
     * @author Moe Mantach
     * @access public
     */
    public function HolidayRequest()
    {
        $lst_users          = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_departments    = Departments::whereSdIsDeleted(0)->get();
        
        $data = array(
            "lst_users" => $lst_users,
            "lst_departments" => $lst_departments
        );
        return Response()->view("timesheet.holidayrequests",$data);
    }
    
    
    /**
     * Change Request Status From Allow to Deny and save information why
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveChangeRequestStatus(Request $request)
    {
        $tr_request_ids         = $request->input("tr_request_ids");
        $request_status         = $request->input("request_status");
        $request_information    = $request->input("request_information");
        
        
        $request_ids = explode($tr_request_ids, ",");
        $result_array = array();
        
        // check if $tr_request_ids is number this mean is one number
        if(is_numeric($tr_request_ids))
            $request_ids[0] = $tr_request_ids;
        
        
        foreach ($request_ids as $key => $request_id) {
            $HolidayRequest = HolidayRequests::find($request_id);
            
            // check if old status is approve or deny we will not continue the loop
            if($HolidayRequest->tr_request_status != 0)
                   continue;
            
            $HolidayRequest->tr_request_status = $request_status;
            $HolidayRequest->tr_request_information = $request_information;
            $HolidayRequest->save();
            
            // if status is approve we will add a personal holiday to the selected user
            if($request_status == 1)
            {
                $holiday_date_from  =    $HolidayRequest->tr_holiday_date_from;
                $holiday_date_to    =    $HolidayRequest->tr_holiday_date_to;
                
                $begin = new \DateTime( $holiday_date_from );
                $end   = new \DateTime( $holiday_date_to );
                
                for($i = $begin; $i <= $end; $i->modify('+1 day')){
                    $current_date =  $i->format("Y-m-d");
                    $PersonalHoliday = new PersonalHolidays();
                    $PersonalHoliday->fk_request_id     = $request_id;
                    $PersonalHoliday->ph_holiday_date   = $current_date;
                    $PersonalHoliday->ph_user_id        = $HolidayRequest->tr_user_id;
                    $PersonalHoliday->ph_notes          = $request_information;
                    $PersonalHoliday->save();
                    
                    // save timesheet record with day type as holiday
                    $TimesheetRecord = new TimesheetRecords();
                    $TimesheetRecord->fk_user_id = session('user_id');
                    $TimesheetRecord->fk_date_type  = 4;
                    $TimesheetRecord->ts_timesheet_date = $current_date;
                    $TimesheetRecord->ts_timesheet_checkin  = "00:00:00";
                    $TimesheetRecord->ts_timesheet_checkout = "00:00:00";
                    $TimesheetRecord->save();
                    
                }
               
            }
        }
        

        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        
        return Response()->json($result_array);
    }
    
    
    /**
     * 
     * Display List holiday Requests
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function Displaylistholidayrequests(Request $request)
    {
        $hr_department_id   = $request->input("hr_department_id");
        $hr_user_id         = $request->input("hr_user_id");
        
        $lst_departments    = Departments::whereSdIsDeleted(0)->get();
        $departments_array  = CreateDatabaseArrayByIndex($lst_departments, "tr_id");
        $lst_users          = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $users_array        = CreateDatabaseArrayByIndex($lst_users, "id");
        $HolidayRequests = new HolidayRequests();
        
        if($hr_department_id != '')
        {
            $HolidayRequests = $HolidayRequests->whereTrDepartmentId($hr_department_id);
        }
        
        if($hr_user_id != '')
        {
            $HolidayRequests = $HolidayRequests->whereTrUserId($hr_user_id);
        }
        
        $HolidayRequests = $HolidayRequests->get();
        
        $result_array = array();
 
        $data = array(
            "HolidayRequests" =>$HolidayRequests,
            "departments_array" =>$departments_array,
            "users_array" =>$users_array
        );
        $result_array['is_error'] =0;
        $result_array['display'] = view("timesheet.listholidayrequests" , $data)->render();
        
        return Response()->json($result_array);
    }
    

}