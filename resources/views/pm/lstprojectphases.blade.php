<?php
/***********************************************************
 * lstprojectphases.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/28/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>


@foreach($lst_project_phases  as $index => $phases_info)
    <tr  class="odd gradeX" data-pp_id="{{ $phases_info->pp_id }}">
        <td><input type="checkbox" name="ck_pp_{{ $phases_info->pp_id }}" id="CK_PP_{{ $phases_info->pp_id }}" class="checkboxes" value="{{ $phases_info->pp_id }}" /></td>
        <td>{{ $phases_info->pp_phase_code }}</td>
        <td>{{ $phases_info->Department->sd_department_title }}</td>
        <td>{{ $phases_info->pp_phase_name }}</td>
        <td>{{ $phases_info->pp_planned_start }}</td>
        <td>{{ $phases_info->pp_planned_end }}</td>
        <td><a href="#"  data-pp_id="{{ $phases_info->pp_id }}" id="EDIT_PHASES_{{ $phases_info->pp_id }}" ><i class="fas fa-pencil" aria-hidden="true" height="16"></i></a></td>
        <td><a href="#"  data-pp_id="{{ $phases_info->pp_id }}" id="DELETE_PHASES_{{ $phases_info->pp_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
