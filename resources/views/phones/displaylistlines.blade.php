<?php
/***********************************************************
displaylistlines.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 21, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


?>


@foreach($phone_lines as $index => $pl_info)
<tr class="odd gradeX" data-pl_id="{{ $pl_info->pl_id }}">
	<td><input type="checkbox" name="ck_pl_{{ $pl_info->pl_id }}" id="CK_PL_{{ $pl_info->pl_id }}" class="checkboxes" value="{{ $pl_info->pl_id }}" /></td>
   <td>{{ $pl_info->pl_id }}</td>
   <td>{{ $pl_info->pl_line_title }}</td>
   <td>{{ $pl_info->pl_line_number }}</td>
   <td>{{ $pl_info->pl_total_units }}</td> 
    <td><a href="#"  id="EDIT_LINE_{{ $pl_info->pi_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" id="DELETE_LINE_{{ $pl_info->pi_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach