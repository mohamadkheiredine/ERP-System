<?php
/***********************************************************
listemptype.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 3, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>


@foreach($employment_type as $index => $et_info)
    <tr  class="odd gradeX" data-et_id="{{ $et_info->et_id }}">
    <td><input type="checkbox" name="ck_et_{{ $et_info->et_id }}" id="CK_ET_{{ $et_info->et_id }}" class="checkboxes" value="{{ $et_info->et_id }}" /></td>
    <td>{{ $et_info->et_id }}</td>
    <td>{{ $et_info->et_type }}</td>
    <td>{{ $et_info->et_min_working_hours }}</td>
    <td style="width:2px;">  <a href="#"  data-et_id="{{ $et_info->et_id }}" id="EDIT_EMPTYPE_{{ $et_info->et_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a> </td>
    <td style="width:2px;"> <a href="#"  data-et_id="{{ $et_info->et_id }}"  id="DELETE_EMPTYPE_{{ $et_info->et_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
    </tr>
    @endforeach