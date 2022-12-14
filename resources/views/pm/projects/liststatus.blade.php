<?php
/***********************************************************
listtypes.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>


@foreach($lst_project_status  as $index => $ps_info)
<tr  class="odd gradeX" data-ps_id="{{ $ps_info->ps_id }}">
	<td><input type="checkbox" name="ck_ps_{{ $ps_info->ps_id }}" id="CK_PS_{{ $ps_info->ps_id }}" class="checkboxes" value="{{ $ps_info->ps_id }}" /></td>
   <td>{{ $ps_info->ps_id }}</td>
   <td>{{ $ps_info->pt_type_name }}</td>  
    <td><a href="#" data-ps_id="{{ $ps_info->ps_id }}" id="EDIT_STATUS_{{ $ps_info->ps_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" data-ps_id="{{ $ps_info->ps_id }}"  id="DELETE_STATUS_{{ $ps_info->ps_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach