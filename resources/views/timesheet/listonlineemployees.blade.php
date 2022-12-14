<?php
/***********************************************************
listonlineemployees.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 23, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<table class="table table-bordered m-table m-table--border-brand m-table--head-bg-brand">
	<thead>
		<tr>
			<th>
				#
			</th>
			<th>
				Full Name
			</th>
			<th>
				Status
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach( $lst_users as $index => $user_info )
		<tr>
			<th scope="row">
				{{ $user_info->id }}
			</th>
			<td>
				{{ $user_info->u_fullname }}
			</td>
			<td>
				{!! (isset($lst_employee_status[ $user_info->id ]) && $lst_employee_status[ $user_info->id ]['status'] == 1 ) ? "<span class='m--font-success'>Online</span>" : "<span class='m--font-danger'>Offline</span>" !!}
			</td> 
		</tr>
		@endforeach
	</tbody>
</table>