<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 28, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>


@foreach($lst_plan_status  as $index => $ps_info)
    <tr  class="odd gradeX" data-ps_id="{{ $ps_info->ps_id }}">
        <td><input type="checkbox" name="ck_ps_{{ $ps_info->ps_id }}" id="CK_PS_{{ $ps_info->ps_id }}" class="checkboxes" value="{{ $ps_info->ps_id }}" /></td>
        <td>{{ $ps_info->ps_id }}</td>
        <td>{{ $ps_info->ps_status_title }}</td>
        <td>{{ ( $ps_info->ps_parent_status == 0 ) ? '-' : $plan_status_array[ $ps_info->ps_parent_status ]['ps_status_title'] }}</td>
        <td><a href="#" data-ps_id="{{ $ps_info->ps_id }}" id="EDIT_STATUS_{{ $ps_info->ps_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-ps_id="{{ $ps_info->ps_id }}"  id="DELETE_STATUS_{{ $ps_info->ps_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
