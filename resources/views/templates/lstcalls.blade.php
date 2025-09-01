<?php

/***********************************************************
lstcalls
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 17, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>


<table id="tablPendingCalls" border="1" style="width:100%;" class="table table-striped gy-7 gs-7">
        <thead>
                <tr
                        class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th style="width: 2px;">ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Client</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Contract Code</th>
                        <th>Result</th>
                        <th>Description</th>
                </tr>
        </thead>
        <tbody class="LstInboundCalls" id="LstInboundCalls">
            @foreach($lst_inboundcall_info as $index => $inboundcall_info)
            <tr  class="odd gradeX" data-ic_id="{{ $inboundcall_info->ic_id }}">
               <td>{{ $inboundcall_info->ic_id }}</td>
               <td>{{ $inboundcall_info->ic_call_date }}</td>
               <td>{{ $inboundcall_info->ic_call_start_time }}</td>
               <td>{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_code : "-" }}&nbsp;{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_name : "-" }}</td>
               <td>{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_billing_address : "-" }}</td>
               <td>{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_phone : "-" }}</td>
                <td>{{ $inboundcall_info->ic_contract_code }}</td>
                <td>{{ $inboundcall_info->CallResult ? $inboundcall_info->CallResult->cr_result_title : "-" }}</td>
                <td>{{ strip_tags($inboundcall_info->ic_notes) }}</td>
            </tr>
            @endforeach
        </tbody>
</table>
