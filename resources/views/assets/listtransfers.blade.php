<?php
/***********************************************************
 * listtransfers.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/4/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>

@foreach($lst_asset_transfers  as $index => $transfer_info)
    <tr  class="odd gradeX" data-at_id="{{ $transfer_info->at_id }}">
        <td><input type="checkbox" name="ck_at_{{ $transfer_info->at_id }}" id="CK_AT_{{ $transfer_info->at_id }}" class="checkboxes" value="{{ $transfer_info->at_id }}" /></td>
        <td>{{ $transfer_info->at_id }}</td>
        <td>{{ $transfer_info->Asset->aa_asset_name }}</td>
        <td>{{ $transfer_info->FromDepartment->sd_department_title }}</td>
        <td>{{ $transfer_info->ToDepartment->sd_department_title }}</td>
        <td><a href="#" data-at_id="{{ $transfer_info->at_id }}" id="EDIT_TRANSFER_{{ $transfer_info->at_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-at_id="{{ $transfer_info->at_id }}" id="DELETE_TRANSFER_{{ $transfer_info->at_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
