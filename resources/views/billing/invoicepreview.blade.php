<?php
/***********************************************************
 * invoicepreview.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 1/20/2026
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2026
 *
 * Page Description :
 ***********************************************************/

?>



@extends('layouts.layout',['page_title' => "Billing Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/invoices.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/billing/returninvoices.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Return Invoice Preview</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu"> </ul>
                </div>
            </div>
        </div>
        <div class="card-body">

            <form name="frm_return_stock" id="FORM_RETURN_STOCK">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                         <input type="hidden" name="bi_id" value="{{ $invoices_info->bi_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Return Stock  saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped gy-7 gs-7">
                                <thead>
                                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                    <th title="Product"> Product </th>
                                    <th title="Quantity"> Quantity </th>
                                    <th title="Warehouse"> Warehouse </th>
                                    <th title="Stock Type"> Stock Type </th>
                                </tr>
                                </thead>
                                <tbody id="LstReturnProducts">
                                @foreach( $lst_invoice_items as $index => $item_info )
                                    <tr>
                                        <td>
                                            <b>{{ $item_info->Product->p_product_name }}</b>
                                            <input type="hidden" name="sp_product_id[]" value="{{ $item_info->ii_item_id }}" />
                                            <input type="hidden" name="sp_serial_number[]" value="{{ $item_info->ii_product_serial_number }}" />
                                        </td>
                                        <td>
                                            {{ $item_info->ii_item_qyt }}
                                            <input type="hidden" name="sp_quantity[]" value="{{ $item_info->ii_item_qyt }}" />
                                        </td>
                                        <td>
                                            <select   data-control="select2" data-placeholder="Select a warehouse" class="form-select" name="sp_warehouse_id[]" data-actions-box="true">
                                                <option value="">-- Select Warehouse --</option>
                                                @foreach ( $lst_warehouses as $key => $warehouse_info )
                                                    <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select   data-control="select2" data-placeholder="Select a stock type" class="form-select" name="sp_stock_type[]" data-actions-box="true">
                                                <option value="">-- Select Stock Type --</option>
                                                <option value="3">Returned</option>
                                                <option value="2">defective</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach( $lst_call_products as $index => $item_info )
                                    <tr>
                                        <td>
                                            <b>{{ $item_info->Product->p_product_name }}</b>
                                            <input type="hidden" name="sp_product_id[]" value="{{ $item_info->cp_product_id }}" />
                                        </td>
                                        <td>
                                            {{ $item_info->cp_quantity }}
                                            <input type="hidden" name="sp_quantity[]" value="{{ $item_info->cp_quantity }}" />
                                        </td>
                                        <td>
                                            <select   data-control="select2" data-placeholder="Select a warehouse" class="form-select" name="sp_warehouse_id[]" data-actions-box="true">
                                                <option value="">-- Select Warehouse --</option>
                                                @foreach ( $lst_warehouses as $key => $warehouse_info )
                                                    <option {{ $item_info->cp_warehouse_id == $warehouse_info->w_id ? "selected" : "" }} value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select   data-control="select2" data-placeholder="Select a stock type" class="form-select" name="sp_stock_type[]" data-actions-box="true">
                                                <option value="">-- Select Stock Type --</option>
                                                <option value="1">defective</option>
                                                <option value="2">Returned</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" align="right">
                        <button name="btn_return_products" id="BTN_RETURN_PRODUCTS" class="btn btn-info">
                            Return Products
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
