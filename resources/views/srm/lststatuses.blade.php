<?php
/***********************************************************
lststatuses.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
datatable for list status
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
			  	@foreach($lst_supplier_status  as $index => $ss_info)
                <tr  class="odd gradeX" style="background-color: {{ $ss_info->ss_status_color }}" data-ss_id="{{ $ss_info->ss_id }}">
                	<td><input type="checkbox" name="ck_ss_{{ $ss_info->ss_id }}" id="CK_SS_{{ $ss_info->ss_id }}" class="checkboxes" value="{{ $ss_info->ss_id }}" /></td>
                   <td>{{ $ss_info->ss_id }}</td>
                   <td>{{ $ss_info->ss_status_title }}</td> 
                    <td><a href="#" data-ss_id="{{ $ss_info->ss_id }}" id="EDIT_STATUS_{{ $ss_info->ss_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-ss_id="{{ $ss_info->ss_id }}"  id="DELETE_STATUS_{{ $ss_info->ss_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>