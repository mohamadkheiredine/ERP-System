<?php

/***********************************************************
editinvoicetemplate
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
        <h3 class="card-title">Edit Invoice Template</h3>
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
                        <input type="hidden" name="it_id" id="IT_ID" value="{{ $invoice_template->it_id }}" />
                        <input type="hidden" name="it_template_code" id="IT_TEMPLATE_CODE" value="{{ $invoice_template->it_template_code }}" />
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
                                <span>{{ $invoice_template->it_template_code }}</span>
                            </div>
                        </div>
                        <div class="col-md-4" >
                             <div class="form-group">
                                <label class="control-label">Client <span class="required"> * </span></label><br/> 
                                    <select class="form-select form-select-solid" id="INVOICE_ACCOUNT" name="it_account_id" data-control="select2" data-placeholder="Select a Account">
                        			<option value="0">-- Select Client --</option>
                                    @foreach($list_accounts as $index => $client_info)
                                      <option {{ $invoice_template->it_account_id == $client_info->ca_id ? "selected" : "" }} value="{{ $client_info->ca_id }}">{{ $client_info->ca_account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            		<label class="control-label">Customer <span class="required"> * </span></label><br/>
                                        <select class="form-select form-select-solid" id="FK_CUSTOMER_ID" name="fk_customer_id" data-control="select2" data-placeholder="Select a Customer">
                            			<option value="">-- Select Customer --</option>
                                        @foreach($list_customers as $index => $customer_info)
                                          <option {{ $invoice_template->fk_customer_id == $customer_info->ic_id ? "selected" : "" }} value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
			</div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Template Label <span class="required"> * </span></label><br/>
                                <input type="text" name="it_template_label" id="IT_TEMPLATE_LABEL" maxlength="255" class="form-control" value="{{ $invoice_template->it_template_label }}" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Payment Type <span class="required"> * </span></label><br/>
                                <select class="form-select form-select-solid" id="IT_PAYMENT_TYPE" required name="it_payment_type" data-control="select2" data-placeholder="Select Payment Type">
                        			<option value="">-- Select Payment Type --</option>
                                    @foreach($lst_payment_types as $index => $paytype_info)
                                      <option {{ $invoice_template->it_payment_type == $paytype_info->pt_id ? "selected" : "" }} value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Payment Terms</label><br/>
                                <select class="form-select form-select-solid" id="IT_PAYMENT_TERMS" required name="it_payment_terms" data-control="select2" data-placeholder="Select Payment Terms">
                        			<option value="0">-- Select Payment Terms --</option>
                                    @foreach($lst_payment_terms as $index => $payterms_info)
                                      <option {{ $invoice_template->it_payment_terms == $payterms_info->pt_id ? "selected" : "" }} value="{{ $payterms_info->pt_id }}">{{ $payterms_info->pt_payment_terms }}</option>
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
                                      <option {{ $invoice_template->it_vat_id == $vat_info->av_id ? "selected" : "" }} value="{{ $vat_info->av_id }}">{{ $vat_info->av_vat_label . "(" . $vat_info->av_vat_rate * 100 . "%)" }}</option>
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
                                                <option {{ $invoice_template->it_invoice_currency == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Template Total Cost </label><br/>
                                    <input type="text" name="it_total_cost" id="IT_TOTAL_COST" maxlength="15" class="form-control" value="{{ $invoice_template->it_total_cost }}" />
                                </div>
                        </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Invoice Discount </label><br/>
                                    <input autocomplete="off" type="number" min="0" max="100" step="1.0" name="it_discount" id="IT_DISCOUNT" class="form-control"  maxlength="15"  value="{{ $invoice_template->it_discount }}" />
                                </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Template Total Price </label><br/>
                                    <input type="text" name="it_total_price" id="IT_TOTAL_PRICE" maxlength="15" class="form-control" value="{{ $invoice_template->it_total_price }}" />
                                </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Template Description</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="it_template_description" id="IT_TEMPLATE_DESCRIPTION" >{{ $invoice_template->it_template_description }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Template Notes</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="it_invoice_note" id="IT_INVOICE_NOTE" >{{ $invoice_template->it_invoice_note }}</textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                                      <div class="row">
                       <div class="col-md-12">
                           <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Services</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab_pane_services" role="tabpanel">
                                 <div class="table-responsive">
                                        <table class="table table-bordered">
                                                <thead>
                                                        <tr class="fw-bold fs-6 text-gray-800">
                                                                <th>#</th>
                                                                <th>id</th>
                                                                <th>Service</th>
                                                                <th>Cost</th>
                                                                <th>Delete</th>
                                                        </tr>
                                                </thead>
                                                <tbody id="LstInvoiceTemplates">
                                                </tbody>
                                        </table>
                                </div>
                                <div class="col-md-12" align="right">
                                    <button type="button" name="btn_add_item" id="BTN_ADD_ITEM"  class="btn btn-success"  data-bs-toggle="modal" data-bs-target="#items_modal" >Add Item</button>
                                    
                                </div>
                            </div>
                        </div>
                       </div>
                   </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6" align="right">
                             <button type="submit" name="btn_save_template" id="BTN_SAVE_TEMPLATE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
 </div>


<div class="modal fade" tabindex="-1" id="items_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Item</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label> Service <span class="required"> * </span></label><br/>
                                <select class="form-select form-select-solid" name="tl_item_id" required="required" id="TL_ITEM_ID" data-control="select2" data-placeholder="Select Item">
                                    <option value="">-- Select Item --</option>
                                    @foreach ( $list_services as $key => $service_info )
                                            <option  value="{{ $service_info->cs_id }}">{{ $service_info->cs_service_code . " - " . $service_info->cs_service_title  }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" id="BTN_SAVE_ITEM" name="btn_save_item" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

@endsection