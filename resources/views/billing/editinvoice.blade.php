<?php
/***********************************************************
editinvoice.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 10, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Billing Module"])

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
<script type="text/javascript" src="{{ url('js/modules/invoices.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/receipts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/saveinvoices.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
						Edit Invoice Settings&nbsp;-&nbsp;{{ $invoice_info->bi_invoice_code }}&nbsp;-&nbsp;{!! $invoice_info->bi_invoice_status == 0 ? "<span class='m--font-info'>Draft</span>" : "<span class='m--font-primary'>Official</span>" !!}
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
											@if( $invoice_info->bi_invoice_status == 0 )
											<li class="m-nav__item">
												<a data-action_type="CONVERT_TO_OFFICIAL"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon fa  fa-barcode"></i>
													<span class="m-nav__link-text">
														Convert to official Invoice
													</span>
												</a>
											</li>
											@else
											<li class="m-nav__item">
												<a data-action_type="REVERT_TO_DRAFT"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon fa  fa-barcode"></i>
													<span class="m-nav__link-text">
														Refert Back to draft Invoice
													</span>
												</a>
											</li>
											@endif
											<li class="m-nav__item">
												<a data-action_type="CREATE_RECEIPT"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon fa  fa-barcode"></i>
													<span class="m-nav__link-text">
														Create Receipt
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a data-action_type="PRINT_INVOICE"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon fa  fa-print"></i>
													<span class="m-nav__link-text">
														Print Invoice
													</span>
												</a>
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
             <form name="frm_save_invoice" id="FORM_SAVE_INVOICE">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                        <input type="hidden" name="bi_id" id="BI_ID" value="{{ $invoice_info->bi_id }}" />
                        <input type="hidden" name="bi_invoice_code" id="BI_INVOICE_CODE" value="{{ $invoice_info->bi_invoice_code }}" />
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Invoice information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Invoice Ref </label><br/>
                                    <input type="text" name="bi_invoice_ref" id="BI_INVOICE_REF" class="form-control"  maxlength="15"  value="{{ $invoice_info->bi_invoice_ref }}" />
                                </div>
                        </div>
                        <div class="col-md-4" style="display: none">
                             <div class="form-group">
                                <label class="control-label">Client <span class="required"> * </span></label><br/>
                                <select class="bs-select form-control" id="INVOICE_ACCOUNT" name="invoice_account">
                        			<option value="0">-- Select Client --</option>
                                    @foreach($list_accounts as $index => $client_info)
                                      <option {{ $invoice_info->bi_client_id == $client_info->ca_id ? "selected" : "" }} value="{{ $client_info->ca_id }}">{{ $client_info->ca_account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            		<label class="control-label">Customer <span class="required"> * </span></label><br/>
                            		<select class="bs-select form-control" id="FK_CUSTOMER_ID" name="fk_customer_id">
                            			<option value="">-- Select Customer --</option>
                                        @foreach($list_customers as $index => $customer_info)
                                          <option {{ $invoice_info->fk_customer_id == $customer_info->ic_id ? "selected" : "" }} value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Invoice Date </label><br/>
                                    <input type="text"  autocomplete="off" name="bi_invoice_date" id="BI_INVOICE_DATE" class="form-control"  maxlength="50" readonly="readonly"  value="{{ $invoice_info->bi_invoice_date }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Bank Account </label><br/>
                                <select class="bs-select form-control" required="required" id="FK_BANKACCOUNT_ID" name="fk_bankaccount_id">
                        			<option value="0">-- Select Account --</option>
                                    @foreach($lst_banks_info as $index => $bank_info)
                                      <option {{ $invoice_info->fk_bankaccount_id == $bank_info->ba_id ? "selected" : "" }}  value="{{ $bank_info->ba_id }}">{{ $bank_info->ba_account_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Payment Type <span class="required"> * </span> </label><br/>
                                <select class="bs-select form-control" required="required" id="FK_PAYMENT_TYPE" name="bi_payment_type">
                        			<option value="">-- Select Payment Type --</option>
                                    @foreach($lst_payment_types as $index => $paytype_info)
                                      <option {{ $invoice_info->bi_payment_type == $paytype_info->pt_id ? "selected" : "" }} value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Payment Terms</label><br/>
                                <select class="bs-select form-control" id="FK_PAYMENT_TERMS" name="bi_payment_terms">
                        			<option value="0">-- Select Payment Terms --</option>
                                    @foreach($lst_payment_terms as $index => $payterms_info)
                                      <option {{ $invoice_info->bi_payment_terms == $payterms_info->pt_id ? "selected" : "" }} value="{{ $payterms_info->pt_id }}">{{ $payterms_info->pt_payment_terms }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Tax Account</label>
                                <select class="bs-select form-control" id="BI_VAT_ID" name="bi_vat_id"> 
                        			<option value="0">-- Select Tax Account --</option>
                                    @foreach($lst_vat_accounts as $index => $vat_info)
                                      <option {{ $invoice_info->bi_vat_id == $vat_info->av_id ? "selected" : "" }} value="{{ $vat_info->av_id }}">{{ $vat_info->av_vat_label . "(" . $vat_info->av_vat_rate. "%)" }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency <span class="required"> * </span></label><br/>
                                <select class="bs-select form-control" name="bi_invoice_currency" required="required" id="BI_INVOICE_CURRENCY" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ $invoice_info->bi_invoice_currency  == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Item Type <span class="required"> * </span> </label><br/>
                                <select {{  $invoice_info->bi_invoice_status == 1 ? "disabled='disabled'" : "" }} class="bs-select form-control" name="bi_invoice_items_type" id="BI_INVOICE_ITEMS_TYPE" data-actions-box="true">
                                        <option value="">-- Select Type --</option>
                                        <option {{ $invoice_info->bi_invoice_type  == 1 ? "selected" : "" }} value="1">Products</option>
                                        <option {{ $invoice_info->bi_invoice_type  == 2 ? "selected" : "" }} value="2">Services</option>
                                </select>
                                <input type="hidden" name="ini_invoice_type" value="{{ $invoice_info->bi_invoice_type }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Invoice Discount </label><br/>
                                    <input type="number"  autocomplete="off" min="0" max="100" step="1.0" name="bi_discount" id="BI_DISCOUNT" class="form-control"  maxlength="15"  value="{{  $invoice_info->bi_discount }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Second Currency</label><br/>
                                <select class="bs-select form-control" name="bi_second_currency" id="BI_SECOND_CURRENCY" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{  $invoice_info->bi_second_currency == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Exchange Rate </label><br/>
                                    <input type="number"  autocomplete="off" min="0" max="9999999" step="0.01" name="bi_exchange_rate" id="BI_EXCHANGE_RATE" class="form-control"  maxlength="15"  value="{{  $invoice_info->bi_exchange_rate }}" />
                                </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Invoice Notes</label><br/>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="bi_invoice_note" id="BI_INVOICE_NOTE" >{{ $invoice_info->bi_invoice_note }}</textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:15px;"></div>
                   <div class="row">
                   		<div class="col-md-12">
                   			 <ul class="nav nav-tabs" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#tabProducts">
											{{ $invoice_info->bi_invoice_type == 1 ? "Products" : "Services" }}
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tabPayments">
											Payments
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tabReceipts">
											Receipts
										</a>
									</li>
									 
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tabProducts" role="tabpanel">
										<div class="row">
											<div class="col-md-12" id="LstProducts" align="center"></div>
										</div>
										<div class="row">
											<div class="col-md-12" align="right">
												@if($invoice_info->bi_invoice_status == 0)
    												@if($invoice_info->bi_invoice_type == 1)
    													<button type="button" name="btn_add_product" id="BTN_ADD_PRODUCT" class="btn btn-danger">Add Product</button>
    												@else
    													<button type="button" name="btn_add_service" id="BTN_ADD_SERVICE" class="btn btn-danger">Add Service</button>
    												@endif
												@endif
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tabPayments" role="tabpanel">
										<div class="row">
											<div class="col-md-12" id="LstPaymentSplits"></div>
										</div>
										@if($invoice_info->bi_invoice_status == 0)
										<div class="row">
											<div class="col-md-11" align="right">
												 <button type="button" name="btn_create_rows" id="BTN_CREATE_ROWS" class="btn btn-info">Create Rows</button>
												
											</div>
											<div class="col-md-1" align="right">
												<input type="number" name="number_payments" id="NUMBER_PAYMENT" min="0" max="50" value="0" step="1" class="form-control" />
											</div>
										</div>
										<div class="row" style="padding-top:10px;">
											<div class="col-md-12" align="right">
												 <button type="button" name="btn_save_rows" id="BTN_SAVE_ROWS" class="btn btn-focus">Save Rows</button>
												
											</div>
										</div>
										@endif
									</div>
									<div class="tab-pane" id="tabReceipts" role="tabpanel">
										<div class="row">
											<div class="col-md-12" id="LstReceipts"></div>
										</div>
										@if($invoice_info->bi_invoice_status == 0)
										<div class="row">
											
											<div class="col-md-12" align="right">
												<button type="button" name="btn_generate_receipts" id="BTN_GENERATE_RECEIPTS" class="btn btn-danger">Generate Receipts</button>
											</div>
										</div>
										@endif
									</div>
								</div>
                   		</div>
                   </div>
                   <div class="row" style="height:15px;"></div>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6" align="right">
                             <button type="submit" name="btn_save_invoice" id="BTN_SAVE_INVOICE"  class="btn btn-info">Save</button>
                             <button type="submit" name="btn_save_new" id="BTN_SAVE_NEW"  class="btn btn-info">Save & New</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>
<!-- Insert Product -->
<div class="modal fade" id="InserItems" tabindex="-1" role="dialog" aria-labelledby="InserItemsModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InserItemsModalLabel">
					Insert Product
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_invoice_items" id="FRM_INVOICE_ITEMS" method="post"  enctype="multipart/form-data"> 
				    {!! csrf_field() !!}
				     <input type="hidden" name="invoice_id" value="{{ $invoice_info->bi_id }}" />
				     <input type="hidden" name="invoice_type_item" id="INVOICE_TYPE_ITEM" value="{{ $invoice_info->bi_invoice_type }}" />
				     <input type="hidden" name="item_id" value="" />
				 	<div class="row">
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> {{ $invoice_info->bi_invoice_type == 1 ? "Products" : "Services" }} </label><br/>
                                     <select class="bs-select form-control" name="bi_product" id="bi_product"  style="width:100%" data-actions-box="true">
                                            <option value=""> -- Product -- </option>
                                            @foreach($lst_products as $key => $product_info)
                                                    <option value="{{ $product_info->p_id }}">{{ $product_info->p_product_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> Quanity </label><br/>
                                     <input type="number"  autocomplete="off" name="bi_quanity" class="form-control" max="99999999" min="1" step="1" value="1" />
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
            					Close
            				</button>
            				<button type="submit" name="btn_insert_item" id="BTN_INSERT_ITEM" class="btn btn-primary">
            					Insert
            				</button>
				 		</div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">
				
			</div>
		</div>
	</div>
</div>
<!-- End Insert Product -->
<!-- Insert Service -->
<div class="modal fade" id="InsertServices" tabindex="-1" role="dialog" aria-labelledby="InsertServicesModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InsertServicesModalLabel">
					Insert Service
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_invoice_services" id="FRM_INVOICE_SERVICES" method="post"  enctype="multipart/form-data"> 
				    {!! csrf_field() !!}
				     <input type="hidden" name="invoice_id" value="{{ $invoice_info->bi_id }}" />
				     <input type="hidden" name="invoice_type_item" id="INVOICE_TYPE_ITEM" value="{{ $invoice_info->bi_invoice_type }}" />
				     <input type="hidden" name="invoice_payment_type" id="INVOICE_PAYMENT_TYPE" value="{{ $invoice_info->bi_payment_type }}" />
				     <input type="hidden" name="item_id"  value="" />
				 	<div class="row">
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> {{ $invoice_info->bi_invoice_type == 1 ? "Products" : "Services" }} </label><br/>
                                     <select class="bs-select form-control" name="bi_service_id" id="BI_SERVICE_ID" required="required"  style="width:100%" data-actions-box="true">
                                            <option value=""> -- Services -- </option>
                                            @foreach($list_services as $key => $service_info)
                                                    <option data-validatept={{ $service_info->cs_validate_payment_type }} value="{{ $service_info->cs_id }}">{{ $service_info->cs_service_title }}</option>
                                            @endforeach
                                    </select>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label">Supplier</label><br/>
                                     <select class="bs-select form-control" name="ii_supplier_id" id="II_SUPPLIER_ID" required="required"  style="width:100%" data-actions-box="true">
                                            <option value=""> -- Supplier -- </option>
                                            @foreach( $lst_suppliers as $key => $supp_info )
                                                    <option value="{{ $supp_info->ss_id }}">{{ $supp_info->ss_supplier_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group PaymentType" style="display: none">
                                    <label class="control-label">Payment Type</label><br/>
                                     <select class="bs-select form-control" name="ii_payment_type" id="II_PAYMENT_TYPE" style="width:100%" data-actions-box="true">
                                            <option value=""> -- Supplier -- </option>
                                            @foreach( $lst_payment_types as $key => $pt_info )
                                                    <option value="{{ $pt_info->pt_id }}">{{ $pt_info->pt_payment_type }}</option>
                                            @endforeach
                                    </select>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			<label class="control-label">Service Cost Price</label></br>
				 			<input type="text"  autocomplete="off" class="form-control" name="ii_cost_price" id="II_COST_PRICE"  required="required" value="" />
				 		</div>
				 		<div class="col-md-12">
				 			<label class="control-label">Service Price</label></br>
				 			<input type="text"  autocomplete="off" class="form-control" name="bi_service_price" id="PI_SERVICE_PRICE"  required="required" value="" />
				 		</div>
				 		<div class="col-md-12">
				 			<label class="control-label">Service Currency</label></br>
				 			<span><b>{{ $currencies_array[$invoice_info->bi_invoice_currency]['cc_currency_code'] }}</b></span>
				 		</div>
				 		<div class="col-md-12" align="right">
				 			<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
            					Close
            				</button>
            				<button type="submit" name="btn_insert_service" id="BTN_INSERT_SERVICE" class="btn btn-primary">
            					Insert
            				</button>
				 		</div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">
				
			</div>
		</div>
	</div>
</div>
<!-- End Insert Service -->
@endsection
