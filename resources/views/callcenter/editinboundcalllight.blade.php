<?php
/***********************************************************
editinboundcall.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 18, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :

***********************************************************/

?>

@extends('layouts.layout', ['page_title' => "Calls Management"])

@section('themes')
    <style>
        body {
            background-color: #f6f8fb;
        }

        /* ===== Card ===== */
        .card {
            border: 0;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 12px 30px rgba(0,0,0,.06);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #eef1f6;
            padding: 1.5rem 1.75rem;
        }

        .card-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: #111827;
        }

        /* ===== Section Titles ===== */
        .card-section-title {
            font-size: .9rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 1.25rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            border: 0;
            padding: 0;
        }

        /* ===== Inputs ===== */
        .form-label {
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #6b7280;
            margin-bottom: .35rem;
        }

        .form-control,
        .form-select {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: .85rem;
            padding: .6rem .75rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 .15rem rgba(99,102,241,.15);
            background: #ffffff;
        }

        textarea.form-control {
            min-height: 110px;
        }

        /* ===== Section Container ===== */
        .form-section {
            background: #ffffff;
            border-radius: 14px;
            padding: 1.5rem;
            border: 1px solid #eef1f6;
            margin-bottom: 2rem;
        }

        /* ===== Switch Cards ===== */
        .switch-card {
            background: #f9fafb;
            border: 1px dashed #dbeafe;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* ===== Buttons ===== */
        .btn {
            border-radius: 12px;
            font-weight: 500;
            padding: .6rem 1.25rem;
        }

        .btn-primary {
            background: linear-gradient(135deg,#4f46e5,#6366f1);
            border: none;
        }

        .btn-light {
            background: #f3f4f6;
            border: 0;
        }

        /* ===== Alerts ===== */
        .alert {
            border-radius: 14px;
            font-size: .85rem;
        }
    </style>
@endsection

@section('plugins')
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/inboundcalls.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/callcenter/saveinboundcall.js') }}"></script>
@endsection

@section('content')
    <div class="card card-flush shadow-sm">
        <div class="card-header">
            <div class="d-flex align-items-center gap-3">
                <i class="ki-duotone ki-pencil fs-2 text-primary">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <h3 class="card-title mb-0">Edit Appointment</h3>

                <span class="badge bg-light-primary text-primary ms-2">
            #{{ $inboundcall_info->ic_id }}
        </span>
            </div>
        </div>

        <div class="card-body pt-0">
            <form name="frm_save_inbound" id="FORM_SAVE_INBOUND" class="form">
            <span id="hidden_fields">
                @csrf
                <input type="hidden" name="ic_id" value="{{ $inboundcall_info->ic_id }}" />
            </span>

                <div class="alert alert-dismissible bg-light-success border border-success border-dashed flex-column flex-sm-row w-100 p-5 mb-10" style="display:none">
                    <i class="ki-duotone ki-check-circle fs-2hx text-success me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <h5 class="mb-1">Success!</h5>
                        <span>Call information updated successfully.</span>
                    </div>
                </div>

                <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed flex-column flex-sm-row w-100 p-5 mb-10" style="display:none">
                    <i class="ki-duotone ki-cross-circle fs-2hx text-danger me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <h5 class="mb-1">Error!</h5>
                        <span>You have some form errors. Please check the fields below.</span>
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="card-section-title">Client Information</h4>
                    <div class="row g-5">
                        <div class="col-md-3">
                            <label class="form-label">Client</label>
                            <select name="ic_client_id" id="IC_CLIENT_ID" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Client">
                                <option value="">-- Select Client --</option>
                                @foreach ($lst_clients as $client_info)
                                    <option {{ $inboundcall_info->fk_customer_id == $client_info->ca_id ? "selected" : "" }} value="{{ $client_info->ca_id }}">{{ $client_info->ca_account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Client Code</label>
                            <div class="input-group input-group-solid">
                                <span class="input-group-text"><i class="ki-duotone ki-barcode fs-3"><span class="path1"></span><span class="path2"></span></i></span>
                                <input type="text" name="ic_client_code" id="IC_CLIENT_CODE" class="form-control form-control-solid" required="required" maxlength="15" value="{{ $inboundcall_info->ic_client_code }}" />
                            </div>
                            <input type="hidden" name="fk_customer_id" id="FK_CUSTOMER_ID" value="{{ $inboundcall_info->fk_customer_id }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Client Name</label>
                            <input type="text" name="ca_account_name" id="CA_ACCOUNT_NAME" class="form-control form-control-solid bg-light" required="required" readonly="readonly" maxlength="255" value="" />
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Client Address</label>
                            <input type="text" name="ca_account_address" id="CA_ACCOUNT_ADDRESS" class="form-control form-control-solid bg-light" required="required" readonly="readonly" maxlength="255" value="" />
                        </div>
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="card-section-title">Assignment & Details</h4>
                    <div class="row g-5">
                        <div class="col-md-3">
                            <label class="form-label">Appointment Title</label>
                            <input type="text" name="ic_call_subject" id="IC_CALL_SUBJECT" class="form-control form-control-solid" value="{{ $inboundcall_info->ic_call_subject }}" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Call Date</label>
                            <input type="text" name="ic_call_date" required="required" id="IC_CALL_DATE" class="form-control form-control-solid" value="{{ $inboundcall_info->ic_call_date }}" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Call Time</label>
                            <input type="text" name="ic_call_start_time" required="required" id="IC_CALL_START_TIME" class="form-control form-control-solid" value="{{ $inboundcall_info->ic_call_start_time }}" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Technician</label>
                            <select name="ic_technician_id" id="IC_TECHNICIAN_ID" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Technician">
                                <option value="">-- Select Technician --</option>
                                @foreach ($lst_technicians as $user_info)
                                    <option {{ $inboundcall_info->ic_technician_id == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                                @foreach ($lst_admins as $user_info)
                                    <option {{ $inboundcall_info->ic_technician_id == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <br />
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="ic_closed_voucher" id="IC_CLOSED_VOUCHER" value="1" />
                                    <span class="form-check-label fw-semibold text-muted">
                                      Close Appointment
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Next Appointment</label>
                                <input type="number" min="0" max="12" step="1" name="ic_next_appt" required="required" id="IC_NEXT_APPT" class="form-control form-control-solid" value="1" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <br />
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="ic_issue_resolved" id="IC_ISSUE_RESOLVED" value="1" {{ $inboundcall_info->ic_issue_resolved == 1 ? "checked" : "" }} />
                                    <span class="form-check-label fw-semibold text-muted">
                                      Issue Resolved
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Appointment Price</label>
                            <input type="text" name="ic_visit_price" id="IC_VISIT_PRICE" class="form-control form-control-solid" value="{{ $inboundcall_info->ic_visit_price }}" />
                        </div>
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="card-section-title">Problem Description & Notes</h4>
                    <div class="row g-5">
                        <div class="col-md-12">
                            <label class="form-label">Problem Description</label>
                            <textarea id="IC_CALL_OUTCOME" name="ic_call_outcome" class="form-control form-control-solid" placeholder="Describe the issue reported by client...">{{ $inboundcall_info->ic_call_outcome }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Internal Notes</label>
                            <textarea id="IC_NOTES" name="ic_notes" class="form-control form-control-solid" placeholder="Internal notes for technicians...">{{ $inboundcall_info->ic_notes }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Result / Diagnosis</label>
                            <textarea id="IC_ITEM_PROBLEM" name="ic_item_problem" class="form-control form-control-solid" placeholder="Final result or initial diagnosis...">{{ $inboundcall_info->ic_item_problem }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="separator mb-8"></div>

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <button type="button" id="BACK_FORM" class="btn btn-light">
                        ← Back
                    </button>

                    <button type="submit" id="BTN_SAVE_CALL" class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
