<?php
/***********************************************************
listjobroles.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 2, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@foreach($job_roles as $index => $jr_info)
<tr  class="odd gradeX" data-jr_id="{{ $jr_info->jr_id }}">
	<td><input type="checkbox" name="ck_jr_{{ $jr_info->jr_id }}" id="CK_JR_{{ $jr_info->jr_id }}" class="checkboxes" value="{{ $jr_info->jr_id }}" /></td>
   <td>{{ $jr_info->jr_id }}</td>
   <td>{{ $jr_info->jr_job_role }}</td>
  <td style="width:2px;">  <a href="#"  data-jr_id="{{ $jr_info->jr_id }}" id="EDIT_JOB_ROLE_{{ $jr_info->jr_id }}" ><i class="fa-solid fa-pen-to-square"></i></a> </td>
  <td style="width:2px;"> <a href="#"  data-jr_id="{{ $jr_info->jr_id }}"  id="DELETE_JOB_ROLE_{{ $jr_info->jr_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach