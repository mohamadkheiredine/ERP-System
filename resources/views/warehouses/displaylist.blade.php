<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 2
Date Created :Sep 8, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/

$session_user_id = session()->get('user_id');
?>

<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="#">#</th>
				<th title="Id">ID</th>
				<th title="Name">Warehouse Name</th>
				<th title="City">warehouse City</th>
				<th title="Status">Status</th>
				<th title="edit">edit</th>
				<th title="settings">settings</th>
				<th title="delete">Delete</th>
			</tr>
		</thead>
		<tbody>

		     <?php  foreach ( $lst_warehouses as $key => $warehouse_info ) { ?>
                <tr>
    				<td><input type="checkbox" name="ck_w_{{ $warehouse_info->w_id }}" id="CK_W_{{ $warehouse_info->w_id }}" class="checkboxes" value="{{ $warehouse_info->w_id }}" /></td>
    				<td>{{ $warehouse_info->w_id }}</td>
    				<td>{{ $warehouse_info->w_warehouse_name }}</td>
    				<td>{{ $warehouse_info->w_warehouse_city }}</td>
    				<td>{!! $warehouse_info->w_warehouse_status == 1 ? "<span class='m--font-success'>Active</span>" :  "<span class='m--font-danger'>Inactive</span>" !!}</td>
    				 <td><a  data-w_id="{{ $warehouse_info->w_id }}"  href="#"  id="EDIT_WAREHOUSE_{{ $warehouse_info->w_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td>
                     <td><a  data-w_id="{{ $warehouse_info->w_id }}"  href="#"  id="SETTINGS_WAREHOUSE_{{ $warehouse_info->w_id }}" ><i class="fas fa-cog" aria-hidden="true" height="16" ></i></a></td>
                     <td><a  data-w_id="{{ $warehouse_info->w_id }}"  href="#"  id="DELETE_WAREHOUSE_{{ $warehouse_info->w_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
    			</tr>
		     <?php } ?>

			</tbody>
</table>