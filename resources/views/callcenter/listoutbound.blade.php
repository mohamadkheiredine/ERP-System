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

@foreach($lst_outboundcall_info as $index => $outboundcall_info)
<tr  class="odd gradeX" data-oc_id="{{ $outboundcall_info->oc_id }}">
	<td><input type="checkbox" name="ck_oc_{{ $outboundcall_info->oc_id }}" id="CK_OC_{{ $outboundcall_info->oc_id }}" class="checkboxes" value="{{ $outboundcall_info->oc_id }}" /></td>
   <td>{{ $outboundcall_info->oc_id }}</td>
   <td>{{ $outboundcall_info->Agent->u_fullname }}</td>
   <td>{{ $outboundcall_info->Lead->cl_first_name }}&nbsp;{{ $outboundcall_info->Lead->cl_last_name }}</td> 
   <td>{{ $outboundcall_info->oc_call_date }}</td> 
   <td>{{ $outboundcall_info->oc_call_start_time }}</td>
   <td>{{ $outboundcall_info->oc_call_end_time }}</td>
    <td><a href="#" data-oc_id="{{ $outboundcall_info->oc_id }}" id="EDIT_CALL_{{ $outboundcall_info->oc_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td> 
    <td><a href="#" data-oc_id="{{ $outboundcall_info->oc_id }}"   id="DELETE_CALL_{{ $outboundcall_info->oc_id }}" ><i class="fa-solid fa-trash"></i></a></td> 
</tr>
@endforeach