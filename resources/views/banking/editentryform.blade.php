<?php
/***********************************************************
editentryform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Banking Management"])

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
<script type="text/javascript" src="{{ url('js/modules/bankingentries.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/banking/saveentry.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Entry Info</h3>
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
    <form name="frm_save_entry" id="FORM_SAVE_ENTRY">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                      <input type="hidden" name="be_id" value="{{ $entry_info->be_id }}" /> 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Account Entry Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row"> 
                        <div class="col-md-4">
                             <div class="form-group">
                               <label> Bank <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="be_bank_id" id="BE_BANK_ID" required="required" data-actions-box="true">
                                        <option value="">-- Select Bank --</option>
                                        @foreach ( $lst_bank_accounts as $key => $bank_info )
                                                <option {{ $entry_info->be_bank_id == $bank_info->ba_id ? "selected" : "" }} value="{{ $bank_info->ba_id }}">{{ $bank_info->ba_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Entry Type </label>
                                    <select id="BE_ENTRY_TYPE" class="form-control" name="be_entry_type">
                                    	<option value="">&nbsp;</option>
                                    	@foreach($lst_payment_types as $index => $pt_info)
                                    	<option value="{{ $pt_info->pt_id }}" {{ $pt_info->pt_id == $entry_info->be_entry_type ? "selected" : "" }} data-account_id="{{ $pt_info->pt_payment_account }}" >{{ $pt_info->pt_payment_type }}</option> 
                                    	@endforeach
                                    </select>
                                </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Transfer Transmitter <span class="required"> * </span></label>
                                <input type="text"  name="be_transfer_transmitter" id="BE_TRANSFER_TRANSMITTER"  class="form-control" value="{{ $entry_info->be_transfer_transmitter }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Bank Transfer <span class="required"> * </span></label>
                                <input type="text"  name="be_bank_transfer" id="BE_BANK_TRANSFER"  class="form-control" value="{{ $entry_info->be_bank_transfer }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Operation Date <span class="required"> * </span></label>
                                <input type="text"  name="be_operation_date" required="required" id="BE_OPERATION_DATE" readonly="readonly"  class="form-control" value="{{ $entry_info->be_operation_date }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Value Date <span class="required"> * </span></label>
                                <input type="text"  name="be_value_date" required="required" id="BE_VALUE_DATE" readonly="readonly"  class="form-control" value="{{ $entry_info->be_value_date }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Entry Label <span class="required"> * </span></label>
                                <input type="text"  name="be_entry_label" id="BE_ENTRY_LABEL" required="required" maxlength="255" class="form-control" value="{{ $entry_info->be_entry_label }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Entry Amount <span class="AmountCurrency" style="font-weight: bold"></span><span class="required"> * </span></label>
                                <input type="text"  name="be_entry_amount" required="required" id="BE_ENTRY_AMOUNT" maxlength="255" class="form-control" value="{{ $entry_info->be_entry_amount }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Payable <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="be_source_account_id" id="BE_SOURCE_ACCOUNT_ID" required="required" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option {{ $ca_info->aa_id == $entry_info->be_source_account_id ? "selected='selected'" : "" }} value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div> 
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Receivable <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="be_dest_account_id" id="BE_DEST_ACCOUNT_ID" required="required" data-actions-box="true">
                                      <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option {{ $ca_info->aa_id == $entry_info->be_dest_account_id ? "selected='selected' " : "" }} value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group" align="left">
                                <label class="control-label"> Entry Information </label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="BE_ENTRY_DESCRIPTION"  class="form-control" name="be_entry_description"  cols=""></textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_entry" id="BTN_SAVE_ENTRY"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
 </div>
@endsection