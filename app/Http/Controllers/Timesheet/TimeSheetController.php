<?php
/***********************************************************
TimeSheetController.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 4, 2019
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
use App\models\Timesheet\PersonalHolidays;
use App\models\Timesheet\TimesheetRecords;
use App\library\TimesheetManager;
use App\models\Timesheet\DayTypes;
use App\models\Users\Users;



class TimeSheetController extends Controller
{

    /**
     * Display Page for Timesheet and ability to checkin/checkoUt
     * 
     * @author Moe Mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        
        $list_days_type = DayTypes::whereDtShowForEmployee(1)->get();
        $data = array(
            "list_days_type" => $list_days_type
        );
        return Response()->view('timesheet.timesheet',$data);
    }
    
    
    /**
     * Display timesheet of current month
     * 
     * @author Moe Mantach
     * @param Request $request
     * @return unknown
     */
    public function DisplayListTimesheet(Request $request)
    {
        $user_id        = session('user_id');
        $month          = date("m");
        $result_array   = array();
        
        
        $lst_timesheets = TimesheetRecords::whereMonth( 'ts_timesheet_date', '=', $month )->whereFkUserId($user_id)->get();
        
        $timesheet_manager = new TimesheetManager();
        
        $timesheet_array        = $timesheet_manager->GetTimesheetArray($lst_timesheets);
        $timesheet_month_array  = $timesheet_array['timesheet_month_array'];
        $timesheet_main_array   = $timesheet_array['timesheet_main_array'];
        
        $data = array( 
            "timesheet_month_array" => $timesheet_month_array,
            "timesheet_main_array" => $timesheet_main_array,
        );
        $result_array['display'] = view("timesheet.lsttimesheet",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Display list of today's that's are online
     * 
     * @author Moe Mantach
     * @access public
     */
    public function onlineemployees()
    {
        
        $data = array();
        
        return Response()->view("timesheet.onlineemployees",$data);
    }
    
    
    /**
     *  Display list of online/offline Employees and 
     *  
     * @param Request $request
     */
    public function DisplayListOnlineemployees(Request $request)
    {
        $result_array   = array();
        
       $TimesheetManagement = new TimesheetManager();
       
       $lst_employee_status = $TimesheetManagement->GetEmployeeStatus();
       $lst_users           = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        
        $data = array(
            "lst_employee_status" => $lst_employee_status,
            "lst_users" => $lst_users,
        );
        
        $result_array['display'] = view("timesheet.listonlineemployees",$data)->render();
        
        
        return Response()->json($result_array);
    }
    
    /**
     * Checkin/Checkout attendance , check if the last record is check in it will save check out on the last record
     * with regular date type and check the personal holidays and company holiday if todays si holiday we return with message
     * 
     * 
     * @param Request $request
     */
    public function CheckInCheckOutAttendance( Request $request )
    {
        $action = $request->input('action');
        $result_array = array();
        $user_id =  session('user_id');
        
        switch($action)
        {
            case "checkin":
                {
                    $Timesheet = new TimesheetRecords();
                    $Timesheet->fk_user_id          = $user_id;
                    $Timesheet->fk_date_type        = 1;
                    $Timesheet->ts_timesheet_date   = date("Y-m-d");
                    $Timesheet->ts_timesheet_checkin= date("H:i:s");
                    $Timesheet->save();
                }
            break;
            case "checkout":
                {
                    $timesheet_note = $request->input('timesheet_note');
                    $ts_day_type    = $request->input('ts_day_type'); 
                    $LastRecordTimesheet = TimesheetRecords::whereFkUserId($user_id)->whereTsTimesheetDate(date("Y-m-d"))->orderby('ts_id')->get();
                    $ts_id = $LastRecordTimesheet[0]['ts_id'];
                    $Timesheet = TimesheetRecords::find($ts_id);
                    $Timesheet->ts_timesheet_checkout = date("H:i:s");
                    $Timesheet->fk_date_type = $ts_day_type;
                    $Timesheet->ts_record_note= $timesheet_note;
                    $Timesheet->save();
                }
            break;
        }
        
        
        $result_array['is_error']  = 0;
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Manage the timesheet records for all empoloyees and able to add/edit and delete records
     * ability to change the day type 
     * 
     * @author MOe Mantach
     * @access public
     */
    public function TimesheetManagement()
    {
        $list_users = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $data = array(
            "list_users" => $list_users
        );
        return Response()->view('timesheet.timesheetmanagement',$data);
    }
    
    
    public function DisplaySelectedTimesheet(Request $request)
    {
        $ts_user        = $request->input("ts_user");
        $ts_date        = $request->input("ts_date");
        $result_array   = array();
        
        $today_timesheet = TimesheetRecords::whereFkUserId($ts_user)->whereTsTimesheetDate($ts_date)->get();
        $lst_day_types   = DayTypes::whereDtIsDeleted(0)->get();

        $data = array(
            "today_timesheet" => $today_timesheet,
            "list_days_type" => $lst_day_types
        );
        $result_array = array();
        $result_array['is_error'] = 0;
        $result_array['display'] = view('timesheet.editdaytimesheet',$data)->render();
        return Response()->json($result_array);
    }
    
    
    /**
     * Display List of Holiday Employees and show the number remaining holidays the number ofpending holidays
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function Displaylistholidayemployees( Request $request )
    {
        $ts_year = $request->input('ts_year');
   
        $personal_holidays = PersonalHolidays::whereYear('ph_holiday_date',$ts_year);
        
        $personal_holidays = $personal_holidays->get();
      
         
        $list_users = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $user_array = CreateDatabaseArrayByIndex($list_users, "id");
        
        $timesheet_manager = new TimesheetManager();
        $personal_holidays_array = $timesheet_manager->GetPersonalHolidaysArray($user_array , $personal_holidays);

        $data = array(
            "user_array" => $user_array,
            "personal_holidays_array" => $personal_holidays_array,
        );
        $result_array = array();
        $result_array['is_error'] = 0;
        $result_array['display'] = view('timesheet.listholidayemployees',$data)->render();
        return Response()->json($result_array);
    }
    
    
    /**
     * Page to display list of transportation Employees
     * 
     * @author Moe Mantach
     * @access public
     */
    public function TransportationEmployees()
    {
        $list_users = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $data = array(
            "list_users" => $list_users
        );
        return Response()->view('timesheet.transportationemployees',$data);
    }
    
    
    
    public function HolidayEmployees()
    {
        $list_users = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $data = array(
            "list_users" => $list_users
        );
        return Response()->view('timesheet.holidayemployees',$data);
    } 
    
    
    /**
     * Display list of transportation Employee
     * @param Request $request
     */
    public function DisplaylistTransportationEmployees(Request $request)
    {
        $te_date        = $request->input('te_date');
        $result_array   = array();
        $te_date = date("Y-m-d",strtotime($te_date));
        $date_array     = explode("-", $te_date);

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

        $result_array['is_error']   = 0;
        $data = array(
            "transportation_employees_array" => $transportation_employees_array,
            "users_array" => $users_array,
        );
        $result_array['display']    = view('timesheet.listtransemployees',$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Save Admin timesheet info 
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveAdminTimesheet(Request $request)
    {
        $ts_id                  = $request->input('ts_id');
        $ts_timesheet_checkin   = $request->input('ts_timesheet_checkin');
        $ts_timesheet_checkout  = $request->input('ts_timesheet_checkout');
        $ts_record_note         = $request->input('ts_record_note');
        $ts_user                = $request->input('ts_user');
        $ts_date                = $request->input('ts_date'); 
        $ts_day_type            = $request->input('ts_day_type');
        
        
        for ($i = 0; $i < count($ts_timesheet_checkin); $i++) 
        {
            $TimesheetRecord  = new TimesheetRecords(); 
            if($ts_id[$i] != 0)
            {
                $TimesheetRecord = TimesheetRecords::find($ts_id[$i]);
            }
          
            
            if(strlen($ts_timesheet_checkin[$i]) == 0 && strlen($ts_timesheet_checkout[$i]) == 0)
                continue;
            
                $checkintime = strtotime($ts_timesheet_checkin[$i]); 
                $checkouttime = strtotime($ts_timesheet_checkout[$i]); 
                
            if( $checkintime > $checkouttime )
            {
                $result_array = array();
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = "the checkout time is less then checkin time please fix it before save";
                return Response()->json($result_array);
            }
                
            $TimesheetRecord->fk_user_id            = $ts_user;

            $TimesheetRecord->ts_timesheet_date     = $ts_date;
            $TimesheetRecord->fk_date_type          = $ts_day_type;
            $TimesheetRecord->ts_timesheet_checkin  = $ts_timesheet_checkin[$i];
            $TimesheetRecord->ts_timesheet_checkout = $ts_timesheet_checkout[$i];
            $TimesheetRecord->ts_record_note        = $ts_record_note[$i];
             
            $TimesheetRecord->save();
            
        }
        
        
        $result_array = array();
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        return Response()->json($result_array);
        
    }  
    

}