<?php
/***********************************************************
listpergroups.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<table class="m-datatable" id="html_table" width="100%">
<thead>
	<tr>
		<th title="Id">ID</th>
		<th title="Code">Code</th>
		<th title="label">Label</th>
		<th title="Formula">Formula</th>
		<th style="width:4px !important;" nowrap title="#">edit</th>
		<th style="width:4px !important;" nowrap title="#">Delete</th>
	</tr>
</thead>
<tbody>

     @foreach ( $lst_prez_groups as $key => $group_info )
        <tr>
			<td>{{ $group_info->pg_id }}</td>
			<td>{{ $group_info->pg_group_code }}</td>
			<td>{{ $group_info->pg_group_label }}</td>
			<td>{{ $group_info->pg_group_formula }}</td> 
			 <td style="width:2px;"><a  data-pg_id="{{ $group_info->pg_id }}"  href="#"  id="EDIT_GROUP_{{ $group_info->pg_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td>
               <td style="width:2px;"><a  data-pg_id="{{ $group_info->pg_id }}"  href="#"  id="DELETE_GROUP_{{ $group_info->pg_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
		</tr>
     @endforeach
</tbody>
</table>