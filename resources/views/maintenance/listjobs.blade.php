<?php
/***********************************************************
listjobs.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 5, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>

<table class="m-datatable" id="html_table" width="50%">
		<thead>
			<tr>
				<th title="#">#</th>
				<th title="Id"> ID </th>
				<th title="Job Code"> Job Code </th>
				<th title="Job Label"> Job Label </th>
				<th title="Customer"> Customer </th>
				<th title="Due Date"> Due Date </th>
				<th style="width:2px;" nowrap title="#"> edit </th>
				<th style="width:2px;" nowrap title="#"> Delete </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_jobs  as $index => $job_info)
                <tr  class="odd gradeX"  data-j_id="{{ $job_info->j_id }}">
                	<td><input type="checkbox" name="ck_j_{{ $job_info->j_id }}" id="CK_J_{{ $job_info->j_id }}" class="checkboxes" value="{{ $job_info->j_id }}" /></td>
                   <td>{{ $job_info->j_id }}</td>
                   <td>{{ $job_info->j_job_code }}</td>  
                   <td>{{ $job_info->j_job_title }}</td>  
                   <td>{{ ($job_info->j_customer_id != 0) ? $job_info->Customer->ic_customer_name : "N/A" }}</td>  
                   <td>{{ $job_info->j_due_date }}</td>   
                    <td><a href="#" data-j_id="{{ $job_info->j_id }}" id="EDIT_JOB_{{ $job_info->j_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-j_id="{{ $job_info->j_id }}"  id="DELETE_JOB_{{ $job_info->j_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>