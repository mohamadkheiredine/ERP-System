<?php
/***********************************************************
listholidayemployees.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/
 
?>

<table class="table table-bordered m-table m-table--border-brand m-table--head-bg-brand">
	<thead>
		<tr>
			<th>#</th>
			<th>User Fullname</th>
			<th>Number Of holidays Allowed</th>
			<th>Number Of holidays taken</th>
			<th>Number Of holidays Remaining</th>
		</tr>
	</thead>
	<tbody>
		@foreach( $user_array as $user_id => $user_info )
		 <tr>
			<td>#</td>
			<td>{{ $user_info->u_fullname }}</td>
			<td>{{  $user_info->u_number_holidays }}</td>
			<td>{!! ( isset( $personal_holidays_array[ $user_id ] ) ? $personal_holidays_array[ $user_id ]['taken'] : 0 )  !!}</td>
			<td> {!! ( isset( $personal_holidays_array[ $user_id ] ) ? ( $user_info->u_number_holidays - $personal_holidays_array[ $user_id ]['taken'] ) : $user_info->u_number_holidays )  !!}  </td>
		</tr>
		@endforeach
	</tbody>
</table>