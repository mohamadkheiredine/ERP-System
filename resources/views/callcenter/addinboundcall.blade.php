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
                <i class="ki-duotone ki-call fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                Add New Inbound Call
            </h3>
            <div class="card-toolbar">
            </div>
        </div>

        <div class="card-body pt-0">
            <form name="frm_save_inbound" id="FORM_SAVE_INBOUND" class="form">
            <span id="hidden_fields">
                @csrf
            </span>

                <div  class="alert alert-dismissible bg-light-success border border-success border-dashed flex-column flex-sm-row w-100 p-5 mb-10" style="display:none">
                    <i class="ki-duotone ki-check-circle fs-2hx text-success me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <h5 class="mb-1">Success!</h5>
                        <span>Call information saved successfully.</span>
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
                                <input type="text" name="ic_client_code" id="IC_CLIENT_CODE" class="form-control form-control-solid" required="required" maxlength="15" placeholder="Enter Code" />
                            </div>
                            <input type="hidden" name="fk_customer_id" id="FK_CUSTOMER_ID" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Contract Code</label>
                            <input type="text" name="ic_contract_code" id="IC_CONTRACT_CODE" class="form-control form-control-solid" maxlength="15" placeholder="Optional" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Client Name</label>
                            <input type="text" name="ca_account_name" id="CA_ACCOUNT_NAME" class="form-control form-control-solid bg-light" readonly="readonly" required="required" maxlength="255" />
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Client Address</label>
                            <input type="text" name="ca_account_address" id="CA_ACCOUNT_ADDRESS" class="form-control form-control-solid bg-light" readonly="readonly" maxlength="255" />
                        </div>
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="card-section-title">Assignment & Details</h4>
                    <div class="row g-5">
                        <div class="col-md-3">
                            <label class="form-label">Call Date</label>
                            <input type="text" name="ic_call_date" id="IC_CALL_DATE" class="form-control form-control-solid" value="{{ date('Y-m-d') }}" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Call Time</label>
                            <input type="text" name="ic_call_start_time" id="IC_CALL_START_TIME" class="form-control form-control-solid" value="{{ date('H:i:s') }}" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Technician</label>
                            <select name="ic_technician_id" id="IC_TECHNICIAN_ID" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Technician">
                                <option value="">-- Select Technician --</option>
                                @foreach ($lst_technicians as $user_info)
                                    <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                                @foreach ($lst_admins as $user_info)
                                    <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Maintenance Type</label>
                            <select name="ic_maintenance_type" required id="IC_MAINTENANCE_ID" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Type">
                                <option value="">-- Select Telemarketing --</option>
                                @foreach ($lst_maint_types as $type_info)
                                    <option value="{{ $type_info->mt_id }}">{{ $type_info->mt_type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4" style="display:none">
                            <label>Result</label>
                            <select name="ic_result_id" id="IC_RESULT_ID" class="form-select" data-control="select2">
                                <option value="">-- Select Result --</option>
                                @foreach ($lst_results as $result_info)
                                    <option value="{{ $result_info->cr_id }}">{{ $result_info->cr_result_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" style="display:none">
                            <label>Salesman</label>
                            <select name="ic_sales_id" required="required" id="IC_SALES_ID" class="form-select" data-control="select2">
                                <option value="">-- Select Salesman --</option>
                                @foreach ($lst_sales as $user_info)
                                    <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                                @foreach ($lst_admins as $user_info)
                                    <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" style="display:none">
                            <label>Product</label>
                            <select name="ic_customer_product" id="IC_CUSTOMER_PRODUCT" class="form-select" data-control="select2">
                                <option value=""> -- Select Product -- </option>
                                @foreach($lst_products as $product_info)
                                    <option value="{{ $product_info->p_id }}" data-ref_id="{{ $product_info->p_product_ref }}">{{ $product_info->p_product_ref }}&nbsp;-&nbsp;{{ $product_info->p_product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="card-section-title">Product & Warranty Status</h4>
                    <div class="row g-5 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Product Serial Number</label>
                            <input type="text" name="ic_product_machine_id" id="IC_PRODUCT_MACHINE_ID" class="form-control form-control-solid" maxlength="45" placeholder="S/N" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Warranty Expiry</label>
                            <div class="input-group input-group-solid">
                                <span class="input-group-text"><i class="ki-duotone ki-calendar fs-3"><span class="path1"></span><span class="path2"></span></i></span>
                                <input type="text" name="ic_warranty_expiry" id="IC_WARRANTY_EXPIRY" class="form-control form-control-solid" value="{{ date('Y-m-d', strtotime('+10 years')) }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bill Situation</label>
                            <input type="text" name="ic_bill_situation" id="IC_BILL_SITUATION" class="form-control form-control-solid" maxlength="45" />
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mt-3 bg-light-primary rounded p-3 border border-primary border-dashed">
                                <div class="form-check form-switch form-check-custom form-check-solid me-5">
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="ic_under_warranty" id="IC_UNDER_WARRANTY" checked="checked" value="1" />
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
                                    <input class="form-check-input h-20px w-30px" type="checkbox" name="ic_issue_resolved" id="IC_ISSUE_RESOLVED" value="1" />
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
                            <textarea id="IC_CALL_OUTCOME" name="ic_call_outcome" class="form-control form-control-solid" placeholder="Describe the issue reported by client..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Internal Notes</label>
                            <textarea id="IC_NOTES" name="ic_notes" class="form-control form-control-solid" placeholder="Internal notes for technicians..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Result / Diagnosis</label>
                            <textarea id="IC_ITEM_PROBLEM" name="ic_item_problem" class="form-control form-control-solid" placeholder="Final result or initial diagnosis..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="separator mb-8"></div>

                <div class="d-flex justify-content-end">
                    <button type="button" id="BACK_FORM" name="back_form" class="btn btn-light me-3">
                        <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i> Back
                    </button>
                    <button type="submit" name="btn_save_call" id="BTN_SAVE_CALL" class="btn btn-primary">
                        <i class="ki-duotone ki-save-2 fs-2"><span class="path1"></span><span class="path2"></span></i> Save Call
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
