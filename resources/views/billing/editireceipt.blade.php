<?php
/***********************************************************
addform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 10, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

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
<script type="text/javascript" src="{{ url('js/modules/receipts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/savereceipts.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Edit Receipt</h3>
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
												<a data-action_type="DOWNLOAD"  href="{{ url('billing/invoices/downloadreceipt/' . $receipt_info->br_id ) }}" class="m-nav__link quickactions">
													<i class="m-nav__link-icon fa fa-print"></i>
													<span class="m-nav__link-text">
														download Receipt
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
             <form name="frm_save_receipt" id="FORM_SAVE_RECEIPT">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="br_id" value="{{ $receipt_info->br_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Receipt information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong>You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Receipt Ref </label>
                                    <input type="text" name="br_receipt_number" id="BR_RECEIPT_NUMBER" class="form-control"  maxlength="15"  value="{{ $receipt_info->br_receipt_number }}" readonly="readonly" />
                                </div>
                        </div>
                        <div class="col-md-4" >
                             <div class="form-group">
                                <label class="control-label"> Account <span class="required"> * </span></label><br/>
                                <select class="bs-select form-control" id="BR_ACCOUNT_ID" name="br_account_id">
                        			<option value="0">-- Select Account --</option>
                                    @foreach($lst_accounts as $index => $acc_info)
                                      <option {{ $receipt_info->br_account_id == $acc_info->aa_id ? "selected" : "" }} value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account }}&nbsp;-&nbsp;{{ $acc_info->aa_account_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            		<label class="control-label">Customer <span class="required"> * </span></label><br/>
                            		<select class="bs-select form-control" id="BR_CUSTOMER_ID" name="br_customer_id">
                            			<option value="0">-- Select Customer --</option>
                                        @foreach($lst_customers as $index => $customer_info)
                                          <option {{ $receipt_info->br_customer_id == $customer_info->ic_id ? "selected" : "" }}  value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Receipt Label </label><br/>
                                    <input type="text" name="br_receipt_label" id="BR_RECEIPT_LABEL" class="form-control"  maxlength="255"  value="{{ $receipt_info->br_receipt_label }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Receipt Date </label><br/>
                                    <input type="text" name="br_receipt_date" id="BR_RECEIPT_DATE" class="form-control"  maxlength="50" readonly="readonly"  value="{{ $receipt_info->br_receipt_date }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Payment Type</label><br/>
                                <select class="bs-select form-control" required="required" id="FK_PAYMENT_TYPE" name="bi_payment_type">
                        			<option value="">-- Select Payment Type --</option>
                                    @foreach($lst_payment_types as $index => $paytype_info)
                                      <option {{ $receipt_info->br_payment_type == $paytype_info->pt_id ? "selected" : "" }} value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Invoice</label><br/>
                                <select class="bs-select form-control" id="FK_INVOICE_ID" name="fk_invoice_id">
                        			<option value="0">-- Select Invoice --</option>
                                    @foreach($lst_invoices as $index => $inv_info)
                                      <option {{ $receipt_info->fk_invoice_id == $inv_info->bi_id ? "selected" : "" }} value="{{ $inv_info->bi_id }}">{{ $inv_info->bi_invoice_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Receipt Amount </label><br/>
                                    <input type="text" name="br_payment_value" id="BR_PAYMENT_VALUE" class="form-control"  maxlength="10"  value="{{ $receipt_info->br_payment_value }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency <span class="required"> * </span></label><br/>
                                <select class="bs-select form-control" name="br_receipt_currency" required="required" id="BR_RECEIPT_CURRENCY" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ $receipt_info->br_receipt_currency == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                          <label> Receipt Paid </label>
                           <div class="m-form__group form-group row">
								<div class="col-12">
									<span class="m-switch m-switch--icon m-switch--info">
										<label>
											<input type="checkbox" {{  $receipt_info->br_receipt_paid == 1 ? "checked" : "" }}  name="br_receipt_paid" value="1" />
											<span></span>
										</label>
									</span>
								</div>
								</div>
                        </div> 
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Receipt Notes</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="br_receipt_note" id="BR_RECEIPT_NOTE" >{{ $receipt_info->br_receipt_note }}</textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_receipt" id="BTN_SAVE_RECEIPT"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection