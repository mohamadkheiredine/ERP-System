<?php
/***********************************************************
 * lstprojecttasks.blade.php
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

@foreach($lst_project_tasks  as $index => $task_info)
    <tr  class="odd gradeX" data-wt_id="{{ $task_info->wt_id }}">
        <td><input type="checkbox" name="ck_wt_{{ $task_info->wt_id }}" id="CK_WT_{{ $task_info->wt_id }}" class="checkboxes" value="{{ $task_info->wt_id }}" /></td>
        <td>{{ $task_info->wt_task_code }}</td>
        <td><a href="#">{{ $task_info->Phase->pp_phase_name }}</a></td>
        <td><a href="#">{{ $task_info->Job->pj_job_name }}</a></td>
        <td>{{ $task_info->wt_task_code }}</td>
        <td>{{ $task_info->wt_task_name }}</td>
        <td>{{ $task_info->wt_planned_start }}</td>
        <td>{{ $task_info->wt_planned_end }}</td>
        <td><a href="#"    data-wt_id="{{ $task_info->wt_id }}" id="EDIT_TASK_{{ $task_info->wt_id }}" ><i class="fas fa-pencil" aria-hidden="true" height="16"></i></a></td>
        <td><a href="#"    data-wt_id="{{ $task_info->wt_id }}" id="DELETE_TASK_{{ $task_info->wt_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
