<?php
/***********************************************************
addjvoucherform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 22, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :
Add Journal Vouchers
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
<script type="text/javascript" src="{{ url('js/modules/journalvouchers.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/savejvouchers.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Journal Voucher</h3>
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
    <form name="frm_save_voucher" id="FORM_SAVE_VOUCHER">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="pj_user_id" value="{{ session('user_id') }}" /> 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong>Journal Voucher information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong>You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Voucher Ref </label>
                                    <input type="text" name="pj_code" id="PJ_CODE" class="form-control"  maxlength="15"  value="{{ $jv_code }}" readonly="readonly" />
                                </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Voucher Label</label>
                                    <input type="text" name="pj_voucher_label" id="PJ_VOUCHER_LABEL" class="form-control"  maxlength="255"  value="" />
                                </div>
                        </div>  
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Voucher Date </label>
                                    <input type="text" name="pj_creation_date" id="PJ_CREATION_DATE" class="form-control"  maxlength="50" readonly="readonly"  value="{{ date('Y-m-d') }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Debit <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="pj_account_debit" id="PJ_ACCOUNT_RECEIVABLE" required="required" data-actions-box="true">
                                      <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div> 
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Credit <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="pj_account_credit" id="PJ_ACCOUNT_CREDIT" required="required" data-actions-box="true">
                                      <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Voucher Ammount </label>
                                    <input type="text"  name="pj_payment_amount" id="PJ_PAYMENT_AMOUNT" class="form-control"  maxlength="50"  value="1" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency </label>
                                <select class="bs-select form-control" name="pj_currency_id" id="PJ_CURRENCY_ID" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ session("company_currency") == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Voucher Notes</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="pj_voucher_description" id="PJ_VOUCHER_DESCRIPTION" ></textarea>
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