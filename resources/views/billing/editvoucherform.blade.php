<?php
/***********************************************************
editvoucherform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 21, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

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
<script type="text/javascript" src="{{ url('js/modules/paymentvouchers.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/savevouchers.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Edit Payment Voucher</h3>
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
											<li class="m-nav__item">
												<a href="#" class="m-nav__link PrintVoucher">
													<i class="m-nav__link-icon flaticon-chat-1"></i>
													<span class="m-nav__link-text">
														Print Voucher
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
             <form name="frm_save_voucher" id="FORM_SAVE_VOUCHER">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="pv_id" id="PV_ID" value="{{ $payment_vouchers->pv_id }}" />
                        <input type="hidden" name="pv_user_id" value="{{ $payment_vouchers->pv_user_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong>Payment Voucher information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong>You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Voucher Ref </label>
                                    <input type="text" name="pv_code" id="PV_CODE" class="form-control"  maxlength="15"  value="{{ $payment_vouchers->pv_code }}" readonly="readonly" />
                                </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Voucher Label</label>
                                    <input type="text" name="pv_voucher_label" id="PV_VOUCHER_LABEL" class="form-control"  maxlength="255"  value="{{ $payment_vouchers->pv_voucher_label }}" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Voucher Date </label>
                                    <input type="text" name="pv_creation_date" id="PV_CREATION_DATE" class="form-control"  maxlength="50" readonly="readonly"  value="{{ $payment_vouchers->pv_creation_date }}" />
                                </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Payable <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="pv_account_payable" id="PV_ACCOUNT_PAYABLE" required="required" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option {{ $payment_vouchers->pv_account_payable == $ca_info->aa_id ? "selected" : "" }} value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div> 
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Receivable <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="pv_account_receivable" id="PV_ACCOUNT_RECEIVABLE" required="required" data-actions-box="true">
                                      <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option {{ $payment_vouchers->pv_account_receivable == $ca_info->aa_id ? "selected" : "" }}  value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Voucher Ammount </label>
                                    <input type="text"  name="pv_payment_amount" id="PV_PAYMENT_AMOUNT" class="form-control"  maxlength="50"  value="{{ $payment_vouchers->pv_payment_amount }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency </label>
                                <select class="bs-select form-control" name="pv_currency_id" id="PV_CURRENCY_ID" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{  $payment_vouchers->pv_currency_id  == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> Second Currency </label>
                                <select class="bs-select form-control" name="pv_sec_currency_id" id="PV_SEC_CURRENCY_ID" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ $payment_vouchers->pv_sec_currency_id  == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Exchange Rate </label>
                                    <input type="text"  name="pv_exchange_rate" id="PV_EXCHANGE_RATE" class="form-control" value="{{ $payment_vouchers->pv_exchange_rate }}" />
                                </div>
                        </div>
                        <div class="col-md-10">
                            	<table class="table table-bordered m-table m-table--head-bg-brand">
            						<thead>
            							<tr>
            								<th style="width:2px;">#</th>
            								<th style="width:2px;">ID</th>
            								<th style="width:20%">Account</th>
            								<th style="width:20%">Amount</th>
            								<th style="width:20%">Currency</th>
            								<th style="width:40%">Note</th>
            								<th style="width:4px;white-space: nowrap;text-align: center">edit</th>
            								<th style="width:4px;white-space: nowrap;text-align: center">Delete</th>
            							</tr>
            						</thead>
            						<tbody  class="LstExtensionVouchers"></tbody>
            					</table>
                        </div>
                        <div class="col-md-12" align="right">
                        	<button type="button" name="btn_add_extension" id="BTN_ADD_EXTENSION" class="btn btn-danger" >Add Extension</button>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Voucher Notes</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="pv_voucher_description" id="PV_VOUCHER_DESCRIPTION" >{{ $payment_vouchers->pv_voucher_description  }}</textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_voucher" id="BTN_SAVE_VOUCHER"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection