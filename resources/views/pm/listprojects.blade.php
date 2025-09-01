<?php
/***********************************************************
 * listprojects.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/25/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>



@foreach($lst_projects  as $index => $project_info)
    <tr  class="odd gradeX" data-pp_id="{{ $project_info->pp_id }}">
        <td><input type="checkbox" name="ck_pp_{{  $project_info->pp_id }}" id="CK_PP_{{  $project_info->pp_id }}" class="checkboxes" value="{{ $project_info->pp_id }}" /></td>
        <td>{{ $project_info->pp_id }}</td>
        <td>{{ $project_info->pp_project_code }}</td>
        <td>{{ $project_info->pp_project_name }}</td>
        <td>{{ $project_info->Status->ps_status_title }}</td>
        <td>{{ $project_info->pp_start_date }}</td>
        <td>{{ $project_info->pp_end_date }}</td>
        <td><a href="#" data-pp_id="{{ $project_info->pp_id }}" id="EDIT_PROJECT_{{ $project_info->pp_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-pp_id="{{ $project_info->pp_id }}"  id="DELETE_PROJECT_{{ $project_info->pp_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
