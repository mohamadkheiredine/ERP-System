<?php
/***********************************************************
 * listlocations.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/1/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>


@foreach($lst_asset_locations  as $index => $location_info)
    <tr  class="odd gradeX" data-il_id="{{ $location_info->il_id }}">
        <td><input type="checkbox" name="ck_il_{{ $location_info->il_id }}" id="CK_IL_{{ $location_info->il_id }}" class="checkboxes" value="{{ $location_info->il_id }}" /></td>
        <td>{{ $location_info->il_id }}</td>
        <td>{{ $location_info->il_location_name }}</td>
        <td>{{ $location_info->il_address }}</td>
        <td><a href="#" data-il_id="{{ $location_info->il_id }}" id="EDIT_LOCATION_{{ $location_info->il_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-il_id="{{ $location_info->il_id }}"  id="DELETE_LOCATION_{{ $location_info->il_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
