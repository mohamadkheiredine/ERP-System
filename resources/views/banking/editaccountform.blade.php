<?php
/***********************************************************
editaccountform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 24, 2019
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
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/banking.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/banking/savebanking.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Existing Account</h3>
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
    <form name="frm_save_account" id="FORM_SAVE_ACCOUNT">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                      <input type="hidden" name="ba_id" id="BA_ID" value="{{ $BankingAccount->ba_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Bank Account Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row"> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Account Ref <span class="required"> * </span></label>
                                    <input type="text" name="ba_account_ref" id="BA_ACCOUNT_REF" class="form-control" required="required" maxlength="15" readonly="readonly"  value="{{ $BankingAccount->ba_account_ref }}" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Account Label</label>
                                    <input type="text" name="ba_account_label" id="BA_ACCOUNT_LABEL" class="form-control"  maxlength="255"  value="{{ $BankingAccount->ba_account_label }}" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Account Type <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="ba_account_type" id="BA_ACCOUNT_TYPE" data-actions-box="true">
                                        <option {{ $BankingAccount->ba_account_type == 1 ? "selected" : "" }} value="1">Savings account</option>
                                        <option {{ $BankingAccount->ba_account_type == 2 ? "selected" : "" }} value="2">Current or credit card account</option>
                                        <option {{ $BankingAccount->ba_account_type == 3 ? "selected" : "" }}  value="3">Cash account</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Account Currency <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="ba_account_currency" id="BA_ACCOUNT_CURRENCY" required="required" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ $BankingAccount->ba_account_currency == $currency_info->cc_id ? "selected" : "" }}  value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " .  $currency_info->cc_currency_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Account Country <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="ba_account_country" id="BA_ACCOUNT_COUNTRY" required="required" data-actions-box="true">
                                        <option value="">-- Select Country --</option>
                                        @foreach ( $lst_countries as $key => $country_info )
                                                <option {{ $BankingAccount->ba_account_country == $country_info->id ? "selected" : "" }}  value="{{ $country_info->id }}">{{ $country_info->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Account City</label>
                                    <input type="text" name="ba_account_city" id="BA_ACCOUNT_CITY" class="form-control"  maxlength="255"  value="{{ $BankingAccount->ba_account_city }}" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Initial Balance</label>
                                    <input type="text" name="ba_initial_balance" id="BA_INITIAL_BALANCE" class="form-control"  maxlength="255"  value="{{ $BankingAccount->ba_initial_balance }}" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Minimum Allowed Balance</label>
                                    <input type="text" name="ba_min_allowed_balance" id="BA_MIN_ALLOWED_BALANCE" class="form-control"  maxlength="255"  value="{{ $BankingAccount->ba_min_allowed_balance }}" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Minimum Desired Balance</label>
                                    <input type="text" name="ba_min_desired_balance" id="BA_MIN_DESIRED_BALANCE" class="form-control"  maxlength="255"  value="{{ $BankingAccount->ba_min_desired_balance }}" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Bank Name</label>
                                    <input type="text" name="ba_bank_name" id="BA_BANK_NAME" class="form-control"  maxlength="255"  value="{{ $BankingAccount->ba_bank_name }}" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Account IBAN</label>
                                    <input type="text" name="ba_account_iban" id="BA_ACCOUNT_IBAN" class="form-control"  maxlength="255"  value="{{ $BankingAccount->ba_account_iban }}" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Account Number</label>
                                    <input type="text" name="ba_account_number" id="BA_ACCOUNT_NUMBER" class="form-control"  maxlength="255"  value="{{ $BankingAccount->ba_account_number }}" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Account Swift</label>
                                    <input type="text" name="ba_account_swift" id="BA_ACCOUNT_SWIFT" class="form-control"  maxlength="75"  value="{{ $BankingAccount->ba_account_swift }}" />
                                </div>
                        </div> 
                        <div class="col-md-6">
                             <div class="form-group">
                                <label class="control-label"> Account Address</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="BA_ACCOUNT_ADDRESS"  class="form-control" name="ba_account_address"  cols="">{{ $BankingAccount->ba_account_address }}</textarea>
                             </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Account Owner Name</label>
                                    <input type="text" name="ba_account_owner_name" id="BA_ACCOUNT_OWNER_NAME" class="form-control"  maxlength="255"  value="{{ $BankingAccount->ba_account_owner_name }}" />
                                </div>
                        </div>
                        <div class="col-md-6">
                             <div class="form-group">
                                <label class="control-label"> Account Owner Address</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="BA_ACCOUNT_OWNER_ADDRESS"  class="form-control" name="ba_account_owner_address"  cols="">{{ $BankingAccount->ba_account_owner_address }}</textarea>
                             </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> Accounting Journal <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="ba_accounting_journal" id="BA_ACCOUNTING_JOURNAL" required="required" data-actions-box="true">
                                        <option value="">-- Select Journal --</option>
                                        @foreach ( $lst_journals as $key => $journal_info )
                                                <option {{ $BankingAccount->ba_accounting_journal == $journal_info->aj_id ? "selected" : "" }} value="{{ $journal_info->aj_id }}">{{ $journal_info->aj_journal_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Accounting Account <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="ba_accounting_account" id="BA_ACCOUNTING_ACCOUNT" required="required" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                <option {{ $BankingAccount->ba_accounting_account == $acc_info->aa_id ? "selected" : "" }} value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Account Comment</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="BA_ACCOUNT_COMMENT"  class="form-control" name="ba_account_comment"  cols="">{{ $BankingAccount->ba_account_comment }}</textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_account" id="BTN_SAVE_ACCOUNT"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>

@endsection