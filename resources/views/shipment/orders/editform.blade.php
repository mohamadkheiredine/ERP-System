<?php
/* * *********************************************************
  editform.blade.php
  Product :
  Version : 1.0
  Release : 1
  Date Created : Dec 9, 2019
  Developed By  : Mohamad Mantach   PHP Department itm Solutions
  All Rights Reserved ,   itm Solutions COPYRIGHT 2019

  Page Description :

 * ********************************************************* */
?>
@extends('layouts.layout',['page_title' => "Order Management"])

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
<script type="text/javascript" src="{{ url('default/assets/plugins/jquery-scanner-detection/jquery.scannerdetection.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/sorders.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/shipment/orders/saveorder.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Order</h3>
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
        <form name="frm_save_order" id="FORM_SAVE_ORDER">
            <div class="form-body">
                <span id="hidden_fields">
                    {!! csrf_field() !!}
                    <input type="hidden" name="so_id" id="SO_ID" value="{{ $order_info->so_id }}" />
                    <input type="hidden" name="so_order_barcode" value="{{ $order_info->so_order_barcode }}" />
                    <input type="hidden" name="so_barecode_img" value="{{ $order_info->so_barecode_img }}" />
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
                            <img id="BARCODE_IMG" src="data:image/png;base64,{{ $order_info->so_barecode_img }}" alt="barcode" height="50" width="150"   /><br/>
                            <label class='lblbarcode'>{{ $order_info->so_order_barcode }}</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"> Order Code :&nbsp;</label><br/>
                            <input type="text" name="so_order_code" id="SO_ORDER_CODE" class="form-control" readonly="readonly" required="required" maxlength="25"  tabindex="1" value="{{ $order_info->so_order_code != null ? $order_info->so_order_code : $order_code }}" />
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Assign To :&nbsp;</label><br/>
                            <select class="bs-select form-control" name="so_assign_to" id="SO_ASSIGN_TO" data-actions-box="true" tabindex="2">
                                <option value="">No Parent</option>
                                @foreach ( $lst_users as $key => $user_info )
                                <option {{ $order_info->so_assign_to == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Order Status :&nbsp;</label><br/>
                            <select class="bs-select form-control" name="so_order_status" id="SO_ORDER_STATUS" data-actions-box="true" tabindex="3">
                                <option value="">-- Status --</option>
                                @foreach ( $lst_order_status as $key => $status_info )
                                <option {{ $order_info->fk_status_id == $status_info->ss_id ? "selected" : "" }} value="{{ $status_info->ss_id }}">{{ $status_info->ss_status_title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Warehouse :&nbsp;</label><br/>
                            <select class="bs-select form-control" name="fk_warehouse_id" id="FK_WAREHOUSE_ID" data-actions-box="true" tabindex="4">
                                <option value="">-- warehouse --</option>
                                @foreach ( $lst_warehouses as $key => $warehouse_info )
                                <option {{ $order_info->fk_warehouse_id == $warehouse_info->w_id ? "selected" : "" }} value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"> Order Label :&nbsp;<span class="required"> * </span></label><br/>
                            <input type="text" name="so_order_label" id="SO_ORDER_LABEL" class="form-control" required="required" maxlength="255"  value="{{ $order_info->so_order_label }}"  tabindex="5" />
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"> Order Date :&nbsp;</label><br/>
                            <input type="text" name="so_order_date" id="SO_ORDER_DATE" class="form-control" required="required" readonly="readonly"  maxlength="10"  value="{{ date('m/d/Y',strtotime($order_info->so_order_date)) }}"  tabindex="6" />
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"> Delivery Date :&nbsp;</label><br/>
                            <input type="text" name="so_delivery_date" id="SO_DELIVERY_DATE" class="form-control" required="required" readonly="readonly"  maxlength="10"  value="{{ date('m/d/Y',strtotime($order_info->so_delivery_date)) }}" tabindex="7" />
                        </div>
                    </div> 
                    
                        <div class="col-md-4">
                             <div class="form-group">
                                   <label class="control-label"> Customer Payment:&nbsp;</label><br/>
                                   <input type="text" name="so_customer_payment" id="SO_CUSTOMER_PAYMENT" class="form-control"   required="required"   value="{{ $order_info->so_customer_payment }}" />
                               </div>
                       </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Order Currency :&nbsp;</label><br/>
                            <select class="bs-select form-control" name="so_order_currency" id="SO_ORDER_CURRENCY" data-actions-box="true" tabindex="8">
                                <option value=""> -- Currency -- </option>
                                @foreach ( $lst_currency as $key => $curr_info )
                                <option {{ $order_info->so_currency_id ==  $curr_info->cc_id ? "selected" : "" }} value="{{ $curr_info->cc_id }}">{{ $curr_info->cc_currency_code . "-" . $curr_info->cc_currency_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Order Tax :&nbsp;</label><br/>
                            <select class="bs-select form-control" name="so_vat_id" id="SO_VAT_ID" data-actions-box="true"  tabindex="9">
                                <option value=""> -- Tax -- </option>
                                @foreach ( $lst_vat_tax as $key => $tax_info )
                                <option {{ $order_info->so_vat_id ==  $tax_info->av_id ? "selected='selected'" : "" }}  value="{{ $tax_info->av_id }}">{{ $tax_info->av_vat_label }}&nbsp;(&nbsp;{{ $tax_info->av_vat_rate }}&nbsp;%&nbsp;)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Customer:&nbsp;</label><br/>
                            <select class="bs-select form-control" name="so_order_customer" id="SO_ORDER_CUSTOMER" data-actions-box="true"  tabindex="10">
                                <option value=""> -- Customer -- </option>
                                @foreach ( $lst_customers as $key => $customer_info )
                                <option {{ $order_info->so_customer_id == $customer_info->ic_id ? "selected='selected'" : "" }} value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_code }}&nbsp;-&nbsp;{{ $customer_info->ic_customer_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Supplier:&nbsp;</label><br/>
                            <select class="bs-select form-control" name="so_supplier_id" id="SO_SUPPLIER_ID" data-actions-box="true" tabindex="13">
                                <option value=""> -- Supplier -- </option>
                                @foreach ( $lst_suppliers as $key => $supplier_info )
                                <option {{ $order_info->so_supplier_id == $supplier_info->ss_id ? "selected='selected'" : "" }} value="{{ $supplier_info->ss_id }}">{{ $supplier_info->ss_supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Order Note :&nbsp;</label>
                            <textarea class="form-control" style="width:100%;height: 250px;" name="so_order_note" id="SO_ORDER_NOTE"  tabindex="12">{{ $order_info->so_order_note }}</textarea>
                        </div>
                    </div> 
                </div>
                <div class="row" style="height:5px;"></div>
                <div class="row">
                    <div class="col-md-7"></div>
                    <div class="col-md-5" align="right">
                        <button type="submit" name="btn_save_order" id="BTN_SAVE_ORDER"  class="btn btn-info">Save</button>
                        @if( $order_info->so_order_paied == 0 )
                        <button type="button" name="btn_pay_order" id="BTN_PAY_ORDER"  class="btn btn-danger">Pay Order</button>
                        @endif
                        <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                    </div>
                </div>
            </div>
        </form>
        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tabPackage">Package Content</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tabPackage" role="tabpanel">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" >
                            <table class="table table-row-dashed table-row-gray-300 gy-7">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800">
                                        <th title="#">#</th>
                                        <th title="Id"> ID </th>
                                        <th title="Category"> Category </th>
                                        <th title="Quantity"> Quantity </th>
                                        <th title="Package Price">Package Price</th>
                                    </tr>
                                </thead>
                                <tbody  id="LstPackingCategories" >

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12" align="right">
                        @if( $order_info->so_order_paied == 0 )
                        <button type="button" name="btn_add_category" id="BTN_ADD_CATEGORY" class="btn btn-success" >Add Category</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="OrderPackageModel" tabindex="-1" role="dialog" aria-labelledby="OrderProductsModelLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="OrderPackageModelLabel">
                            Order Package
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                                &times;
                            </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form name="frm_add_packing" id="FRM_ADD_PACKING"  method="post"  enctype="multipart/form-data"> 
                            {!! csrf_field() !!}
                            <input type="hidden" name="order_id" id="ORDER_ID" value="{{ $order_info->so_id }}" />
                            <input type="hidden" name="currency_id" id="CURRENCY_ID" value="0" />
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" tabindex="0">Weight</label>
                                        <input type="text" name="so_package_weight" id="SO_PACKAGE_WEIGHT" class="form-control" required="required" value=""  tabindex="2"  />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>Category</label>
                                    <select class="form-control" name="so_product_category" id="SO_PRODUCT_CATEGORY" required="required" style="width:100%;" data-actions-box="true" tabindex="1">
                                        <option value=""> -- Category -- </option>
                                        @foreach ( $lst_categories as $key => $category_info )
                                        <option value="{{ $category_info->pc_id }}">{{ $category_info->pc_category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" tabindex="0">Cost</label>
                                        <input type="text" name="so_package_cost" id="SO_PACKAGE_COST" class="form-control" required="required" value="0"  tabindex="3" />
                                    </div>
                                </div>
                                <div class="col-md-12" align="left">&nbsp;</div>
                                <div class="col-md-6" align="left">
                                    <span id="AjaxLoader" style="display:none;"><img src="{{ url('images/loader.gif') }}" style="height:60px" /></span>
                                </div>
                                <div class="col-md-6" align="right">
                                    <button type="submit" name="btn_save_category" id="BTN_SAVE_CATEGORY" class="btn btn-primary">
                                        Add Category
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close 
                        </button>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>

@endsection