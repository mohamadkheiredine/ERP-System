<?php
/***********************************************************
liststatus.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 9, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>
@foreach($lst_order_status  as $index => $status_info)
<tr  class="odd gradeX"  data-ss_id="{{ $status_info->ss_id }}">
    <td><input type="checkbox" name="ck_ss_{{ $status_info->ss_id }}" id="CK_OS_{{ $status_info->ss_id }}" class="checkboxes" value="{{ $status_info->ss_id }}" /></td>
    <td>{{ $status_info->ss_id }}</td>
    <td>{{ $status_info->ss_status_title }}</td> 
    <td>{{ ( $status_info->ss_status_parent_id == 0 ) ? '-' : $order_status_array[ $status_info->ss_status_parent_id ]['ss_status_title'] }}</td> 
    <td><a href="#" data-ss_id="{{ $status_info->ss_id }}" id="EDIT_STATUS_{{ $status_info->ss_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" data-ss_id="{{ $status_info->ss_id }}"  id="DELETE_STATUS_{{ $status_info->ss_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach
