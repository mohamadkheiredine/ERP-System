<?php
/***********************************************************
 * listappresults.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 7/3/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>

@foreach($lst_app_results as $index => $result_info)
    <tr  class="odd gradeX" data-ar_id="{{ $result_info->ar_id }}" bgcolor="{{ $result_info->ar_result_color }}">
        <td><input type="checkbox" name="ck_ar_{{ $result_info->ar_id }}" id="CK_AR_{{ $result_info->ar_id }}" class="checkboxes" value="{{ $result_info->ar_id }}" /></td>
        <td>{{ $result_info->ar_id }}</td>
        <td>{{ $result_info->ResultParent ? $status_info->ResultParent->ar_app_result : "N/A" }}</td>
        <td>{{ $result_info->ar_app_result }}</td>
        <td><a href="#" data-ar_id="{{ $result_info->ar_id }}" id="EDIT_RESULT_{{ $result_info->ar_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
        <td><a href="#" data-ar_id="{{ $result_info->ar_id }}"  id="DELETE_RESULT_{{ $result_info->ar_id }}" ><i class="fa-solid fa-trash"></i></a></td>
    </tr>
@endforeach
