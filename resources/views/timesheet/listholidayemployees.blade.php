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
@foreach( $user_array as $user_id => $user_info )
 <tr>
	<td>#</td>
	<td>{{ $user_info->u_fullname }}</td>
	<td>{{  $user_info->u_number_holidays }}</td>
	<td>{!! ( isset( $personal_holidays_array[ $user_id ] ) ? $personal_holidays_array[ $user_id ]['taken'] : 0 )  !!}</td>
	<td> {!! ( isset( $personal_holidays_array[ $user_id ] ) ? ( $user_info->u_number_holidays - $personal_holidays_array[ $user_id ]['taken'] ) : $user_info->u_number_holidays )  !!}  </td>
</tr>
@endforeach