<?php
/***********************************************************
 * lstphases.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/31/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>
@foreach($lst_project_phases  as $index => $phases_info)
    <tr  class="odd gradeX" data-pp_phase_id="{{ $phases_info->pp_phase_id }}">
        <td><input type="checkbox" name="ck_PHASE_{{ $phases_info->pp_phase_id }}" id="CK_PHASE_{{ $phases_info->pp_phase_id }}" class="checkboxes" value="{{ $phases_info->pp_phase_id }}" /></td>
        <td>{{ $phases_info->pp_phase_id }}</td>
        <td>{{ $phases_info->pp_phase_code }}</td>
        <td>{{ $phases_info->Project->pp_project_code }}&nbsp;-&nbsp;{{ $phases_info->Project->pp_project_name }}</td>
        <td>{{ $phases_info->Department->sd_department_title }}</td>
        <td>{{ $phases_info->pp_planned_start }}</td>
        <td>{{ $phases_info->pp_planned_end }}</td>
        <td><a href="#" data-pp_phase_id="{{ $phases_info->pp_phase_id }}" id="EDIT_PHASE_{{ $phases_info->pp_phase_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-pp_phase_id="{{ $phases_info->pp_phase_id }}"  id="DELETE_PHASE_{{ $phases_info->pp_phase_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach

