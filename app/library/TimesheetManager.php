<?php
/***********************************************************
TimesheetManager.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 4, 2019
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
use Illuminate\Database\Eloquent\Collection;
use App\models\Timesheet\TimesheetRecords;
use App\models\Users\Users;
use App\models\Timesheet\DayTypes;


class TimesheetManager
{
    const DT_DAILY_WORK             = 110;
    const DT_WEEKEND                = 120;
    const DT_PENDING                = 130;
    const DT_PERSONAL_HOLIDAY       = 140;
    const DT_HOLIDAY                = 150;
    const DT_ABSENT                 = 160;
    const DT_BUSINESS_TRIP          = 170;
    
    
    /**
     * Get list of timesheet records and generate a array contain all records of timesheet for the current day
     * 
     * @author Moe Mantach
     * @access public
     * @param TimesheetRecords $timesheetrecords
     * 
     * @return $result_array['timesheet_month_array']
     * @return $result_array['timesheet_main_array']
     */
    public function GetTimesheetArray( Collection $timesheetrecords )
    { 
        $timesheet_month_array  = array();
        $timesheet_main_array   = array();
        $result_array           = array();
        
        $lst_day_types      = DayTypes::whereDtIsDeleted(0)->get();
        $day_types_array    = CreateDatabaseArrayByIndex($lst_day_types, "dt_id");
        
        $index = 0;
        foreach ( $timesheetrecords as $key => $record_info ) 
        { 
            $date           = $record_info->ts_timesheet_date;
            $checkin_time   = $record_info->ts_timesheet_checkin;
            $checkout_time  = $record_info->ts_timesheet_checkout;
            $checkout_time  = $record_info->ts_timesheet_checkout;
            
            if($checkin_time != null && $checkout_time != null)
            {
                $time = strtotime($checkout_time) - strtotime($checkin_time);
            }
            else {
                $time = 0;
            }
            
            $time_min = $time/60;
            
            $hours = (int)($time_min/60);
            $min = $time_min - $hours * 60;
           
            $min    = ceil($min);
            $hours  = ceil($hours);
            
            $str = strtotime($date);
            
            if(!isset($timesheet_month_array[ $date]))
                $index = 0;
            
            $timesheet_month_array[ $date]["checkin"][$index]     = $checkin_time;
            $timesheet_month_array[ $date]["checkout"][$index]    = $checkout_time;
            $timesheet_month_array[ $date]["hours"][$index]       = $hours;
            $timesheet_month_array[ $date]["mins"][$index]        = $min;
            
            $index++;
            
            if(isset($timesheet_main_array[ $date]))
            {
                $timesheet_main_array[ $date]["hours"] += $hours;
                
                $total_min = $timesheet_main_array[ $date]["mins"] + $min;
                if($total_min > 60)
                {
                   
                    $tmp_hour = $total_min/60;
                    $tmp_hour = floor($tmp_hour);
   
                    $rem_min = $total_min - $tmp_hour*60;

                    $timesheet_main_array[ $date]["hours"] += $tmp_hour;
                    $timesheet_main_array[ $date]["mins"] = $rem_min;
                    
                }
                else {
                    $timesheet_main_array[ $date]["mins"] = $total_min;
                }
                
            }
            else {
                $timesheet_main_array[ $date]["hours"] = $hours;
                $timesheet_main_array[ $date]["mins"] = $min;
            }
            $timesheet_main_array[ $date]['day_type_name'] = $day_types_array[$record_info->fk_date_type]['dt_day_type'];
            $timesheet_main_array[ $date]['day_type_color'] = $day_types_array[$record_info->fk_date_type]['dt_day_color'];
            $timesheet_main_array[ $date]['day_type_hours'] = $day_types_array[$record_info->fk_date_type]['dt_working_hours'];
        }
        
        $result_array['timesheet_month_array']  = $timesheet_month_array;
        $result_array['timesheet_main_array']   = $timesheet_main_array;
        
        return $result_array;
    }
    
    
    /**
     * Get Array of number of transportation days
     * @param unknown $params_array
     */
    public function GetTransportationEmployeeArray( $params_array )
    {
        
        $lst_timesheet_info = $params_array['lst_timesheet_info'];
        $users_array        = $params_array['users_array'];
        $timesheet_main_array = array();
        $min_working_hours = Config::get('appconfig.min_transportation_hours');
        
        $index = 0;
        foreach ( $lst_timesheet_info as $key => $record_info )
        {
            $date           = $record_info->ts_timesheet_date;
            $checkin_time   = $record_info->ts_timesheet_checkin;
            $checkout_time  = $record_info->ts_timesheet_checkout;
            
            if($checkin_time != null && $checkout_time != null)
            {
                $time   = strtotime($checkout_time) - strtotime($checkin_time);
            }
            else 
            {
                $time   = 0;
            }
            
            $time_min   = $time/60;
            
            $hours      = (int)($time_min/60);
            $min        = $time_min - $hours * 60;
            
            $min        = ceil($min);
            $hours      = ceil($hours);
            
                
               
                
                if(isset($timesheet_main_array[ $date]))
                {
                    $timesheet_main_array[$record_info->fk_user_id][$date]["hours"] += $hours;
                    $timesheet_main_array[$record_info->fk_user_id][$date]["mins"] += $min;
                }
                else {
                    $timesheet_main_array[$record_info->fk_user_id][$date]["hours"] = $hours;
                    $timesheet_main_array[$record_info->fk_user_id][$date]["mins"] = $min;
                }
        }
        

        // Array of number of days where the number of working hours is
        // greater then minimum we count as one transportation day
        $result_array       = array();
        foreach ($timesheet_main_array as $user_id => $timesheet_record ) {
 
            $user_info = $users_array[$user_id];
            $daily_working_hours = $user_info['u_daily_working_hours'];
            foreach ( $timesheet_record as $date => $total_time_info ) 
            {
                $hours  = $total_time_info["hours"];
                $mins   = $total_time_info["mins"];
                
                $hours = $hours + $mins/60;
                if($hours >= $min_working_hours)
                {
                    if(isset($result_array[$user_id]))
                        $result_array[$user_id] = $result_array[$user_id] + 1;
                    else 
                        $result_array[$user_id] = 1;
                }
            }
            
        }
        
        return $result_array;
    }
    
    
    /**
     * Set An Array of Personal Holiday
     * 
     * @author Moe Mantach
     * @access public
     * @param object $lst_users
     * @param object $lst_timesheet
     */
    public function GetPersonalHolidaysArray( $lst_users , $lst_holidays )
    {
       $holiday_counts          = array();
       
       $today_date = date("Y-m-d");
       foreach ( $lst_holidays as $key => $hold_info ) {
           $user_id         = $hold_info->ph_user_id;
           $ph_holiday_date = $hold_info->ph_holiday_date;
           
           if($ph_holiday_date > $today_date)
           {
               if( !isset($holiday_counts[ $user_id ]['pending']) )
               {
                   $holiday_counts[ $user_id ]['pending'] = 1;
               }
               else
               {
                   $holiday_counts[ $user_id ]['pending'] = $holiday_counts[ $user_id ]['pending'] + 1;
               }
           }
           else
           {
               if( !isset($holiday_counts[ $user_id ]['taken']) )
               {
                   $holiday_counts[ $user_id ]['taken'] = 1;
               }
               else
               {
                   $holiday_counts[ $user_id ]['taken'] = $holiday_counts[ $user_id ]['taken'] + 1;
               }
               
           }
            
       } 
       

       
       return $holiday_counts;
    }
    
    /**
     * List of Employees and list if they ar`e online or offline
     */  
    public function GetEmployeeStatus()
    {
        $day                = date("d");
        $lst_today_records  = TimesheetRecords::whereDay('ts_timesheet_date', '=', $day)->orderby('ts_id','desc')->get();
        $todays_array       = array();
        $lst_users          = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $user_info_array    = CreateDatabaseArrayByIndex($lst_users, "id");
        
        
        foreach ( $lst_today_records as $key => $tr_info ) 
        {
            if($tr_info->ts_timesheet_checkout == null ) 
            {
                $todays_array[$tr_info->fk_user_id]['status'] = 1;
            }
            else if( !isset($todays_array[$tr_info->fk_user_id]['status']) )
            {
                $todays_array[$tr_info->fk_user_id]['status']= 0;
            }
            
            $todays_array[$tr_info->fk_user_id]['fullname'] = $user_info_array[ $tr_info->fk_user_id ]['u_fullname'];
            
        }
        
        return $todays_array;
    }
}