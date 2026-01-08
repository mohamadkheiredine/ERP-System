@extends('layouts.layout', ['page_title' => "Pending Calls Management"])

@section('themes')
    <style>
        /* Modern Tweaks */
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #6c757d;
            background-color: #f9f9f9;
            cursor: pointer;
        }
        .table tbody td {
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .form-label {
            font-weight: 500;
            color: #3F4254;
        }
        .client-info-box {
            background: #f1faff;
            border: 1px dashed #009ef7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .client-info-label {
            font-size: 0.8rem;
            color: #7e8299;
            display: block;
        }
        .client-info-value {
            font-weight: 600;
            color: #181c32;
            font-size: 1rem;
        }
        /* Modal Sizing */
        #ModelPopUp, .modal-lg-custom {
            max-width: 900px;
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
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-12" name="general_search" id="generalSearch" placeholder="Search Calls..." />
                </div>
            </div>
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <div class="btn-group">
                    <button type="button" class="btn btn-light-primary font-weight-bold dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ki-duotone ki-category fs-2"><span class="path1"></span><span class="path2"></span></i> Batch Actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" data-action_type="ADD_MAINTENANCE_VOUCHER" href="#">Add Maintenance Voucher</a></li>
                        <li><a class="dropdown-item" data-action_type="DOWNLOAD_PDF_REPORT" href="#">Download PDF Report</a></li>
                        <li><a class="dropdown-item" data-action_type="ADD_RESULT" href="#">Add Call Result</a></li>
                    </ul>
                </div>
                <a href="{{ url('/callcenter/inboundcall/addform') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i> New Call
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
        <span id="hidden_fields">
            <input type="hidden" name="page_number" value="1" />
        </span>

            <div class="bg-light-primary rounded border-primary border border-dashed p-6 mb-10">
                <h4 class="mb-4 text-primary fs-6 text-uppercase fw-bold">Advanced Filters</h4>
                <div class="row g-5">
                    <div class="col-md-3">
                        <label class="form-label fs-7">Technician</label>
                        <select name="ic_technician_id" id="IC_TECHNICIAN_ID" class="form-select form-select-solid form-select-sm" data-control="select2" data-placeholder="Filter by Technician">
                            <option value="0">All Technicians</option>
                            @foreach ($lst_technicians as $user_info)
                                <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                            @endforeach
                            @foreach ($lst_admins as $user_info)
                                <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-7">Maintenance Type</label>
                        <select name="ic_maintenance_type" id="IC_MAINTENANCE_ID" class="form-select form-select-solid form-select-sm" data-control="select2" data-placeholder="All Types">
                            <option value="0">All Types</option>
                            @foreach ($lst_maint_types as $type_info)
                                <option value="{{ $type_info->mt_id }}">{{ $type_info->mt_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-7">Date</label>
                        <input type="text" name="ic_call_date" id="IC_CALL_DATE" class="form-control form-control-solid form-control-sm" placeholder="Pick date" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-7">Status</label>
                        <select name="ic_archived_call" id="IC_ARCHIVED_CALL" class="form-select form-select-solid form-select-sm" data-control="select2">
                            <option value="0">Pending</option>
                            <option value="1">Archived</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-7">Result</label>
                        <select name="ic_result_id" id="IC_RESULT_ID" class="form-select form-select-solid form-select-sm" data-control="select2">
                            <option value="0">All Results</option>
                            @foreach ($lst_results as $res_info)
                                <option value="{{ $res_info->cr_id }}">{{ $res_info->cr_result_title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-7">Area <span class="text-danger">*</span></label>
                        <select name="cl_area" required id="CL_AREA" class="form-select form-select-solid form-select-sm" data-control="select2">
                            <option value="0">Select Area</option>
                            @foreach ($lst_areas as $area_info)
                                <option value="{{ $area_info->la_area }}">{{ $area_info->la_area }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-7">Region</label>
                        <div id="REGION_DROPDOWN">
                            <select name="cl_region" required id="CL_REGION" class="form-select form-select-solid form-select-sm" data-control="select2">
                                <option value="0">Select Region</option>
                                @foreach($lst_regions as $region_info)
                                    <option value="{{ $region_info->lr_region }}">{{ $region_info->lr_region }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="tablPendingCalls" class="table table-hover table-rounded table-striped border gy-5 gs-5">
                    <thead class="bg-light">
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                        <th class="min-w-50px">#</th>
                        <th class="min-w-100px">Date</th>
                        <th>Time</th>
                        <th>Client</th>
                        <th>Region</th>
                        <th>Area</th>
                        <th>Address</th>
                        <th>Salesman</th>
                        <th>Assign To</th>
                        <th>Phone</th>
                        <th>Result</th>
                        <th>Call Result</th>
                        <th class="text-end min-w-100px">Actions</th>
                        <th style="display:none">Delete</th> </tr>
                    </thead>
                    <tbody class="LstInboundCalls text-gray-600 fw-semibold" id="LstInboundCalls">
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap mt-5">
                <div class="d-flex flex-wrap py-2 mr-3">
                    <ul id="InboundCallsPagination" class="pagination pagination-circle pagination-outline"></ul>
                </div>
            </div>

            <div class="separator my-10"></div>

            <h5 class="card-title mb-5">History Log</h5>
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table id="TableMainCallResults" class="table table-row-bordered align-middle gy-4 gs-9">
                    <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light">
                    <tr>
                        <th class="min-w-150px">Date</th>
                        <th class="min-w-250px">Note</th>
                        <th class="min-w-100px">Result</th>
                        <th class="min-w-150px">Assigned To</th>
                    </tr>
                    </thead>
                    <tbody class="LstMainCallResult" id="LstMainCallResult"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="CallResultManagement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Manage Call Results</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form name="frm_save_results" id="FRM_SAVE_RESULTS" class="form">
                        @csrf
                        <input type="hidden" name="ic_call_ids" id="IC_CALL_IDS" value="0" />
                        <input type="hidden" name="cw_id" id="CW_ID" value="0" />

                        <div class="row g-5 mb-5 mt-3">
                            <div class="col-md-6">
                                <label class="required form-label">Result Date</label>
                                <input type="text" name="cw_creation_date" id="CW_CREATION_DATE" class="form-control form-control-solid" value="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="required form-label">Outcome</label>
                                <select name="cw_result_id" required id="CW_RESULT_ID" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Result">
                                    <option value="">Select Result</option>
                                    @foreach ($lst_results as $result_info)
                                        <option value="{{ $result_info->cr_id }}">{{ $result_info->cr_result_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 CallBack" style="display:none">
                                <label class="required form-label">Callback Date</label>
                                <input type="text" name="cw_callback_date" id="CW_CALLBACK_DATE" class="form-control form-control-solid" value="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Assign Technician</label>
                                <select name="cw_assigned_to" id="CW_ASSIGNED_TO" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Technician">
                                    <option value="">Select Technician</option>
                                    @foreach ($lst_admins as $user_info)
                                        <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                    @endforeach
                                    @foreach ($lst_technicians as $user_info)
                                        <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Note</label>
                                <textarea name="cw_result_note" id="CW_RESULT_NOTE" maxlength="500" class="form-control form-control-solid" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="text-end mb-10">
                            <button type="submit" name="btn_save_result" id="BTN_SAVE_RESULT" class="btn btn-primary">
                                <i class="ki-duotone ki-check fs-2"></i> Save Changes
                            </button>
                        </div>

                        <h5 class="fw-bold mb-3">Previous Results</h5>
                        <div class="table-responsive border rounded p-3">
                            <table id="TableCallResults" class="table table-striped gy-3 gs-3">
                                <thead class="bg-light">
                                <tr class="fw-bold text-gray-800">
                                    <th>Date</th>
                                    <th>Note</th>
                                    <th>Result</th>
                                    <th>Assign To</th>
                                </tr>
                                </thead>
                                <tbody class="LstCallWResults" id="LstCallWResults"></tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AddMainVoucher" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Maintenance Voucher</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="client-info-box">
                        <div class="row text-center">
                            <div class="col-md-4 border-end">
                                <span class="client-info-label">Client Code</span>
                                <span class="client-info-value ClientCode">--</span>
                            </div>
                            <div class="col-md-4 border-end">
                                <span class="client-info-label">Client Name</span>
                                <span class="client-info-value ClientName">--</span>
                            </div>
                            <div class="col-md-4">
                                <span class="client-info-label">Mobile</span>
                                <span class="client-info-value ClientMobile">--</span>
                            </div>
                        </div>
                    </div>

                    <form name="frm_save_voucher" id="FRM_SAVE_VOUCHER">
                        @csrf
                        <input type="hidden" name="ic_ids" value="0" />
                        <input type="hidden" name="products_stock" value="" />

                        <div class="row g-5">
                            <div class="col-md-6">
                                <label class="required form-label">Date</label>
                                <input type="text" name="ic_resolution_date" required id="IC_RESOLUTION_DATE" class="form-control form-control-solid" />
                            </div>
                            <div class="col-md-6">
                                <label class="required form-label">MV Number</label>
                                <input type="text" name="ic_doc_number" required id="IC_DOC_NUMBER" maxlength="25" class="form-control form-control-solid" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Technician</label>
                                <select name="ic_tech_id" id="IC_TECH_ID" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Technician" style="width:100%">
                                    <option value="0">Select Technician</option>
                                    @foreach ($lst_technicians as $user_info)
                                        <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="required form-label">Maintenance Number</label>
                                <input type="text" name="ic_call_index" required id="IC_CALL_INDEX" maxlength="25" class="form-control form-control-solid" />
                            </div>
                            <div class="col-md-4">
                                <label class="required form-label">Commission</label>
                                <div class="input-group input-group-solid">
                                    <input type="text" name="ic_comission" required id="IC_COMISSION" class="form-control form-control-solid" value="0" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="required form-label">Visit Price</label>
                                <div class="input-group input-group-solid">
                                    <input type="text" name="ic_visit_price" required id="IC_VISIT_PRICE" class="form-control form-control-solid" value="0" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="required form-label">Currency</label>
                                <select name="ic_currency_id" required id="IC_CURRENCY_ID" class="form-select form-select-solid" data-control="select2" style="width:100%">
                                    <option value="">Select Currency</option>
                                    @foreach ($lst_currencies as $currency_info)
                                        <option {{ Session('company_currency') == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">
                                            {{ $currency_info->cc_currency_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 PaymentTypesDropdown" style="display:none">
                                <label class="form-label">Payment Type</label>
                                <select class="form-select form-select-solid" id="IC_PAYMENT_TYPE" name="ic_payment_type" data-control="select2">
                                    <option value="">Select Payment Type</option>
                                    @foreach($lst_payment_types as $paytype_info)
                                        <option value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch form-check-custom form-check-solid mt-6">
                                    <input class="form-check-input" type="checkbox" name="ic_is_paid" id="IC_IS_PAID" value="1" />
                                    <label class="form-check-label fw-bold text-gray-700" for="IC_IS_PAID">
                                        Voucher Paid
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="separator my-8"></div>

                        <h5 class="mb-4">Parts & Items</h5>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fs-8 text-muted">Barcode</label>
                                <select class="form-select form-select-solid" id="CP_PRODUCT_ID" name="cp_product_id" data-control="select2" style="width:100%">
                                    <option value="0">Select Code</option>
                                    @foreach($lst_products as $product_info)
                                        <option value="{{ $product_info->p_id }}">{{ $product_info->p_barcode }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-8 text-muted">Product Name</label>
                                <select name="cp_product_name" id="CP_PRODUCT_NAME" class="form-select form-select-solid" data-control="select2" style="width:100%">
                                    <option value="0">Select Product</option>
                                    @foreach($lst_products as $product_info)
                                        <option value="{{ $product_info->p_id }}">{{ $product_info->p_product_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fs-8 text-muted">Qty</label>
                                <input type="text" name="cp_quantity" class="form-control form-control-solid" value="1" />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fs-8 text-muted">Price</label>
                                <input type="text" name="cp_total_cost" class="form-control form-control-solid" value="1" />
                            </div>
                            <div class="col-md-2">
                                <button type="button" name="btn_add_stock" class="btn btn-info w-100">
                                    <i class="ki-duotone ki-plus fs-4"></i> Add
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive mt-5 border rounded">
                            <table class="table table-hover table-striped align-middle gy-3 gs-3">
                                <thead class="bg-light">
                                <tr class="fw-bold fs-7 text-gray-800 text-uppercase">
                                    <th>Code</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th class="text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody class="LstMaintenanceProducts">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="FRM_SAVE_VOUCHER" name="btn_save_mv" id="BTN_SAVE_MV" class="btn btn-primary">Save Voucher</button>
                </div>
            </div>
        </div>
    </div>
@endsection
