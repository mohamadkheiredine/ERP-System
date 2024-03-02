<?php
/***********************************************************
listholidays.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Display list of 
***********************************************************/

?>
@foreach($HolidayRequests as $index => $hr_info)
<tr  class="odd gradeX" data-tr_id="{{ $hr_info->tr_id }}">
	<td><input type="checkbox" name="ck_tr_{{ $hr_info->tr_id }}" id="CK_TR_{{ $hr_info->tr_id }}" class="checkboxes" value="{{ $hr_info->tr_id }}" /></td>
   <td>{{ $hr_info->tr_id }}</td>
   <td>{{ isset($departments_array[ $hr_info->tr_department_id ]['sd_department_title']) ? $departments_array[ $hr_info->tr_department_id ]['sd_department_title'] : "N/A" }}</td>
   <td>{{ $users_array[ $hr_info->tr_user_id ]['u_fullname'] }}</td>
   <td>{{ $hr_info->tr_holiday_date_from }}</td>
   <td>{{ $hr_info->tr_holiday_date_to }}</td>
   <td>
   	@switch($hr_info->tr_request_status)
    @case(1)
        Approved
    @break
    @case(0)
        Pending
    @break 
    @case(-1)
        Denied
    @break 
@endswitch
   	</td>
  <td style="width:2px;"> <a href="#"  data-tr_id="{{ $hr_info->tr_id }}"  id="DELETE_REQUEST_{{ $hr_info->tr_id }}" ><i class="fa fa-comment" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach