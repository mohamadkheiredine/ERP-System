<?php
/***********************************************************
 * listterminals.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/21/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>


@foreach($list_terminals  as $index => $terminal_info)
    <tr  class="odd gradeX" data-pt_id="{{ $terminal_info->pt_id }}">
        <td><input type="checkbox" name="ck_terminal_{{ $terminal_info->pt_id }}" id="CK_TERMINAL_{{ $terminal_info->pt_id }}" class="checkboxes" value="{{ $terminal_info->pt_id }}" /></td>
        <td>{{ $terminal_info->pt_id }}</td>
        <td>{{ $terminal_info->Store->ps_store_name }}</td>
        <td>{{ $terminal_info->pt_terminal_name }}</td>
        <td>{{ $terminal_info->Manager->u_fullname }}</td>
        <td><a href="#" data-pt_id="{{ $terminal_info->pt_id }}" id="EDIT_TERMINAL_{{ $terminal_info->pt_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-pt_id="{{ $terminal_info->pt_id }}"  id="DELETE_TERMINAL_{{ $terminal_info->pt_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach


