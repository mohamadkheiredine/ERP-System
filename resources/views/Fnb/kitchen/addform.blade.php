@extends('layouts.layout', ['page_title' => "FNB Kitchen Management"])

@section('themes')
    <style>
        th {
            cursor: pointer;
        }

        #ModelPopUp {
            width: 800px;
        }

        /* Optional: make inputs consistent full width */
        .form-group {
            margin-bottom: 1.5rem;
        }
    </style>
@endsection

@section('plugins')
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/fnb-kitchen.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/fnb/kitchen/saveKitchen.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add New Kitchen</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu"></ul>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form name="frm_save_kitchen" id="FORM_SAVE_KITCHEN">
                <div class="form-body">
                    <span id="hidden_fields">
                        {!! csrf_field() !!}
                    </span>

                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Kitchen Information is saved successfully!
                    </div>

                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Kitchen Name <span class="required"></span></label>
                                <input type="text" name="ks_name" id="KS_KITCHEN_NAME" class="form-control" required
                                    maxlength="255" value="" />
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <textarea name="ks_description" id="KS_DESCRIPTION" class="form-control" rows="3"
                                    placeholder="Enter kitchen description"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <br />
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="ks_active" id="KS_ACTIVE"
                                        value="1" />
                                    <span class="form-check-label fw-semibold text-muted">
                                        Kitchen Active
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Warehouse <span class="required">*</span></label>
                                        <select  name="ks_warehouse_id" id="KS_WAREHOUSE_ID" class="form-control form-select" required data-control="select2" data-placeholder="Select Warehouse">
                                        <option value="">-- Select Warehouse --</option>
                                        @foreach($lst_warehouses as $warehouse)
                                            <option value="{{ $warehouse->w_id }}">{{ $warehouse->w_warehouse_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Printer IP</label>
                                    <input type="text" name="printer_ip" id="PRINTER_IP" class="form-control"
                                        placeholder="192.168.1.100" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Printer Port</label>
                                    <input type="number" name="printer_port" id="PRINTER_PORT" class="form-control"
                                        placeholder="9100" />
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12 col-xs-12">
                            {{-- Buttons --}}
                            <div class="d-flex justify-content-end">
                                <button type="submit" name="btn_save_kitchen" id="BTN_SAVE_KITCHEN"
                                    class="btn btn-info me-2">Save</button>
                                <button type="button" id="BACK_FORM" name="back_form"
                                    class="btn btn-secondary">Back</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
