<?php
/***********************************************************
listinbound.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :

***********************************************************/

?>

@foreach($lst_lead_apps as $index => $app_info)
<tr  class="odd gradeX" data-ca_id="{{ $app_info->ca_id }}">
   <td>{{ $app_info->ca_apt_date }}</td>
   <td>{{ $app_info->ca_apt_time }}</td>
   <td>{{ $app_info->Lead->cl_first_name }}&nbsp;{{ $app_info->Lead->cl_last_name }}</td>
   <td>{{ $app_info->Lead->cl_area }}</td>
   <td>{{ $app_info->Salesman ? $app_info->Salesman->u_fullname : "-" }}</td>
   <td>{{ $app_info->AppResult ? $app_info->AppResult->ar_app_result : "Pending" }}</td>
   <td>{{ $app_info->Telemarketing ? $app_info->Telemarketing->u_fullname : "-" }}</td>
    <td>{{ $app_info->Lead->cl_mobile }}</td>
   <td>{{ $app_info->ca_lead_confirm  == 1? "Confirmed" : "Pending"  }}</td>
    <td><a href="#" data-ca_id="{{ $app_info->ca_id }}"   id="EDIT_APP_{{ $app_info->ca_id }}" ><i class="fas fa-edit"></i></a></td>
    <td><a href="#" data-ca_id="{{ $app_info->ca_id }}"   id="DELETE_APP_{{ $app_info->ca_id }}" ><i class="fa-solid fa-trash"></i></a></td>
</tr>
@endforeach
