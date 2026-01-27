<?php
/***********************************************************
 * lstjobs.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/26/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

@foreach($lst_project_jobs  as $index => $job_info)
    <tr  class="odd gradeX" data-pj_id="{{ $job_info->pj_id }}">
        <td><input type="checkbox" name="ck_pj_{{ $job_info->pj_id }}" id="CK_PJ_{{ $job_info->pj_id }}" class="checkboxes" value="{{ $job_info->pj_id }}" /></td>
        <td>{{ $job_info->pj_id }}</td>
        <td>{{ $job_info->pj_job_code }}</td>
        <td>{{ $job_info->pj_job_name }}</td>
        <td><b>{{ $job_info->Project->pp_project_code }}</b> - <b>{{ $job_info->Project->pp_project_name }}</b></td>
        <td>{{ $job_info->Status->ss_status_title }}</td>
        <td>{{ $job_info->AssignTo->u_fullname }}</td>
        <td>{{ $job_info->pj_actual_start }}</td>
        <td>{{ $job_info->pj_actual_end }}</td>
        <td><a href="#"   data-pj_id="{{ $job_info->pj_id }}" id="EDIT_JOB_{{ $job_info->pj_id }}" ><i class="fas fa-pencil" aria-hidden="true" height="16"></i></a></td>
        <td><a href="#"   data-pj_id="{{ $job_info->pj_id }}" id="DELETE_JOB_{{ $job_info->pj_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach

