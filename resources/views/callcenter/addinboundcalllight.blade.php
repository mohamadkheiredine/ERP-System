@extends('layouts.layout', ['page_title' => "Maintenance Appointments Management"])

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
            border: 0;
            padding: 0;
            text-transform: uppercase;
            letter-spacing: .08em;
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
                <i class="ki-duotone ki-call fs-2 text-primary">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <h3 class="card-title mb-0">New Appointment</h3>
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
                        <span>Appointment information saved successfully.</span>
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

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <button type="button" id="BACK_FORM" class="btn btn-light">
                        ← Back
                    </button>

                    <button type="submit" id="BTN_SAVE_CALL" class="btn btn-primary">
                        Save Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
