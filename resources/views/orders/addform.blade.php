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
	<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
    <script type="text/javascript" src="{{ url('default/assets/plugins/jquery-scanner-detection/jquery.scannerdetection.js') }}"></script>
	<script type="text/javascript" src="{{ url('js/modules/orders.module.js') }}"></script>
	<script type="text/javascript" src="{{ url('js/libraries/orders/saveorder.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Add New Order
				</h3>
			</div>
		</div>
		<div class="m-portlet__head-tools">
			<ul class="m-portlet__nav">
				<li class="m-portlet__nav-item">
					<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
						<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
							<i class="la la-ellipsis-h m--font-brand"></i>
						</a>
						<div class="m-dropdown__wrapper">
							<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
							<div class="m-dropdown__inner">
								<div class="m-dropdown__body">
									<div class="m-dropdown__content">
										<ul class="m-nav">
											<li class="m-nav__section m-nav__section--first">
												<span class="m-nav__section-text">
													Quick Actions
												</span>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="m-portlet__body">
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
                                 <img id="BARCODE_IMG" src="data:image/png;base64,{{ $bar_code_png }}" alt="barcode" height="50" width="150"   /><br/>
                                 <label class='lblbarcode'>{{ $rand_barcode }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Order Code:&nbsp;<span class="required"> * </span></label><br/>
                                    <input type="text" name="so_order_code" id="SO_ORDER_CODE" class="form-control"  readonly="readonly"  required="required" maxlength="25" tabindex="1"  value="{{ $order_code }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Assign To:&nbsp;</label><br/>
                                <select class="bs-select form-control" name="so_assign_to" id="SO_ASSIGN_TO" data-actions-box="true"  tabindex="2" >
                                        <option value="">No Parent</option>
                                        @foreach ( $lst_users as $key => $user_info )
                                                <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Order Status:&nbsp;</label><br/>
                                 <select class="bs-select form-control" name="so_order_status" id="SO_ORDER_STATUS" data-actions-box="true"  tabindex="3">
                                        <option value="">-- Status --</option>
                                        @foreach ( $lst_order_status as $key => $status_info )
                                                <option value="{{ $status_info->os_id }}">{{ $status_info->os_status_title }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Warehouse :&nbsp;</label><br/>
                                 <select class="bs-select form-control" name="fk_warehouse_id" id="FK_WAREHOUSE_ID" data-actions-box="true"  tabindex="4">
                                        <option value="">-- warehouse --</option>
                                        @foreach ( $lst_warehouses as $key => $warehouse_info )
                                                <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Order Label:&nbsp;<span class="required"> * </span></label><br/>
                                    <input type="text" name="so_order_label" id="SO_ORDER_LABEL" class="form-control" required="required" maxlength="255"  tabindex="5"  value="" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Order Date:&nbsp;<span class="required"> * </span></label><br/>
                                    <input type="text" name="so_order_date" id="SO_ORDER_DATE" class="form-control" required="required" readonly="readonly"  maxlength="10"  value="{{ date('Y-m-d') }}"  tabindex="6" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Delivery Date:&nbsp; <span class="required"> * </span></label><br/>
                                <input type="text" name="so_delivery_date" id="SO_DELIVERY_DATE" class="form-control" required="required" readonly="readonly"  maxlength="10"  value="{{ date('Y-m-d') }}"  tabindex="7" />
                            </div>
                        </div> 
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Order Currency:&nbsp;</label><br/>
                                 <select class="bs-select form-control" name="so_order_currency" id="SO_ORDER_CURRENCY" data-actions-box="true"  tabindex="8">
                                        <option value=""> -- Currency -- </option>
                                        @foreach ( $lst_currency as $key => $curr_info )
                                                <option value="{{ $curr_info->cc_id }}">{{ $curr_info->cc_currency_code . "-" . $curr_info->cc_currency_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Order Tax:&nbsp;</label><br/>
                                 <select class="bs-select form-control" name="so_vat_id" id="SO_VAT_ID" data-actions-box="true"  tabindex="9">
                                        <option value=""> -- Tax -- </option>
                                        @foreach ( $lst_vat_tax as $key => $tax_info )
                                                <option value="{{ $tax_info->av_id }}">{{ $tax_info->av_vat_label }}&nbsp;(&nbsp;{{ $tax_info->av_vat_rate }}&nbsp;%&nbsp;)</option>
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
                                                <option value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_code }}&nbsp;-&nbsp;{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Product Type:&nbsp;</label><br/>
                                 <select class="bs-select form-control" name="so_product_type" id="SO_PRODUCT_TYPE" data-actions-box="true"  tabindex="11">
                                        <option value=""> -- Product Type -- </option>
                                        <option value="1"> Products </option>
                                        <option value="2"> Services </option>
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="m-form__group form-group row">
								<label class="col-md-12 col-form-label">
									Whole Sales
								</label>
								<div class="col-3">
									<span class="m-switch m-switch--lg m-switch--icon">
										<label>
											<input type="checkbox"  name="so_whole_sale" id="SO_WHOLE_SALE" value="1"  tabindex="12" />
											<span class="WholeSaleSpan"></span>
										</label>
									</span>
								</div>
							</div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Vendor:&nbsp;</label><br/>
                                 <select class="bs-select form-control" name="so_vendor_id" id="SO_VENDOR_ID" data-actions-box="true" disabled="disabled"  tabindex="13">
                                        <option value=""> -- Vendor -- </option>
                                        @foreach ( $lst_vendors as $key => $vendor_info )
                                                <option value="{{ $vendor_info->iv_id }}">{{ $vendor_info->iv_vendor_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                                <label class="control-label"> Order Note:&nbsp;</label>
                                <textarea class="form-control" style="width:100%;height: 250px;" name="so_order_note" id="SO_ORDER_NOTE"  tabindex="12"></textarea>
                            </div>
                        </div> 
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_order" id="BTN_SAVE_ORDER"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection