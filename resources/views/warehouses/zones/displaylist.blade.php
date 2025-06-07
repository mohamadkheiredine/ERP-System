<?php
/***********************************************************
 * displaylist.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 3/11/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

@foreach($list_zones  as $index => $zone_info)
    <tr  class="odd gradeX" data-wz_id="{{ $zone_info->wz_id }}">
        <td><input type="checkbox" name="ck_wz_{{ $zone_info->wz_id }}" id="CK_WZ_{{ $zone_info->wz_id }}" class="checkboxes" value="{{ $zone_info->wz_id }}" /></td>
        <td>{{ $zone_info->wz_id }}</td>
        <td>{{ $zone_info->wz_zone_label }}</td>
        <td>{{ $zone_info->warehouse->w_warehouse_name }}</td>
        <td><a href="#" data-wz_id="{{ $zone_info->wz_id }}" id="EDIT_ZONE_{{ $zone_info->wz_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-wz_id="{{ $zone_info->wz_id }}"  id="DELETE_ZONE_{{ $zone_info->wz_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
