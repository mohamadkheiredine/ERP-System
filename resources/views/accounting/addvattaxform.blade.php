<?php
/***********************************************************
addvattaxform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 25, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Add VAT/TAX Form
***********************************************************/

?>
@extends('layouts.layout',['page_title' => "TAX Management"])

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
<script type="text/javascript" src="{{ url('js/modules/vataccounts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/savevataccounts.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New VAT Tax</h3>
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
    <form name="frm_save_vat" id="FORM_SAVE_VAT">
                                        <div class="form-body">
                                             <span id="hidden_fields">
                                              <div class="form-group">
                                                {!! csrf_field() !!}
                                                 </div>
                                            </span>
                                            <div class="alert alert-success" style="display:none">
                                    				<strong>Success!</strong> VAT Account Information is saved successfully!
                                    			</div>
                                    			<div class="alert alert-danger" style="display:none">
                                    				<strong>Error!</strong> You have some form errors. Please check below.
                                    			</div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> VAT Code <span class="required"> * </span></label>
                                                            <input type="text" name="av_vat_code" id="AV_VAT_CODE" class="form-control" required="required" maxlength="15"  value="" />
                                                        </div>
                                                </div> 
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> VAT Label <span class="required"> * </span></label>
                                                            <input type="text" name="av_vat_label" id="AV_VAT_LABEL" class="form-control" required="required" maxlength="255"  value="" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> VAT Rate (%) <span class="required"> * </span></label>
                                                            <input type="text" name="av_vat_rate" id="AV_VAT_RATE" class="form-control" required="required" maxlength="255"  value="" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Sales Account <span class="required"> * </span></label>
                                                        <select class="bs-select form-control" name="av_sale_account_code" id="AV_SALE_ACCOUNT_CODE" required="required" data-actions-box="true">
                                                                <option value="">-- Select Account --</option>
                                                                @foreach ( $lst_accounts as $key => $acc_info )
                                                                        <option value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Purchase Account <span class="required"> * </span></label>
                                                        <select class="bs-select form-control" name="av_purchase_account_code" id="AV_PURCHASE_ACCOUNT_CODE" required="required" data-actions-box="true">
                                                                <option value="">-- Select Account --</option>
                                                                @foreach ( $lst_accounts as $key => $acc_info )
                                                                        <option value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                           <div class="row" style="height:5px;"></div>
                                            <div class="row">
                                                <div class="col-md-9"></div>
                                                <div class="col-md-3" align="right">
                                                     <button type="submit" name="btn_save_vat" id="BTN_SAVE_VAT"  class="btn btn-info">Save</button>
                                                    <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
    </div>
 </div>

@endsection