<?php
/***********************************************************
addform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 9, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>
@extends('layouts.layout', ['page_title' => "Order Management"])

@section('themes')
    <style>
        th {
            cursor: pointer;
        }

        #ModelPopUp {
            width: 800px;
        }
    </style>
@endsection
@section('plugins')
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript"
        src="{{ url('default/assets/plugins/jquery-scanner-detection/jquery.scannerdetection.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/orders.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/orders/saveorder.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add New Order</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form name="frm_save_order" id="FORM_SAVE_ORDER">
                <div class="form-body">
                    <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="so_order_barcode" value="{{ $rand_barcode }}" />
                        <input type="hidden" name="so_barecode_img" value="{{ $bar_code_png }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Order Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <img id="BARCODE_IMG" src="data:image/png;base64,{{ $bar_code_png }}" alt="barcode"
                                    height="50" width="150" /><br />
                                <label class='lblbarcode'>{{ $rand_barcode }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Order Code:&nbsp;</label><br />
                                <input type="text" name="so_order_code" id="SO_ORDER_CODE" class="form-control"
                                    readonly="readonly" required="required" maxlength="25" tabindex="1"
                                    value="{{ $order_code }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Assign To:&nbsp;</label><br />
                                <select name="so_assign_to" id="SO_ASSIGN_TO" class="form-control form-select"
                                    data-control="select2" data-placeholder="Select User Assign">
                                    <option value="">No Parent</option>
                                    @foreach ($lst_users as $key => $user_info)
                                        <option {{  session('user_id') == $user_info->id ? "selected" : "" }}
                                            value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Order Status:&nbsp;</label><br />
                                <select name="so_order_status" id="SO_ORDER_STATUS" class="form-control form-select"
                                    data-control="select2" data-placeholder="Select Order Status">
                                    <option value="">-- Status --</option>
                                    @foreach ($lst_order_status as $key => $status_info)
                                        <option value="{{ $status_info->os_id }}">{{ $status_info->os_status_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Warehouse :&nbsp;</label><br />
                                <select name="fk_warehouse_id" id="FK_WAREHOUSE_ID" class="form-control form-select"
                                    data-control="select2" data-placeholder="Select Warehouse">
                                    <option value="">-- warehouse --</option>
                                    @foreach ($lst_warehouses as $key => $warehouse_info)
                                        <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Order Label:&nbsp;</label><br />
                                <input type="text" name="so_order_label" id="SO_ORDER_LABEL" class="form-control"
                                    required="required" maxlength="255" tabindex="5" value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Order Date:&nbsp;</label><br />
                                <input type="text" name="so_order_date" id="SO_ORDER_DATE" class="form-control"
                                    required="required" readonly="readonly" maxlength="10" value="{{ date('m/d/Y') }}"
                                    tabindex="6" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Delivery Date:&nbsp;</label><br />
                                <input type="text" name="so_delivery_date" id="SO_DELIVERY_DATE" class="form-control"
                                    required="required" readonly="readonly" maxlength="10" value="{{ date('m/d/Y') }}"
                                    tabindex="7" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Order Currency:&nbsp;</label><br />
                                <select name="so_order_currency" id="SO_ORDER_CURRENCY" required
                                    class="form-control form-select" data-control="select2"
                                    data-placeholder="Select Currency">
                                    <option value=""> -- Currency -- </option>
                                    @foreach ($lst_currency as $key => $curr_info)
                                        <option value="{{ $curr_info->cc_id }}">
                                            {{ $curr_info->cc_currency_code . "-" . $curr_info->cc_currency_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Order Tax:&nbsp;</label><br />
                                <select name="so_vat_id" id="SO_VAT_ID" class="form-control form-select"
                                    data-control="select2" data-placeholder="Select Tax">
                                    <option value="0"> -- Tax -- </option>
                                    @foreach ($lst_vat_tax as $key => $tax_info)
                                        <option value="{{ $tax_info->av_id }}">
                                            {{ $tax_info->av_vat_label }}&nbsp;(&nbsp;{{ $tax_info->av_vat_rate }}&nbsp;%&nbsp;)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Customer:&nbsp;</label><br />
                                <select name="so_order_customer" id="SO_ORDER_CUSTOMER" required
                                    class="form-control form-select" data-control="select2"
                                    data-placeholder="Select Customer">
                                    <option value=""> -- Customer -- </option>
                                    @foreach ($lst_customers as $key => $customer_info)
                                        <option {{ $customer_id == $customer_info->ic_id ? "selected" : ""  }}
                                            value="{{ $customer_info->ic_id }}">( {{ $customer_info->ic_customer_code }})
                                            &nbsp;-&nbsp;{{ $customer_info->ic_customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Product Type:&nbsp;</label><br />
                                <select name="so_product_type" id="SO_PRODUCT_TYPE" class="form-control form-select"
                                    data-control="select2" data-placeholder="Select Product Type">
                                    <option value=""> -- Product Type -- </option>
                                    <option value="1"> Products </option>
                                    <option value="2"> Services </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <br />
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="so_whole_sale" id="SO_WHOLE_SALE"
                                        value="1" />
                                    <span class="form-check-label fw-semibold text-muted">
                                        Whole Sales
                                    </span>
                                    <span class="WholeSaleSpan"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Vendor:&nbsp;</label><br />
                                <select name="so_vendor_id" id="SO_VENDOR_ID" class="form-control form-select"
                                    data-control="select2" disabled="disabled" data-placeholder="Select Vendor">
                                    <option value=""> -- Vendor -- </option>
                                    @foreach ($lst_vendors as $key => $vendor_info)
                                        <option value="{{ $vendor_info->iv_id }}">{{ $vendor_info->iv_vendor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Order Note:&nbsp;</label>
                                <textarea class="form-control" style="width:100%;height: 250px;" name="so_order_note"
                                    id="SO_ORDER_NOTE" tabindex="12"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_order" id="BTN_SAVE_ORDER"
                                class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
