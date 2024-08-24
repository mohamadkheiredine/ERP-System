<?php
/***********************************************************
listinbound.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :

***********************************************************/

?>

@foreach($lst_cases_status as $index => $status_info)
<tr  class="odd gradeX" data-cc_id="{{ $status_info->cc_id }}" bgcolor="{{ $status_info->cc_status_color }}">
	<td><input type="checkbox" name="ck_cc_{{ $status_info->cc_id }}" id="CK_CC_{{ $status_info->cc_id }}" class="checkboxes" value="{{ $status_info->cc_id }}" /></td>
   <td>{{ $status_info->cc_id }}</td>
   <td>{{ $status_info->Status ? $status_info->Status->cc_status_title : "N/A" }}</td>
   <td>{{ $status_info->cc_status_title }}</td>
    <td><a href="#" data-cc_id="{{ $status_info->cc_id }}" id="EDIT_STATUS_{{ $status_info->cc_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td> 
    <td><a href="#" data-cc_id="{{ $status_info->cc_id }}"  id="DELETE_STATUS_{{ $status_info->cc_id }}" ><i class="fa-solid fa-trash"></i></a></td> 
</tr>
@endforeach