<?php
/***********************************************************
DateTimeHelper.php
Product :
Version : 1.0
Release : 2
Date Created : Jun 24, 2016
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,    Softweb S.A.R.L COPYRIGHT 2015

Page Description :
{Enter page description Here}
***********************************************************/

date_default_timezone_set('Asia/Beirut');


/**
 * check time frame if the time < 10 return 1 : monring , if time between 10 and 2 return 2 : noon if greater then 2 afternoon
 *
 * @author Moe Mantach
 * @access public
 * @param unknown $time
 */
function CheckTimeFrame($time)
{
    $morning = strtotime('08:00:00');
    $noun_ten = strtotime('10:00:00');
    $noun_two = strtotime('14:00:00');

    $current_time = strtotime($time);

    if($current_time < $noun_ten)
    {
        return 1;
    }
    elseif( $current_time >= $noun_ten && $current_time < $noun_two )
    {
        return 2;
    }
    else
    {
        return 3;
    }
}


 function GetTimeDifference($created_time)
 {

         //Change as per your default time
        $str = strtotime($created_time);

        $today = strtotime(date('Y-m-d H:i:s'));
        // It returns the time difference in Seconds...
        $time_differnce = $today-$str;

        // To Calculate the time difference in Years...
        $years = 60*60*24*365;

        // To Calculate the time difference in Months...
        $months = 60*60*24*30;

        // To Calculate the time difference in Days...
        $days = 60*60*24;

        // To Calculate the time difference in Hours...
        $hours = 60*60;

        // To Calculate the time difference in Minutes...
        $minutes = 60;

        if(intval($time_differnce/$years) > 1)
        {
            return intval($time_differnce/$years)." years ago";
        }else if(intval($time_differnce/$years) > 0)
        {
            return intval($time_differnce/$years)." year ago";
        }else if(intval($time_differnce/$months) > 1)
        {
            return intval($time_differnce/$months)." months ago";
        }else if(intval(($time_differnce/$months)) > 0)
        {
            return intval(($time_differnce/$months))." month ago";
        }else if(intval(($time_differnce/$days)) > 1)
        {
            return intval(($time_differnce/$days))." days ago";
        }else if (intval(($time_differnce/$days)) > 0)
        {
            return intval(($time_differnce/$days))." day ago";
        }else if (intval(($time_differnce/$hours)) > 1)
        {
            return intval(($time_differnce/$hours))." hours ago";
        }else if (intval(($time_differnce/$hours)) > 0)
        {
            return intval(($time_differnce/$hours))." hour ago";
        }else if (intval(($time_differnce/$minutes)) > 1)
        {
            return intval(($time_differnce/$minutes))." minutes ago";
        }else if (intval(($time_differnce/$minutes)) > 0)
        {
            return intval(($time_differnce/$minutes))." minute ago";
        }else if (intval(($time_differnce)) > 1)
        {
            return intval(($time_differnce))." seconds ago";
        }else
        {
            return "few seconds ago";
        }
    }

    
    function timeago($date) {
        $timestamp = strtotime($date);
        
        $strTime = array("second", "minute", "hour", "day", "month", "year");
        $length = array("60","60","24","30","12","10");
        
        $currentTime = time();
        if($currentTime >= $timestamp) {
            $diff     = time()- $timestamp;
            for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
                $diff = $diff / $length[$i];
            }
            
            $diff = round($diff);
            return $diff . " " . $strTime[$i] . "(s) ago ";
        }
    }
    

    /**
     * get number of days between this 2 dates
     * @param unknown $date_from
     * @param unknown $date_to
     * @return unknown
     */
    function GetNumberOfDays( $date_from , $date_to )
    {
        $DateTo = new DateTime($date_from);

        $DateFrom = new DateTime($date_to);

        $difference = $DateFrom->diff($DateTo);

        return $difference;
    }

    /**
     * function to get allowed days in array by numbers
     *
     * @author Moe Mantach
     * @access public
     * @param Object $days_info
     */
    function GetAllowedDays( $days_info )
    {
        $days_configuration = array();
        $days_configuration[ 2 ] = (isset( $days_info->D1 ) && $days_info->D1 == -1) ? true : false;
        $days_configuration[ 3 ] = (isset( $days_info->D2 ) &&$days_info->D2 == -1) ? true : false;
        $days_configuration[ 4 ] = (isset( $days_info->D3 ) &&$days_info->D3 == -1) ? true : false;
        $days_configuration[ 5 ] = (isset( $days_info->D4 ) &&$days_info->D4 == -1) ? true : false;
        $days_configuration[ 6 ] = (isset( $days_info->D5 ) &&$days_info->D5 == -1) ? true : false;
        $days_configuration[ 7 ] = (isset( $days_info->D6 ) &&$days_info->D6 == -1) ? true : false;
        $days_configuration[ 1 ] = (isset( $days_info->D7 ) &&$days_info->D7 == -1) ? true : false;


        return $days_configuration;
    }


    function writeDaysDropdown( $selected_day )
    {
        $data = array(
            "selected_day" => $selected_day
        );
        return view('general.days',$data)->render();
    }
?>