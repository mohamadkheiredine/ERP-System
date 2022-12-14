<?php
/***********************************************************
liststatus.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 9, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<table class="m-datatable" id="html_table" width="50%">
		<thead>
			<tr>
				<th title="#">#</th>
				<th title="Id"> ID </th>
				<th title="Status Name"> Status Name </th>
				<th title="Depend Status"> depend status </th>
				<th style="width:2px;" nowrap title="#"> edit </th>
				<th style="width:2px;" nowrap title="#"> Delete </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_order_status  as $index => $status_info)
                <tr  class="odd gradeX" style="background-color: {{ $status_info->os_status_color }}" data-os_id="{{ $status_info->os_id }}">
                	<td><input type="checkbox" name="ck_os_{{ $status_info->os_id }}" id="CK_OS_{{ $status_info->os_id }}" class="checkboxes" value="{{ $status_info->os_id }}" /></td>
                   <td>{{ $status_info->os_id }}</td>
                   <td>{{ $status_info->os_status_title }}</td> 
                   <td>{{ ( $status_info->os_status_parent_id == 0 ) ? '-' : $order_status_array[ $status_info->os_status_parent_id ]['os_status_title'] }}</td> 
                    <td><a href="#" data-os_id="{{ $status_info->os_id }}" id="EDIT_STATUS_{{ $status_info->os_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-os_id="{{ $status_info->os_id }}"  id="DELETE_STATUS_{{ $status_info->os_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>