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
@foreach ( $lst_prez_groups as $key => $group_info )
<tr>
	<td>{{ $group_info->pg_id }}</td>
	<td>{{ $group_info->pg_group_code }}</td>
	<td>{{ $group_info->pg_group_label }}</td>
	<td>{{ $group_info->pg_group_formula }}</td> 
	 <td style="width:2px;"><a  data-pg_id="{{ $group_info->pg_id }}"  href="#"  id="EDIT_GROUP_{{ $group_info->pg_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
       <td style="width:2px;"><a  data-pg_id="{{ $group_info->pg_id }}"  href="#"  id="DELETE_GROUP_{{ $group_info->pg_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach