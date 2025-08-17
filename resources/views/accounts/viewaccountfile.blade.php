<?php
/***********************************************************
products.blade.php
Product :
Version : 1.0
Release : 2
Date Created :Oct 7, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
 ***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Products Management"])

@section('themes')
    <style>
        th{
            cursor: pointer;
        }
        #ModelPopUp{
            width:800px;
        }
    </style>
@endsection
@section('plugins')
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/clients.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/crm/viewclient.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">View Account File - {{ $client_info->ca_account_name }}&nbsp;(&nbsp;{{ $client_info->ca_account_code }}&nbsp;)&nbsp;</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Client Info</h3>
                    <div class="card-toolbar"></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Client Code </label>
                                <span class="text-success">{{ $client_info->ca_account_code }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Client Name </label>
                                <span class="text-success">{{ $client_info->ca_account_name }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mobile</label>
                                <span class="text-success">{{ $client_info->ca_account_mobile }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email</label>
                                <span class="text-success">{{ $client_info->ca_account_email }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Area</label>
                                <span class="text-success">{{ $client_info->ca_billing_area }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Area</label>
                                <span class="text-success">{{ $client_info->ca_billing_region }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width: 100%;height: 50px" class="col-md-12">&nbsp;</div>
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_not_paid">Bills Not Paid</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab_paid">Bills Paid</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab_pending_calls">Pending Calls</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab_closed_calls">Closed Calls</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab_not_paid" role="tabpanel">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                <th style="width:2px;white-space: nowrap;" title="#">#</th>
                                <th title="Voucher Ref"> Ref </th>
                                <th title="Bill Nbr"> Bill Nbr </th>
                                <th title="Voucher Date"> Date </th>
                                <th title="Client Code"> Client Code </th>
                                <th title="Client Name"> Client Name </th>
                                <th title="Total Price"> Bill Amount </th>
                            </tr>
                            </thead>
                            <tbody id="LstUBills">
                            @foreach($lst_bills_unpaid as $index => $bill_info)
                                <tr  class="odd gradeX" data-ip_id="{{ $bill_info->ip_id }}">
                                    <td><input type="checkbox" name="ip_checkbox_{{ $bill_info->ip_id }}" id="IP_CHECKBOX_{{ $bill_info->ip_id }}" class="checkboxes" value="{{ $bill_info->ip_id }}" /></td>
                                    <td>{{ $bill_info->ip_payment_doc }}</td>
                                    <td>{{ $bill_info->ip_billing_nbr }}</td>
                                    <td>{{ $bill_info->ip_billing_date }}</td>
                                    <td>{{ $bill_info->Client ? $bill_info->Client->ca_account_code : "-" }}</td>
                                    <td>{{ $bill_info->Client ? $bill_info->Client->ca_account_name : "-" }}</td>
                                    <td>{{ $bill_info->ip_payment_amount }}&nbsp;<b>{{ $bill_info->Currency ? $bill_info->Currency->cc_currency_code : "-" }}</b></td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_paid" role="tabpanel">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                <th style="width:2px;white-space: nowrap;" title="#">#</th>
                                <th title="Voucher Ref"> Ref </th>
                                <th title="Bill Nbr"> Bill Nbr </th>
                                <th title="Voucher Date"> Date </th>
                                <th title="Client Code"> Client Code </th>
                                <th title="Client Name"> Client Name </th>
                                <th title="Total Price"> Bill Amount</th>
                            </tr>
                            </thead>
                            <tbody id="LstPBills">
                            @foreach($lst_bills_paid as $index => $bill_info)
                                <tr  class="odd gradeX" data-ip_id="{{ $bill_info->ip_id }}">
                                    <td><input type="checkbox" name="ip_checkbox_{{ $bill_info->ip_id }}" id="IP_CHECKBOX_{{ $bill_info->ip_id }}" class="checkboxes" value="{{ $bill_info->ip_id }}" /></td>
                                    <td>{{ $bill_info->ip_payment_doc }}</td>
                                    <td>{{ $bill_info->ip_billing_nbr }}</td>
                                    <td>{{ $bill_info->ip_billing_date }}</td>
                                    <td>{{ $bill_info->Client ? $bill_info->Client->ca_account_code : "-" }}</td>
                                    <td>{{ $bill_info->Client ? $bill_info->Client->ca_account_name : "-" }}</td>
                                    <td>{{ $bill_info->ip_payment_amount }}&nbsp;<b>{{ $bill_info->Currency ? $bill_info->Currency->cc_currency_code : "-" }}</b></td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_pending_calls" role="tabpanel">
                    <div class="table-responsive">
                        <table id="tablPendingCalls" class="table table-striped gy-7 gs-7">
                            <thead>
                            <tr
                                class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                <th style="width: 2px;">#</th>
                                <th style="width: 2px;">ID</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Client</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Result</th>
                            </tr>
                            </thead>
                            <tbody class="LstPendingCalls" id="LstPendingCalls">
                            @foreach($lst_pendingcalls as $index => $inboundcall_info)
                                <tr  class="odd gradeX" data-ic_id="{{ $inboundcall_info->ic_id }}">
                                    <td><input type="checkbox" name="ck_ic_{{ $inboundcall_info->ic_id }}" id="CK_IC_{{ $inboundcall_info->ic_id }}" class="checkboxes" value="{{ $inboundcall_info->ic_id }}" /></td>
                                    <td>{{ $inboundcall_info->ic_id }}</td>
                                    <td>{{ $inboundcall_info->ic_call_date }}</td>
                                    <td>{{ $inboundcall_info->ic_call_start_time }}</td>
                                    <td>{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_code : "-" }}&nbsp;{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_name : "-" }}</td>
                                    <td>{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_billing_address : "-" }}</td>
                                    <td>{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_mobile : "-" }}</td>
                                    <td>{{ $inboundcall_info->CallResult ? $inboundcall_info->CallResult->cr_result_title : "-" }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_closed_calls" role="tabpanel">
                    <div class="table-responsive">
                        <table id="tablPendingCalls" class="table table-striped gy-7 gs-7">
                            <thead>
                            <tr
                                class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                <th style="width: 2px;">#</th>
                                <th style="width: 2px;">ID</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Client</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Result</th>
                            </tr>
                            </thead>
                            <tbody class="LstClosedCalls" id="LstClosedCalls">
                            @foreach($lst_closedcalls as $index => $inboundcall_info)
                                <tr  class="odd gradeX" data-ic_id="{{ $inboundcall_info->ic_id }}">
                                    <td><input type="checkbox" name="ck_ic_{{ $inboundcall_info->ic_id }}" id="CK_IC_{{ $inboundcall_info->ic_id }}" class="checkboxes" value="{{ $inboundcall_info->ic_id }}" /></td>
                                    <td>{{ $inboundcall_info->ic_id }}</td>
                                    <td>{{ $inboundcall_info->ic_call_date }}</td>
                                    <td>{{ $inboundcall_info->ic_call_start_time }}</td>
                                    <td>{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_code : "-" }}&nbsp;{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_name : "-" }}</td>
                                    <td>{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_billing_address : "-" }}</td>
                                    <td>{{ $inboundcall_info->Client ? $inboundcall_info->Client->ca_account_mobile : "-" }}</td>
                                    <td>{{ $inboundcall_info->CallResult ? $inboundcall_info->CallResult->cr_result_title : "-" }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
