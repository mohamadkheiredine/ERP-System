<?php
/***********************************************************
 * inboundlite.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 12/29/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>
@extends('layouts.layout', ['page_title' => "Maintenance Appointments Management"])

@section('themes')
    <style>
        /* ===== Base ===== */
        body {
            background-color: #f5f7fb;
        }

        /* ===== Card ===== */
        .card {
            border: 0;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,.05);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #eef1f6;
            padding: 1.25rem 1.5rem;
        }

        .card-title h2 {
            font-size: 1.15rem;
            font-weight: 600;
        }

        /* ===== Search ===== */
        .search-input {
            background: #f1f3f7;
            border-radius: 999px;
            padding-left: 42px;
            border: none;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        /* ===== Filter Panel ===== */
        .filter-panel {
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.9),
                rgba(255,255,255,0.95)
            );
            backdrop-filter: blur(6px);
            border-radius: 14px;
            border: 1px solid #eef1f6;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .filter-panel .form-control,
        .filter-panel .form-select {
            background: #f8f9fc;
            border: 1px solid #e4e7ee;
            border-radius: 10px;
            font-size: .85rem;
        }

        .form-label {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #6c757d;
            font-weight: 600;
        }

        /* ===== Table ===== */
        .table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead th {
            border: 0;
            font-size: .7rem;
            text-transform: uppercase;
            color: #6c757d;
            background: transparent;
        }

        .table tbody tr {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,.04);
            border-radius: 12px;
        }

        .table tbody td {
            border: 0;
            padding: .85rem;
            font-size: .85rem;
            vertical-align: middle;
        }

        .table tbody tr td:first-child {
            border-radius: 12px 0 0 12px;
        }

        .table tbody tr td:last-child {
            border-radius: 0 12px 12px 0;
        }

        /* ===== Buttons ===== */
        .btn {
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none;
        }

        /* ===== Pagination ===== */
        .pagination .page-link {
            border-radius: 10px;
            margin: 0 4px;
            border: none;
            background: #f1f3f7;
            color: #495057;
        }

        .pagination .active .page-link {
            background: #4f46e5;
            color: #fff;
        }
    </style>
    <link href="{{ url('default/assets/plugins/tablesorter/dist/css/theme.default.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('plugins')
    <script type="text/javascript" src="{{ url('default/assets/plugins/tablesorter/dist/js/jquery.tablesorter.js') }}"></script>
    <script type="text/javascript" src="{{ url('default/assets/plugins/tablesorter/dist/js/jquery.tablesorter.widgets.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/leads.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/inboundcalls.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/callcenter/inboundcalls.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            // Initialize Select2 for modal elements
            const select2Config = { dropdownParent: $('#AddMainVoucher'), allowClear: true };
            $('#CP_PRODUCT_NAME, #CP_PRODUCT_ID').select2({...select2Config, placeholder: 'Select used items'});
            $('#IC_CURRENCY_ID').select2({...select2Config, placeholder: 'Select Currency'});
            $('#IC_TECH_ID').select2({...select2Config, placeholder: 'Select Technician'});
        });
    </script>
@endsection

@section('content')
    <div class="card card-flush shadow-sm mb-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title d-flex align-items-center position-relative">
                <i class="ki-duotone ki-magnifier search-icon"></i>
                <input
                    type="text"
                    id="generalSearch"
                    class="form-control search-input w-300px"
                    placeholder="Search appointments..."
                />
            </div>

            <div class="d-flex gap-3">
                <a href="{{ url('/callcenter/inboundcall/addform') }}"
                   class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-4 me-1"></i>
                    New Appointment
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
        <span id="hidden_fields">
            <input type="hidden" name="page_number" value="1" />
        </span>

            <div class="filter-panel">
                <div class="row g-4 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Technician</label>
                        <select id="IC_TECHNICIAN_ID" name="ic_technician_id" class="form-select form-select-sm">
                            <option value="0">All</option>
                            @foreach ($lst_technicians as $u)
                                <option value="{{ $u->id }}">{{ $u->u_fullname }}</option>
                            @endforeach
                            @foreach ($lst_admins as $u)
                                <option value="{{ $u->id }}">{{ $u->u_fullname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input
                            type="text"
                            id="IC_CALL_DATE"
                            name="ic_call_date"
                            class="form-control form-control-sm"
                            placeholder="Select date">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select id="IC_ARCHIVED_CALL" name="ic_archived_call" class="form-select form-select-sm">
                            <option value="0">Pending</option>
                            <option value="1">Archived</option>
                        </select>
                    </div>

                    <div class="col-md-3 text-end">
                        <button class="btn btn-outline-secondary btn-sm">
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="tablPendingCalls" class="table align-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Client</th>
                        <th>Assigned</th>
                        <th>Phone</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="LstInboundCalls" class="LstInboundCalls"></tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap mt-5">
                <div class="d-flex flex-wrap py-2 mr-3">
                    <ul id="InboundCallsPagination" class="pagination pagination-circle pagination-outline"></ul>
                </div>
            </div>

        </div>
    </div>
@endsection

