<?php
/***********************************************************
holidayemployees.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>
 @foreach($lst_total_timesheet_array as $user_id => $timesheet_obj)
<tr>
         <td>{{ $user_id }}</td>
         <td>{{ $timesheet_obj['user_name'] }}</td>
         <td>{{ $timesheet_obj['total_hours'] }}</td>
         <td>{{ $timesheet_obj['total_salary'] }}&nbsp;<b>{{ $timesheet_obj['currency'] }}</b></td>
 </tr>
@endforeach