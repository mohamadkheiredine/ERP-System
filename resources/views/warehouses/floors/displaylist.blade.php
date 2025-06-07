<?php
/***********************************************************
 * displaylist.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 3/15/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

@foreach($list_floors  as $index => $floor_info)
    <tr  class="odd gradeX" data-wf_id="{{ $floor_info->wf_id }}">
        <td><input type="checkbox" name="ck_wf_{{ $floor_info->wf_id }}" id="CK_WF_{{ $floor_info->wf_id }}" class="checkboxes" value="{{ $floor_info->wf_id }}" /></td>
        <td>{{ $floor_info->wf_id }}</td>
        <td>{{ $floor_info->wf_floor_title }}</td>
        <td>{{ $floor_info->warehouse->w_warehouse_name }}</td>
        <td>{{ $floor_info->zone->wz_zone_label }}</td>
        <td><a href="#" data-wf_id="{{ $floor_info->wf_id }}" id="EDIT_FLOOR_{{ $floor_info->wf_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-wf_id="{{ $floor_info->wf_id }}"  id="DELETE_FLOOR_{{ $floor_info->wf_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
