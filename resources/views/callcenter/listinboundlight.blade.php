<?php
/***********************************************************
 * listinboundlight.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 12/29/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>


@foreach($lst_inboundcall_info as $index => $inboundcall_info)
    <tr  class="odd gradeX" data-ic_id="{{ $inboundcall_info->ic_id }}">
        <td><input type="checkbox" name="ck_ic_{{ $inboundcall_info->ic_id }}" id="CK_IC_{{ $inboundcall_info->ic_id }}" class="checkboxes" value="{{ $inboundcall_info->ic_id }}" /></td>
        <td>{{ $inboundcall_info->ic_id }}</td>
        <td>{{ $inboundcall_info->ic_call_date }}</td>
        <td>{{ $inboundcall_info->ic_call_start_time }}</td>
        <td>{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_code : "-" }}&nbsp;{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_name : "-" }}</td>
        <td>{{ $inboundcall_info->Technician ? $inboundcall_info->Technician->u_fullname : "-" }}</td>
        <td>{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_mobile : "-" }}</td>
        <td><a href="#" data-ic_id="{{ $inboundcall_info->ic_id }}" id="EDIT_CALL_{{ $inboundcall_info->ic_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
        <td><a href="#" data-ic_id="{{ $inboundcall_info->ic_id }}"   id="DELETE_CALL_{{ $inboundcall_info->ic_id }}" ><i class="fa-solid fa-trash"></i></a></td>
    </tr>
@endforeach
