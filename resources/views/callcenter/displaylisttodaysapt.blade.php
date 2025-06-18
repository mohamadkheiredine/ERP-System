<?php
/***********************************************************
displaylistapt
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 9, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>

<table class="table table-bordered">
            <thead>
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th title="#"></th>
                            <th title="Date"> Date </th>
                            <th title="Time"> Time </th>
                            <th title="Full Name"> Lead Name </th>
                            <th title="Area"> Address </th>
                            <th title="Mobile"> Mobile </th>
                            <th title="Salesman"> Salesman </th>
                            <th title="Result"> Result </th>
                            <th title="Telemarketing"> Telemarketing </th>
                            <th title="Lead Type"> Lead Type </th>
                            <th title="Reffered By"> Reffered By </th>
                            <th title="Confirmed"> Confirmed </th>
                            <th></th>
                    </tr>
            </thead>
            <tbody id="LstLeadAppts">
@foreach($lst_apppointments as $index => $app_info)
<tr  class="odd gradeX" data-ca_id="{{ $app_info->ca_id }}">
   <td>{{ $index + 1 }}</td>
   <td>{{ $app_info->ca_apt_date }}</td>
   <td>{{ $app_info->ca_apt_time }}</td>
   <td>{{ $app_info->Lead->cl_first_name }}&nbsp;{{ $app_info->Lead->cl_last_name }}</td>
   <td>{{ $app_info->Lead->cl_state }}&nbsp;{{ $app_info->Lead->cl_area }}</td>
   <td>{{ $app_info->Lead->cl_mobile }}</td>
   <td>{{ $app_info->Salesman ? $app_info->Salesman->u_fullname : "-" }}</td>
   <td>{{ $app_info->AppResult ? $app_info->AppResult->ar_app_result : "Pending" }}</td>
   <td>{{ $app_info->Telemarketing ? $app_info->Telemarketing->u_fullname : "-" }}</td>
   <td>{{ $app_info->Lead->LeadType ? $app_info->Lead->LeadType->lt_deal_type : "-"  }}</td>
   <td>{{ $app_info->Lead->cl_referred_by  }}</td>
   <td>{{ $app_info->ca_lead_confirm  == 1? "Confirmed" : "Pending"  }}</td>
    <td><a href="#" data-ca_id="{{ $app_info->ca_id }}"   id="DELETE_APP_{{ $app_info->ca_id }}" ><i class="fa-solid fa-trash"></i></a></td>
</tr>
@endforeach
            </tbody>
</table>
