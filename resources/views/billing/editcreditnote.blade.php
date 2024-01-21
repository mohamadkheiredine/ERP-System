<?php
/***********************************************************
addcreditnote.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 2, 2021
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
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/creditnotes.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/savecreditnotes.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Credit Notes</h3>
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
    <form name="frm_save_creditnotes" id="FORM_SAVE_CREDITNOTES">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="cn_created_by" value="{{ session('user_id') }}" /> 
                        <input type="hidden" name="cn_id" value="{{ $cn_info->cn_id }}" /> 
                        <input type="hidden" name="cn_credit_code" value="{{ $cn_info->cn_credit_code }}" /> 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong>Credit Notes information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong>You have some form errors. Please check below.
            			</div>
                    <div class="row"> 
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Credit Code</label>
                                    <input type="text" name="cn_code" id="CN_CODE" class="form-control"  maxlength="255" readonly="readonly"  value="{{ $cn_info->cn_credit_code }}" />
                                </div>
                        </div>   
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Credit Label</label>
                                    <input type="text" name="cn_credit_label" id="CN_CREDIT_LABEL" class="form-control"  maxlength="255"  value="{{ $cn_info->cn_credit_label }}" />
                                </div>
                        </div>   
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Credit Date </label>
                                    <input type="text" name="cn_credit_date" id="CN_CREATION_DATE" class="form-control"  maxlength="255" readonly="readonly" value="{{ $cn_info->cn_credit_date }}" />
                                </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Payable <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="cn_account_sender" id="CN_ACCOUNT_SENDER" required="required" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option {{ $cn_info->cn_account_sender == $ca_info->aa_id ? "selected" : "" }} value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div> 
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Receivable <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="cn_account_receivable" id="CN_ACCOUNT_RECEIVABLE" required="required" data-actions-box="true">
                                      <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option {{ $cn_info->cn_account_receivable == $ca_info->aa_id ? "selected" : "" }} value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Credit Ammount </label>
                                    <input type="text"  name="cn_credit_value" id="CN_CREDIT_VALUE" class="form-control"  maxlength="50"  value="{{ $cn_info->cn_credit_value }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency </label>
                                <select class="bs-select form-control" name="cn_credit_currency" id="CN_CREDIT_CURRENCY" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ $cn_info->cn_credit_currency == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Second Currency </label>
                                <select class="bs-select form-control" name="cn_second_currency" id="CN_SECOND_CURRENCY" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ $cn_info->cn_second_currency == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Exchange Rate </label>
                                    <input type="text"  name="cn_exchange_rate" id="CN_EXCHANGE_RATE" class="form-control" value="{{ $cn_info->cn_exchange_rate }}" />
                                </div>
                        </div> 
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Credit Notes</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="cn_credit_notes" id="CN_CREDIT_NOTES" >{{ $cn_info->cn_credit_value }}</textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_cnote" id="BTN_SAVE_CNOTE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div> 
@endsection