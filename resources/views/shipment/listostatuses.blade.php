<?php
/***********************************************************
listostatuses.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>




@foreach($operation_status as $index => $os_info)
<tr  class="odd gradeX" bgcolor="{{ $os_info->os_status_color }}" data-os_id="{{ $os_info->os_id }}">
  <td><input type="checkbox" name="ck_os_{{ $os_info->os_id }}" id="CK_OS_{{ $os_info->os_id }}" class="checkboxes" value="{{ $os_info->os_id }}" /></td>
  <td>{{ $os_info->os_id }}</td>
  <td>{{ $os_info->os_status_title }}</td>
  <td>{{ $os_info->os_status_order }}</td>
  <td style="width:2px;"><a href="#"  data-os_id="{{ $os_info->os_id }}" id="EDIT_STATUS_{{ $os_info->os_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a> </td>
  <td style="width:2px;"><a href="#"  data-os_id="{{ $os_info->os_id }}"  id="DELETE_STATUS_{{ $os_info->os_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach