<?php
/***********************************************************
 * listprojectroles.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/24/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>


@foreach($lst_project_roles  as $index => $pr_info)
    <tr  class="odd gradeX" data-pr_id="{{ $pr_info->pr_id }}">
        <td><input type="checkbox" name="ck_pr_{{ $pr_info->pr_id }}" id="CK_PR_{{ $pr_info->pr_id }}" class="checkboxes" value="{{ $pr_info->pr_id }}" /></td>
        <td>{{ $pr_info->pr_id }}</td>
        <td>{{ $pr_info->pr_name }}</td>
        <td><a href="#" data-pr_id="{{ $pr_info->pr_id }}" id="EDIT_ROLE_{{ $pr_info->pr_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-pr_id="{{ $pr_info->pr_id }}"  id="DELETE_ROLE_{{ $pr_info->pr_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach

