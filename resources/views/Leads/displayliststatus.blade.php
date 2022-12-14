<?php
/***********************************************************
displayliststatus.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Feb 8, 2020
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
				<th title="Depend Status"> depend status </th>
				<th style="width:2px;" nowrap title="edit"> edit </th>
				<th style="width:2px;" nowrap title="delete"> Delete </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_lead_status  as $index => $ls_info)
                <tr  class="odd gradeX" data-ls_id="{{ $ls_info->ls_id }}">
                	<td><input type="checkbox" name="ck_ls_{{ $ls_info->ls_id }}" id="CK_LS_{{ $ls_info->ls_id }}" class="checkboxes" value="{{ $ls_info->ls_id }}" /></td>
                   <td>{{ $ls_info->ls_id }}</td>
                   <td>{{ $ls_info->ls_status_title }}</td> 
                   <td>{{ ( $ls_info->fk_parent_status == 0 || $ls_info->fk_parent_status == NULL ) ? '-' : $lead_status_array[ $ls_info->fk_parent_status ]['ls_status_title'] }}</td> 
                    <td><a href="#" data-ls_id="{{ $ls_info->ls_id }}" id="EDIT_STATUS_{{ $ls_info->ls_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-ls_id="{{ $ls_info->ls_id }}"  id="DELETE_STATUS_{{ $ls_info->ls_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>