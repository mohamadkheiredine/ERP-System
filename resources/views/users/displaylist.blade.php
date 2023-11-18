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

    				<!-- @if($user_info->->isOnline())
    					<img src="{{ url('images/online-status.png') }}" height="24" />
    				@else
    				<img src="{{ url('images/offline-status.png') }}" height="24" />
    				@endif -->
***********************************************************/

$session_user_id = session()->get('user_id');
?>

@foreach ( $lst_users as $key => $user_info )
    <tr data-user_id="{{ $user_info->id }}" >
		<td>{{ $user_info->id }}</td>
		<td>{{ $user_info->u_username }}</td>
		<td>{{ $user_info->u_fullname }}</td>
		<td>{{ $user_info->u_email }}</td>
		<td>{{ $user_info->u_phone }}</td>
		<td>
		@if($user_info->isOnline())
			<img src="{{ url('images/online-status.png') }}" height="24" />
		@else
		<img src="{{ url('images/offline-status.png') }}" height="24" />
		@endif
		</td>
		 <td data-user_id="{{ $user_info->id }}"><a href="#"  data-user_id="{{ $user_info->id }}" id="EDIT_USER_{{ $user_info->id }}" ><i class="fa-solid fa-pen-to-square"></i></a></td>
           <?php $display = ($session_user_id == $user_info->id) ? "display:none;" : ""; ?>
           <td><a  data-user_id="{{ $user_info->id }}" href="#" style="{{ $display }}"  id="DELETE_USER_{{ $user_info->id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
	</tr>
@endforeach