<?php
/***********************************************************
listteams.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 29, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
Display list of teams 
***********************************************************/
?>

@foreach($lst_user_teams as $index => $ut_info)
<tr  class="odd gradeX" data-ut_id="{{ $ut_info->ut_id }}">
	<td><input type="checkbox" name="ck_ut_{{ $ut_info->ut_id }}" id="CK_UT_{{ $ut_info->ut_id }}" class="checkboxes" value="{{ $ut_info->ut_id }}" /></td>
   <td>{{ $ut_info->ut_id }}</td>
   <td>{{ $ut_info->ut_team }}</td>
   <td>{{ $ut_info->TeamMembers->count() }}</td> 
  <td style="width:2px;">  <a href="#"  data-ut_id="{{ $ut_info->ut_id }}" id="EDIT_TEAM_{{ $ut_info->ut_id }}" ><i class="fas fa-edit" aria-hidden="true" height="16" ></i></a> </td>
  <td style="width:2px;"> <a href="#"  data-ut_id="{{ $ut_info->ut_id }}"  id="DELETE_TEAM_{{ $ut_info->ut_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach