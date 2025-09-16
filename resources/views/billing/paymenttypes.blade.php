<?php
/***********************************************************
paymenttypes.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 16, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Payment Types Management"])

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
<script type="text/javascript" src="{{ url('js/libraries/billing/paymenttypes.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Payment Types Management</h3>
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
    <div class="row" id="LstBankAccounts">
		<div class="col-md-12">
		<form name="frm_save_payment_types" id="FRM_SAVE_PAYMENT_TYPES">
    	 <div class="row">
    	 	<div class="col-md-12 table-responsive">
    	 		<span id="hidden_fields">
            		 {!! csrf_field() !!}
            		</span>
            		<table class="table">
            			<thead>
            				<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            					<th style="width:10%">ID</th>
            					<th style="width:60%">Label</th>
            					<th style="width:30%">Account</th>
            				</tr>
            			</thead>
            			<tbody>
            				 @foreach ($lst_payment_types as $key => $pt_info)
            				 	<tr>
                					<th scope="row">{{ $pt_info->pt_id }}<input type="hidden" name="pt_id[]" value="{{ $pt_info->pt_id }}" /></th>
                					<td><input  class="form-control" maxlength="255" type="text" name="pt_payment_type[]" value="{{ $pt_info->pt_payment_type }}" /></td>
                					<td>
                						 <select  name="pt_payment_account[]" class="form-select form-control" data-control="select2" data-placeholder="Select Paymemt Type">
                                                    <option value="">Payment Type Account</option>
                                                    @foreach ( $lst_chart_accounts as $key => $acc_info )
                                                            <option {{ $pt_info->pt_payment_account == $acc_info->aa_id ? "selected" : ""  }} value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label  }}</option>
                                                    @endforeach
                                            </select>
                					</td>
                				</tr>
            				 @endforeach
            		</tbody>
            	 </table>
    	 	</div>
    	 </div>
    	  <div class="row">
    	 	<div class="col-md-12" align="right" >
    	 		<button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
    	 		<button type="button" name="btn_save_pay_type" id="BTN_SAVE_PAY_TYPE" class="btn btn-success">Save info</button>
    	 	</div>
    	 </div>
    	 </div>
	 </form>
	</div>
    </div>
</div>


@endsection
