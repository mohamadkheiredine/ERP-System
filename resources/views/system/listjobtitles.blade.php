<?php
/***********************************************************
listjobtitles.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 2, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@foreach($job_titles as $index => $jt_info)
<tr  class="odd gradeX" data-jt_id="{{ $jt_info->jt_id }}">
	<td><input type="checkbox" name="ck_jt_{{ $jt_info->jt_id }}" id="CK_JT_{{ $jt_info->jt_id }}" class="checkboxes" value="{{ $jt_info->jt_id }}" /></td>
   <td>{{ $jt_info->jt_id }}</td>
   <td>{{ $jt_info->jt_job_title }}</td>
  <td style="width:2px;">  <a href="#"  data-jt_id="{{ $jt_info->jt_id }}" id="EDIT_JOB_TITLE_{{ $jt_info->jt_id }}" ><i class="fa-solid fa-pen-to-square"></i></a> </td>
  <td style="width:2px;"> <a href="#"  data-jt_id="{{ $jt_info->jt_id }}"  id="DELETE_JOB_TITLE_{{ $jt_info->jt_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach