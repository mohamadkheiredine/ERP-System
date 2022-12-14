<?php
/***********************************************************
displaylistoperations.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 16, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@foreach($lst_operations as $index => $so_info)
<tr  class="odd gradeX" data-so_id="{{ $so_info->so_id }}">
	<td><input type="checkbox" name="ck_so_{{ $so_info->so_id }}" id="CK_SO_{{ $so_info->so_id }}" class="checkboxes" value="{{ $so_info->so_id }}" /></td>
   <td>{{ $so_info->so_id }}</td>
   <td>{{ $so_info->so_operation_reference }}</td>
   <td>{{ $so_info->so_operation_label }}</td>
   <td>{{ $so_info->so_operation_date }}</td>
   <td>{{ $so_info->so_operation_time }}</td>
  <td style="width:2px;">  <a href="#"  data-so_id="{{ $so_info->so_id }}" id="EDIT_SHIPOP_{{ $so_info->so_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a> </td>
  <td style="width:2px;"> <a href="#"  data-so_id="{{ $so_info->so_id }}"  id="DELETE_SHIPOP_{{ $so_info->so_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach