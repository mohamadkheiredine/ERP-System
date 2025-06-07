<?php
/***********************************************************
listleadresults
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 29, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/


?>

@foreach($lst_lead_results as $index => $res_info)
<tr>
    <td> {{ $res_info->Salesman ? $res_info->Salesman->u_fullname : "" }} </td>
    <td> {{ $res_info->Telemarketing ? $res_info->Telemarketing->u_fullname : "" }} </td>
    <td> {{ $res_info->lr_next_call }} </td>
    <td> {{ $res_info->lr_text_notes }} </td>
    <td> {{ $res_info->AppResult ? $res_info->AppResult->ar_app_result : "" }} </td>
</tr>
@endforeach
