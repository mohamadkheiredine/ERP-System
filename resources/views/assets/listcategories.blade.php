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


@foreach($lst_asset_categories  as $index => $category_info)
    <tr  class="odd gradeX" data-ac_id="{{ $category_info->ac_id }}">
        <td><input type="checkbox" name="ck_ac_{{ $category_info->ac_id }}" id="CK_AC_{{ $category_info->ac_id }}" class="checkboxes" value="{{ $category_info->ac_id }}" /></td>
        <td>{{ $category_info->ac_id }}</td>
        <td>{{ $category_info->ac_category_name }}</td>
        <td><a href="#" data-ac_id="{{ $category_info->ac_id }}" id="EDIT_CATEGORY_{{ $category_info->ac_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-ac_id="{{ $category_info->ac_id }}"  id="DELETE_CATEGORY_{{ $category_info->ac_id}}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
