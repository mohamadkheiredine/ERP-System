<?php
/***********************************************************
addrecurringinvoice
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 18, 2024
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
<script type="text/javascript" src="{{ url('js/modules/recurringinvoices.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/saverecurringinvoices.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Recurring Invoice</h3>
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
    <form name="frm_save_recinvoices" id="FORM_SAVE_RECINVOICES">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Recurring Invoice information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong>You have some form errors. Please check below.
            			</div>
                    <div class="row"> 
                        <div class="col-md-4" >
                             <div class="form-group">
                                <label class="control-label">Client</label><br/> 
                                    <select class="form-select form-select-solid" id="RI_ACCOUNT_ID" name="ri_account_id" data-control="select2" data-placeholder="Select a Account">
                        			<option value="0">-- Select Client --</option>
                                    @foreach($list_accounts as $index => $client_info)
                                      <option value="{{ $client_info->ca_id }}">{{ $client_info->ca_account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            		<label class="control-label">Customer</label><br/>
                                        <select class="form-select form-select-solid" id="RI_CUSTOMER_ID" name="ri_customer_id"  data-control="select2" data-placeholder="Select a Customer">
                            			<option value="">-- Select Customer --</option>
                                        @foreach($list_customers as $index => $customer_info)
                                          <option value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
			</div>
                        <div class="col-md-4">
                            <div class="form-group">
                            		<label class="control-label">Template <span class="required"> * </span></label><br/>
                                        <select class="form-select form-select-solid"  id="RI_TEMPLATE_ID" name="ri_template_id" data-control="select2" data-placeholder="Select Template">
                            			<option value="">-- Select Template --</option>
                                        @foreach($lst_templates as $index => $template_info)
                                          <option value="{{ $template_info->it_id }}">{{ $template_info->it_template_code }}&nbsp;-&nbsp;{{ $template_info->it_template_label }}</option>
                                        @endforeach
                                    </select>
                            </div>
			</div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Recurring Title <span class="required"> * </span></label><br/>
                                <input type="text" name="ri_recurring_title" id="RI_RECURRING_TITLE" required maxlength="255" class="form-control" value="" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Frequency <span class="required"> * </span></label><br/>
                                <select class="form-select form-select-solid" id="RI_FREQUENCY" required="required" required name="ri_frequency" data-control="select2" data-placeholder="Select Frequency">
                        		<option value="">-- Select Frequency --</option> 
                                         @foreach($ri_frequencies as $index => $freq)
                                          <option value="{{ $freq }}">{{ $freq }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Next Invoice Date </label><br/>
                                    <input type="text"  required="required"  autocomplete="off" name="ri_next_invoice_date" id="RI_NEXT_INVOICE_DATE" class="form-control"  maxlength="50" readonly="readonly"  value="" />
                                </div>
                        </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> End Date </label><br/>
                                    <input type="text"  required="required"  autocomplete="off" name="ri_end_date" id="RI_END_DATE" class="form-control"  maxlength="50" readonly="readonly"  value="" />
                                </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Status <span class="required"> * </span></label><br/>
                                <select class="form-select form-select-solid" id="RI_STATUS"  required="required" required name="ri_status" data-control="select2" data-placeholder="Select Frequency">
                        		<option value="">-- Select Status --</option> 
                                         @foreach($ri_statuses as $index => $status)
                                          <option value="{{ $status }}">{{ $status }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div> 
                          <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Last Generated Date</label><br/>
                                    <input type="text"  required="required"  autocomplete="off" name="ri_last_generated_date" id="RI_LAST_GENERATED_DATE" class="form-control"  maxlength="50" readonly="readonly"  value="" />
                                </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                 <br/>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" name="ri_is_activate" id="RI_IS_ACTIVATE"  value="1"  />
                                      <span class="form-check-label fw-semibold text-muted">
                                         Activate Recurring 
                                      </span>
                                  </label>  
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Recurring Description</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="ri_recurring_description" id="RI_RECURRING_DESCRIPTION" ></textarea>
                            </div>
                        </div> 
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6" align="right">
                             <button type="submit" name="btn_save_recinvoice" id="BTN_SAVE_RECINVOICE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
 </div>


@endsection