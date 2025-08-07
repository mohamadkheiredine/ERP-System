<?php
/***********************************************************
 * lststatuses.bade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/3/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



?>

@foreach($lst_system_status as $index => $status_info)
    <tr  class="odd gradeX" data-ss_id="{{ $status_info->ss_id }}" bgcolor="{{ $status_info->ss_status_color }}">
        <td><input type="checkbox" name="ck_ss_{{ $status_info->ss_id }}" id="CK_SS_{{ $status_info->ss_id }}" class="checkboxes" value="{{ $status_info->ss_id }}" /></td>
        <td>{{ $status_info->ss_id }}</td>
        <td>{{ $status_info->Status ? $status_info->Status->ss_status_title : "N/A" }}</td>
        <td>{{ $status_info->ss_status_title }}</td>
        <td><a href="#" data-ss_id="{{ $status_info->ss_id }}" id="EDIT_STATUS_{{ $status_info->ss_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
        <td><a href="#" data-ss_id="{{ $status_info->ss_id }}"  id="DELETE_STATUS_{{ $status_info->ss_id }}" ><i class="fa-solid fa-trash"></i></a></td>
    </tr>
@endforeach
