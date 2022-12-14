<?php
/***********************************************************
listjobstatus.blade.php
Product :
Version : 1.0
Release : 1
Date Created : May 31, 2020
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
				<th title="Status Name"> Status Name </th>
				<th style="width:2px;" nowrap title="#"> edit </th>
				<th style="width:2px;" nowrap title="#"> Delete </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_job_status  as $index => $status_info)
                <tr  class="odd gradeX" style="background-color: {{ $status_info->js_status_color }}" data-js_id="{{ $status_info->js_id }}">
                	<td><input type="checkbox" name="ck_js_{{ $status_info->js_id }}" id="CK_JS_{{ $status_info->js_id }}" class="checkboxes" value="{{ $status_info->js_id }}" /></td>
                   <td>{{ $status_info->js_id }}</td>
                   <td>{{ $status_info->js_status_title }}</td>  
                    <td><a href="#" data-js_id="{{ $status_info->js_id }}" id="EDIT_STATUS_{{ $status_info->js_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-js_id="{{ $status_info->js_id }}"  id="DELETE_STATUS_{{ $status_info->js_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>