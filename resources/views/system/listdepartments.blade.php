<?php
/***********************************************************
listdepartments.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 2, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@foreach($departments as $index => $dep_info)
<tr  class="odd gradeX" data-d_id="{{ $dep_info->sd_id }}">
	<td><input type="checkbox" name="ck_dep_{{ $dep_info->sd_id }}" id="CK_DEP_{{ $dep_info->sd_id }}" class="checkboxes" value="{{ $dep_info->sd_id }}" /></td>
   <td>{{ $dep_info->sd_id }}</td>
   <td>{{ $dep_info->sd_department_title }}</td>
  <td style="width:2px;">  <a href="#"  data-d_id="{{ $dep_info->sd_id }}" id="EDIT_DEPARTMENT_{{ $dep_info->sd_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a> </td>
  <td style="width:2px;"> <a href="#"  data-d_id="{{ $dep_info->sd_id }}"  id="DELETE_DEPARTMENT_{{ $dep_info->sd_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach