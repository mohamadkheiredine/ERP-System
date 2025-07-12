<?php
/***********************************************************
 * reportcallbackleads.blade.php
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


@extends('layouts.layout',['page_title' => "Call Center Management"])

@section('themes')
    <style>
        th{
            cursor: pointer;
        }
        #ModelPopUp{
            width:800px;
        }
        .SwitchDisplay i{
            font-size: 24px;
        }
    </style>
@endsection
@section('plugins')
    <script type="text/javascript" src="{{ url('js/modules/callapt.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/callcenter/callbackleads.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Callback Leads</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item DownloadLeads" data-action_type="DOWNLOAD_LEADS" href="#">Download Leads</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <span id="hidden_fields">
                <input type="hidden" name="display_type" value="list" />
            </span>
            <div class="row">
                <div style="text-align:right" class='col-md-4'>
                    <input type="text" name="cl_date" class="form-control" id="CL_DATE" value="{{ date('Y-m-d') }}" />
                </div>
                <div style="text-align:right" class='col-md-4'></div>
                <div style="text-align:right" class='col-md-4'></div>
            </div>
            <div class="row">
                <div class="col-md-12" style="height:10px">&nbsp;</div>
            </div>
            <div class="row">
                <div style="text-align:right" class='col-md-12'>
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="height:10px">&nbsp;</div>
            </div>
            <div class="row">
                <div style="text-align:center" id="LstLeads" class='col-md-12'>
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
                </div>
            </div>
        </div>
    </div>

@endsection
