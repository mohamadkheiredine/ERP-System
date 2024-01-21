<?php
/***********************************************************
editinternaltransfer.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 15, 2021
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
<script type="text/javascript" src="{{ url('js/modules/internaltransfers.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/saveinternaltransfers.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Internal Transfer</h3>
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
    <form name="frm_save_internaltransfers" id="FORM_SAVE_INTERNALTRANSFERS">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="in_id" value="{{ $in_info->in_id }}" /> 
                        <input type="hidden" name="in_created_by" value="{{ session('user_id') }}" /> 
                        <input type="hidden" name="transfer_code" value="{{ $in_info->in_transfer_code }}" /> 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong>Internal Transfer information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong>You have some form errors. Please check below.
            			</div>
                    <div class="row">
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Transfer Code</label>
                                    <input type="text" name="in_transfer_code" id="IN_TRANSFER_CODE" class="form-control"  maxlength="255" readonly="readonly"  value="{{ $in_info->in_transfer_code }}" />
                                </div>
                        </div>   
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Transfer Label</label>
                                    <input type="text" name="in_transfert_label" id="IN_TRANSFER_LABEL" class="form-control"  maxlength="255"  value="{{ $in_info->in_transfert_label }}" />
                                </div>
                        </div>   
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Transfer Date </label>
                                    <input type="text" name="in_transfer_date" id="IN_TRANSFER_DATE" class="form-control"  maxlength="15"  value="{{ $in_info->in_transfer_date }}" />
                                </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Payable <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="in_account_sender" id="IN_ACCOUNT_SENDER" required="required" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option {{ $in_info->in_account_sender == $ca_info->aa_id ? "selected" : "" }} value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div> 
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Receivable <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="in_account_receivable" id="IN_ACCOUNT_RECEIVABLE" required="required" data-actions-box="true">
                                      <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option {{ $in_info->in_account_receivable == $ca_info->aa_id ? "selected" : "" }} value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Credit Ammount </label>
                                    <input type="text"  name="in_credit_value" id="IN_CREDIT_VALUE" class="form-control"  maxlength="50"  value="{{ $in_info->in_credit_value }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Debit Ammount </label>
                                    <input type="text"  name="in_debit_value" id="IN_DEBIT_VALUE" class="form-control"  maxlength="50"  value="{{ $in_info->in_debit_value }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency </label>
                                <select class="bs-select form-control" name="in_credit_currency" id="IN_CREDIT_CURRENCY" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ $in_info->in_credit_currency == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Second Currency </label>
                                <select class="bs-select form-control" name="in_second_currency" id="IN_SECOND_CURRENCY" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option  {{ $in_info->in_second_currency == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Exchange Rate </label>
                                    <input type="text"  name="in_exchange_rate" id="IN_EXCHANGE_RATE" class="form-control" value="{{ $in_info->in_exchange_rate }}" />
                                </div>
                        </div> 
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Transfer Notes</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="in_transfer_notes" id="IN_TRANSFER_NOTES" >{{ $in_info->in_transfer_notes }}</textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_note" id="BTN_SAVE_NOTE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
 </div>

@endsection