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
        /* Custom spacing and overrides */
        .form-label {
            font-weight: 600;
            color: #3F4254;
            margin-bottom: 0.5rem;
        }
        .card-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #181c32;
            margin-bottom: 1.5rem;
            border-bottom: 1px dashed #e4e6ef;
            padding-bottom: 0.5rem;
        }
        textarea.form-control {
            min-height: 120px;
        }
        /* CKEditor Fix if needed */
        .ck-editor__editable {
            min-height: 200px !important;
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
        <div class="card-header mt-4">
            <h3 class="card-title">
                <i class="ki-duotone ki-pencil fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                Edit Call Details
            </h3>
            <div class="card-toolbar">
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
                            <label class="form-label required">Client Code</label>
                            <div class="input-group input-group-solid">
                                <span class="input-group-text"><i class="ki-duotone ki-barcode fs-3"><span class="path1"></span><span class="path2"></span></i></span>
                                <input type="text" name="ic_client_code" id="IC_CLIENT_CODE" class="form-control form-control-solid" required="required" maxlength="15" value="{{ $inboundcall_info->ic_client_code }}" />
                            </div>
                            <input type="hidden" name="fk_customer_id" id="FK_CUSTOMER_ID" value="{{ $inboundcall_info->fk_customer_id }}" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Contract Code</label>
                            <input type="text" name="ic_contract_code" id="IC_CONTRACT_CODE" class="form-control form-control-solid" required="required" maxlength="15" value="{{ $inboundcall_info->ic_contract_code }}" />
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
                        <div class="col-md-3">
                            <label class="form-label required">Maintenance Type</label>
                            <select name="ic_maintenance_type" id="IC_MAINTENANCE_ID" required class="form-select form-select-solid" data-control="select2" data-placeholder="Select Maintenance Type">
                                <option value="">-- Select Telemarketing --</option>
                                @foreach ($lst_maint_types as $type_info)
                                    <option {{ ($type_info->mt_id == $inboundcall_info->ic_maintenance_type) ? 'selected' : '' }} value="{{ $type_info->mt_id }}">{{ $type_info->mt_type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4" style="display:none">
                            <label>Result</label>
                            <select name="ic_result_id" id="IC_RESULT_ID" class="form-select" data-control="select2">
                                <option value="">-- Select Result --</option>
                                @foreach ($lst_results as $result_info)
                                    <option {{ $inboundcall_info->ic_result_id == $result_info->cr_id ? "selected" : "" }} value="{{ $result_info->cr_id }}">{{ $result_info->cr_result_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" style="display:none">
                            <label>Salesman</label>
                            <select name="ic_sales_id" id="IC_SALES_ID" class="form-select" data-control="select2">
                                <option value="">-- Select Salesman --</option>
                                @foreach ($lst_sales as $user_info)
                                    <option {{ $inboundcall_info->ic_sales_id == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                                @foreach ($lst_admins as $user_info)
                                    <option {{ $inboundcall_info->ic_sales_id == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" style="display:none">
                            <label>Product</label>
                            <select name="ic_customer_product" id="IC_CUSTOMER_PRODUCT" class="form-select" data-control="select2">
                                <option value=""> -- Select Product -- </option>
                                @foreach($lst_products as $product_info)
                                    <option {{ $inboundcall_info->ic_customer_product == $product_info->p_id ? "selected" : "" }} data-ref_id="{{ $product_info->p_product_ref }}" value="{{ $product_info->p_id }}">{{ $product_info->p_product_ref }}&nbsp;-&nbsp;{{ $product_info->p_product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="card-section-title">Product & Warranty Status</h4>
                    <div class="row g-5 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label required">Product Serial Number</label>
                            <input type="text" name="ic_product_machine_id" id="IC_PRODUCT_MACHINE_ID" class="form-control form-control-solid" maxlength="45" value="{{ $inboundcall_info->ic_product_machine_id }}" required="required" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Warranty Expiry</label>
                            <div class="input-group input-group-solid">
                                <span class="input-group-text"><i class="ki-duotone ki-calendar fs-3"><span class="path1"></span><span class="path2"></span></i></span>
                                <input type="text" name="ic_warranty_expiry" id="IC_WARRANTY_EXPIRY" class="form-control form-control-solid" value="{{ $inboundcall_info->ic_warranty_expiry }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bill Situation</label>
                            <input type="text" name="ic_bill_situation" id="IC_BILL_SITUATION" class="form-control form-control-solid" maxlength="45" value="{{ $inboundcall_info->ic_bill_situation }}" />
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mt-3 bg-light-primary rounded p-3 border border-primary border-dashed">
                                <div class="form-check form-switch form-check-custom form-check-solid me-5">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="ic_under_warranty" id="IC_UNDER_WARRANTY" {{ $inboundcall_info->ic_under_warranty == 1 ? "checked" : "" }} value="1" />
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-bold text-gray-800 d-block fs-6">Under Warranty</span>
                                    <span class="text-muted fw-semibold fs-7">Is the item covered?</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mt-3 bg-light-success rounded p-3 border border-success border-dashed">
                                <div class="form-check form-switch form-check-custom form-check-solid me-5">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="ic_issue_resolved" id="IC_ISSUE_RESOLVED" {{ $inboundcall_info->ic_issue_resolved == 1 ? "checked" : "" }} value="1" />
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-bold text-gray-800 d-block fs-6">Issue Resolved</span>
                                    <span class="text-muted fw-semibold fs-7">Close ticket immediately?</span>
                                </div>
                            </div>
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

                <div class="d-flex justify-content-end">
                    <button type="button" id="BACK_FORM" name="back_form" class="btn btn-light me-3">
                        <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i> Back
                    </button>
                    <button type="submit" name="btn_save_call" id="BTN_SAVE_CALL" class="btn btn-primary">
                        <i class="ki-duotone ki-save-2 fs-2"><span class="path1"></span><span class="path2"></span></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
