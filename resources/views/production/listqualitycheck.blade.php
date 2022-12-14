<?php
/***********************************************************
listqualitycheck.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Feb 4, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>

<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>ID</th>
			<th>Check Label</th>
			<th>Team</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		@foreach($lst_quality_check as $index => $qc_info)
            <tr  class="odd gradeX" data-qc_id="{{ $qc_info->qc_id }}">
            	<td><input type="checkbox" name="ck_qc_{{ $qc_info->qc_id }}" id="CK_QC_{{ $qc_info->qc_id }}" class="checkboxes" value="{{ $qc_info->qc_id }}" /></td>
               <td>{{ $qc_info->qc_id }}</td>
               <td>{{ $qc_info->qc_check_label }}</td>
               <td>{{ $qc_info->Team->ut_team }}</td>
               <td><a href="#" data-qc_id="{{ $qc_info->qc_id }}" id="EDIT_CHECK_{{ $qc_info->qc_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
               <td><a href="#" data-qc_id="{{ $qc_info->qc_id }}"  id="DELETE_CHECK_{{ $qc_info->qc_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
            </tr>
            @endforeach
	</tbody>
</table>