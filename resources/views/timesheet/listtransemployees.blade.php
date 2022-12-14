<?php
/***********************************************************
listtransemployees.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 25, 2019
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
				Transportation Days
			</th>
			<th>
				Total Transportation Fees
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach( $users_array as $index => $user_info )
		<tr>
			<th scope="row">
				{{ $user_info->id }}
			</th>
			<td>
				{{ $user_info->u_fullname }}
			</td>
			<td>
				{!! (isset($transportation_employees_array[ $user_info->id ]) ) ? $transportation_employees_array[ $user_info->id ] : 0 !!}
			</td> 
			<td>
				{{ (isset($transportation_employees_array[ $user_info->id ]) ) ? session('company_transportation_fees') * $transportation_employees_array[ $user_info->id ] : 0 }}&nbsp;&nbsp;<b>{{ session('currency_symbol') }}</b>
			</td> 
		</tr>
		@endforeach
	</tbody>
</table>