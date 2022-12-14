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

***********************************************************/

?>


@foreach($company_holidays as $index => $th_info)
<tr  class="odd gradeX" data-th_id="{{ $th_info->th_id }}">
	<td><input type="checkbox" name="ck_th_{{ $th_info->th_id }}" id="CK_TH_{{ $th_info->th_id }}" class="checkboxes" value="{{ $th_info->th_id }}" /></td>
   <td>{{ $th_info->th_id }}</td>
   <td>{{ $th_info->th_year }}</td>
   <td>{{ $th_info->th_holiday_name }}</td>
   <td>{{ $th_info->th_holiday_date }}</td>
  <td style="width:2px;">  <a href="#"  data-th_id="{{ $th_info->th_id }}" id="EDIT_HOLIDAY_{{ $th_info->th_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a> </td>
  <td style="width:2px;"> <a href="#"  data-th_id="{{ $th_info->th_id }}"  id="DELETE_HOLIDAY_{{ $th_info->th_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach