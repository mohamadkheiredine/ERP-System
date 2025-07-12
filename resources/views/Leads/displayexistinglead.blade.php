<?php

/***********************************************************
displayexistinglead
Product : titanerp
Version : 1.0
Release : 1
Date Created : Oct 24, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/
?>


@foreach($lst_leads  as $index => $lead_info)
 
    <tr>
        <td></td>
        <td>{{ $lead_info->cl_sheet_number }}</td>
        <td>{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</td>
        <td>{{ $lead_info->cl_area }}</td>
        <td>{{ $lead_info->cl_region }}</td>
        <td>{{ $lead_info->Salesman->u_fullname }}</td>
        <td>{{ $lead_info->Telemarketing->u_fullname }}</td>
        <td>{{ $lead_info->cl_mobile }}</td>
        <td>{{ $lead_info->cl_referred_by }}</td>
    </tr>

@endforeach

