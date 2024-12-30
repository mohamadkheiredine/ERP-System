<?php
/***********************************************************
addinvoicetemplate.blade.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 14, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
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
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/invoicetemplates.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/saveinvoicetemplates.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Invoice Template</h3>
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
    <form name="frm_save_template" id="FORM_SAVE_TEMPLATE">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="it_template_code" id="IT_TEMPLATE_CODE" value="{{ $it_template_code }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Invoice Template information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong>You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                          	<div class="form-group">
                                <label class="control-label"> Invoice Template Code </label><br/>
                                <span>{{ $it_template_code }}</span>
                            </div>
                        </div>
                        <div class="col-md-4" >
                             <div class="form-group">
                                <label class="control-label">Client <span class="required"> * </span></label><br/> 
                                    <select class="form-select form-select-solid" id="INVOICE_ACCOUNT" name="it_account_id" data-control="select2" data-placeholder="Select a Account">
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
                                        <select class="form-select form-select-solid"  id="FK_CUSTOMER_ID" name="fk_customer_id" data-control="select2" data-placeholder="Select a Customer">
                            			<option value="">-- Select Customer --</option>
                                        @foreach($list_customers as $index => $customer_info)
                                          <option value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
			</div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Template Label <span class="required"> * </span></label><br/>
                                <input type="text" name="it_template_label" id="IT_TEMPLATE_LABEL" required maxlength="255" class="form-control" value="" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Payment Type <span class="required"> * </span></label><br/>
                                <select class="form-select form-select-solid" id="IT_PAYMENT_TYPE" required name="it_payment_type" data-control="select2" data-placeholder="Select Payment Type">
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
                                <select class="form-select form-select-solid" id="IT_PAYMENT_TERMS" name="it_payment_terms" data-control="select2" data-placeholder="Select Payment Terms">
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
                                <select class="form-select form-select-solid" id="IT_VAT_ID" name="it_vat_id" data-control="select2" data-placeholder="Select Tax">
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
                                    <select class="form-select form-select-solid" name="it_invoice_currency" required="required" id="IT_INVOICE_CURRENCY" data-control="select2" data-placeholder="Select Invoice Currency">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ session("company_currency") == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Template Total Cost </label><br/>
                                    <input type="text" name="it_total_cost" id="IT_TOTAL_COST" maxlength="15" class="form-control" value="" />
                                </div>
                        </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Invoice Discount </label><br/>
                                    <input autocomplete="off" type="number" min="0" max="100" step="1.0" name="bi_discount" id="BI_DISCOUNT" class="form-control"  maxlength="15"  value="0" />
                                </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Template Total Price </label><br/>
                                    <input type="text" name="it_total_price" id="IT_TOTAL_PRICE" maxlength="15" class="form-control" value="" />
                                </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Template Description</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="it_template_description" id="IT_TEMPLATE_DESCRIPTION" ></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Template Notes</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="it_invoice_note" id="IT_INVOICE_NOTE" ></textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6" align="right">
                             <button type="submit" name="btn_save_template" id="BTN_SAVE_TEMPLATE"  class="btn btn-info">Save</button>
                             <button type="submit" name="btn_save_new" id="BTN_SAVE_NEW"  class="btn btn-info">Save & New</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
 </div>


@endsection