<?php
/***********************************************************
 * downloadcallbackreport.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 6/22/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

<div class="table-responsive">
    <div class="table-scroll-wrapper">
        <table class="table fixed-header-table">
            <thead class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            <tr>
                <th title="index">Index</th>
                <th title="RS#"> RS# </th>
                <th title="Lead name"> Lead Name </th>
                <th title="Area"> Area </th>
                <th title="Region"> Region </th>
                <th title="Leads Type"> Leads Type </th>
                <th title="Salesman"> Salesman </th>
                <th title="Telemarketer"> Telemarketer </th>
                <th title="Mobile"> Mobile </th>
                <th title="Referred By"> Referred by </th>
                <th title="Last Call Date"> Last Call Date </th>
                <th title="Last Result">Last Result</th>
                <th title="Next Call">Next Call</th>
                <th title="Notes">Notes</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lst_leads as $index => $lead_info)
                <tr  class="odd gradeX" data-cl_id="{{ $lead_info->cl_id }}">
                    <td>{{ ( $index + 1 ) }}</td>
                    <td>{{ $lead_info->cl_sheet_number }}</td>
                    <td>{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</td>
                    <td>{{ $lead_info->cl_area }}</td>
                    <td>{{ $lead_info->cl_region }}</td>
                    <td>{{ $lead_info->LeadType ? $lead_info->LeadType->lt_deal_type : "" }}</td>
                    <td>{{ $lead_info->Salesman ? $lead_info->Salesman->u_fullname : "" }}</td>
                    <td>{{ $lead_info->Telemarketing ? $lead_info->Telemarketing->u_fullname : "" }}</td>
                    <td>{{ $lead_info->cl_mobile }}</td>
                    <td>{{ $lead_info->cl_referred_by }}</td>
                    <td>{{ $lead_info->cl_last_call_date }}</td>
                    <td>{{ $lead_info->AppResult ? $lead_info->AppResult->ar_app_result : "" }}</td>
                    <td>{{ $lead_info->cl_next_call_date }}</td>
                    <td>{{ $lead_info->cl_lead_notes }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
