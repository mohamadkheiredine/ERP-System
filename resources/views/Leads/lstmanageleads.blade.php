<?php
/***********************************************************
 * lstmanageleads.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 2/22/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

@foreach($lst_leads as $index => $lead_info)
    <tr  class="odd gradeX" data-cl_id="{{ $lead_info->cl_id }}">
        <td><input type="checkbox" name="ck_cl_{{ $lead_info->cl_id }}" id="CK_CL_{{ $lead_info->cl_id }}" class="checkboxes" value="{{ $lead_info->cl_id }}" /></td>
        <td>{{ $lead_info->cl_id }}</td>
        <td>{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</td>
        <td>{{ $lead_info->cl_region }}&nbsp;{{ $lead_info->cl_area }}</td>
        <td>{{ $lead_info->Salesman ? $lead_info->Salesman->u_fullname : "" }}</td>
        <td>{{ $lead_info->cl_mobile }}</td>
        <td><a href="#" data-cl_id="{{ $lead_info->cl_id }}" id="EDIT_LEAD_{{ $lead_info->cl_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-cl_id="{{ $lead_info->cl_id }}"  id="DELETE_LEAD_{{ $lead_info->cl_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
