<?php
/***********************************************************
 * lstreportcbleads.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/8/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>
@foreach($lst_leads as $index => $lead_info)
    <tr  class="odd gradeX" data-cl_id="{{ $lead_info->cl_id }}">
        <td><input type="checkbox" name="ck_cl_{{ $lead_info->cl_id }}" id="CK_CL_{{ $lead_info->cl_id }}" class="checkboxes" value="{{ $lead_info->cl_id }}" /></td>
        <td>{{ ( $index + 1 ) }}</td>
        <td>{{ $lead_info->cl_sheet_number }}</td>
        <td>{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</td>
        <td>{{ $lead_info->cl_area }}</td>
        <td>{{ $lead_info->cl_region }}</td>
        <td>{{ $lead_info->LeadType ? $lead_info->LeadType->lt_deal_type : "" }}</td>
        <td>{{ $lead_info->Salesman ? $lead_info->Salesman->u_fullname : "" }}</td>
        <td>{{ $lead_info->Telemarketing ? $lead_info->Telemarketing->u_fullname : "" }}</td>
        <td>{{ $lead_info->cl_mobile }}</td>
        <td>{{ $lead_info->cl_referred_by }}</td>
        <td>{{ $lead_info->cl_last_call_date }}</td>
        <td></td>
        <td>{{ $lead_info->AppResult ? $lead_info->AppResult->ar_app_result : "" }}</td>
        <td>{{ $lead_info->cl_next_call_date }}</td>
        <td>{{ $lead_info->cl_lead_notes }}</td>
    </tr>
@endforeach
