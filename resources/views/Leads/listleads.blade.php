<?php
/***********************************************************
listleads.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 22, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>
@foreach($lst_leads as $index => $lead_info)
<tr  class="odd gradeX" data-cl_id="{{ $lead_info->cl_id }}">
	<td><input type="checkbox" name="ck_cl_{{ $lead_info->cl_id }}" id="CK_CL_{{ $lead_info->cl_id }}" class="checkboxes" value="{{ $lead_info->cl_id }}" /></td>
   <td>{{ $lead_info->cl_id }}</td>
   <td>{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</td>
   <td>{{ $lead_info->cl_company_name }}</td>
   <td>{{ $lead_info->cl_mobile }}</td>
   <td>{{ $lead_info->cl_email }}</td>
    <td><a href="#" data-cl_id="{{ $lead_info->cl_id }}" id="EDIT_LEAD_{{ $lead_info->cl_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" data-cl_id="{{ $lead_info->cl_id }}"  id="DELETE_LEAD_{{ $lead_info->cl_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach