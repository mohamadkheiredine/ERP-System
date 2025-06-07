<?php
/***********************************************************
listperiods
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 21, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>

@foreach($list_periods  as $index => $period_info)
    <tr  class="odd gradeX" data-pp_id="{{ $period_info->pp_id }}">
        <td><input type="checkbox" name="ck_pp_{{ $period_info->pp_id }}" id="CK_PP_{{ $period_info->pp_id }}" class="checkboxes" value="{{ $period_info->pp_id }}" /></td>
        <td>{{ $period_info->pp_id }}</td>
        <td>{{ $period_info->pp_period_name }}</td>
        <td>{{ $period_info->pp_start_date }}</td>
        <td>{{ $period_info->pp_end_date }}</td>
        <td>{{ $period_info->pp_status }}</td>
        <td><a href="#" data-pp_id="{{ $period_info->pp_id }}" id="EDIT_PERIOD_{{ $period_info->pp_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-pp_id="{{ $period_info->pp_id }}"  id="DELETE_PERIOD_{{ $period_info->pp_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
