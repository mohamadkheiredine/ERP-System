<?php
/***********************************************************
addinvoice.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 9, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Form to add and generate invoice
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
<script type="text/javascript" src="{{ url('js/libraries/billing/saveinvoices.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Add New Invoice</h3>
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
             <form name="frm_save_invoice" id="FORM_SAVE_INVOICE">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="bi_invoice_code" id="BI_INVOICE_CODE" value="{{ $invoice_code }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Invoice information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong>You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                          	<div class="form-group">
                                <label class="control-label"> Invoice Ref </label>
                                <input type="text" name="bi_invoice_ref" id="BI_INVOICE_REF" class="form-control"  maxlength="15"  value="{{ $invoice_code }}" />
                            </div>
                        </div>
                        <div class="col-md-4" style="display: none">
                             <div class="form-group">
                                <label class="control-label">Client <span class="required"> * </span></label><br/>
                                <select class="bs-select form-control" id="INVOICE_ACCOUNT" name="invoice_account">
                        			<option value="0">-- Select Client --</option>
                                    @foreach($list_accounts as $index => $client_info)
                                      <option value="{{ $client_info->ca_id }}">{{ $client_info->ca_account_name }}</option>
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
                                          <option value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Invoice Date </label><br/>
                                    <input type="text" autocomplete="off" name="bi_invoice_date" id="BI_INVOICE_DATE" class="form-control"  maxlength="50" readonly="readonly"  value="{{ date('Y-m-d') }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Bank Account</label><br/>
                                <select class="bs-select form-control" required="required" id="FK_BANKACCOUNT_ID" name="fk_bankaccount_id">
                        			<option value="0">-- Select Account --</option>
                                    @foreach($lst_banks_info as $index => $bank_info)
                                      <option value="{{ $bank_info->ba_id }}">{{ $bank_info->ba_account_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Payment Type <span class="required"> * </span></label><br/>
                                <select class="bs-select form-control" required="required" id="FK_PAYMENT_TYPE" name="bi_payment_type">
                        			<option value="">-- Select Payment Type --</option>
                                    @foreach($lst_payment_types as $index => $paytype_info)
                                      <option value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
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
                                      <option value="{{ $payterms_info->pt_id }}">{{ $payterms_info->pt_payment_terms }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Tax Account</label><br/>
                                <select class="bs-select form-control" id="BI_VAT_ID" name="bi_vat_id">
                        			<option value="0">-- Select Tax Account --</option>
                                    @foreach($lst_vat_accounts as $index => $vat_info)
                                      <option value="{{ $vat_info->av_id }}">{{ $vat_info->av_vat_label . "(" . $vat_info->av_vat_rate * 100 . "%)" }}</option>
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
                                                <option {{ session("company_currency") == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4"> 
                        	@if(Session("default_item") == 0)
                            <div class="form-group">
                                <label> Item Type <span class="required"> * </span> </label><br/>
                                <select class="bs-select form-control" name="bi_invoice_items_type" id="BI_INVOICE_ITEMS_TYPE" data-actions-box="true">
                                        <option value="">-- Select Type --</option>
                                        <option value="1">Products</option>
                                        <option value="2">Services</option>
                                </select>
                            </div>
                            @else
                            <div class="form-group">
                                <label> Item Type <span class="required"> * </span> </label><br/>
                                <input type="hidden" name="bi_invoice_items_type" value="{{ Session('default_item') }}" />
                                <label>{{ Session("default_item") == 1 ? "Products" : "Services" }}</label>
                            </div>
                            @endif
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Invoice Discount </label><br/>
                                    <input autocomplete="off" type="number" min="0" max="100" step="1.0" name="bi_discount" id="BI_DISCOUNT" class="form-control"  maxlength="15"  value="0" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Second Currency</label><br/>
                                <select class="bs-select form-control" name="bi_second_currency" id="BI_SECOND_CURRENCY" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ session("company_currency") == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Exchange Rate </label><br/>
                                    <input type="number" autocomplete="off" min="0" max="9999999" step="0.01" name="bi_exchange_rate" id="BI_EXCHANGE_RATE" class="form-control"  maxlength="15"  value="0" />
                                </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Invoice Notes</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="bi_invoice_note" id="BI_INVOICE_NOTE" ></textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
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

@endsection