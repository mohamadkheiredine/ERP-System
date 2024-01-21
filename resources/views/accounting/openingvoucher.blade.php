<?php
/***********************************************************
openingvoucher.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 15, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/
 
?>

@extends('layouts.layout',['page_title' => "Opening Voucher"])

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
<script type="text/javascript" src="{{ url('js/modules/accounting.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/openingvoucher.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Opening Voucher</h3>
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
    <form name="frm_save_opening_voucher" id="FORM_SAVE_OPENING_VOUCHER">
            <div class="form-body">
                 <span id="hidden_fields">
                    {!! csrf_field() !!}
                    <input type="hidden" name="at_id" value="{{ $ov_info->at_id }}" /> 
					<input type="hidden" name="fisical_year" value="{{ date('Y') }}" />
                </span>
                <div class="alert alert-success"  id="SUCCESS_MSG" style="display:none">
        				<strong>Success!</strong> Transaction Information is saved successfully!
        			</div>
        			<div class="alert alert-danger" id="ERROR_MSG" style="display:none">
        				<strong>Error!</strong> You have some form errors. Please check below.
        			</div>
                <div class="row">
                    <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Transaction Date </label>
                                <input type="text" autocomplete="off" name="at_transaction_date" id="AT_TRANSACTION_DATE" class="form-control"  maxlength="255"  value="{{ $ov_info->at_transaction_date }}" />
                            </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label> Journal </label>
                            <select class="bs-select form-control" name="fk_acc_journal_id" id="FK_ACC_JOURNAL_ID" data-actions-box="true">
                                    <option value="0">Accounting Journal</option>
                                    @foreach ( $lst_journals as $key => $journal_info )
                                            <option {{ $ov_info->fk_acc_journal_id ==  $journal_info->aj_id ? "selected" : "" }} value="{{ $journal_info->aj_id }}">{{ $journal_info->aj_journal_code . " - " . $journal_info->aj_journal_label  }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div> 
                    <div class="col-md-4">
                         <div class="form-group">
                            <label class="control-label">Accounting Doc. <span class="required"> * </span></label>
                            <input type="text" name="at_accounting_doc" id="AT_ACCOUNTING_DOC" class="form-control" maxlength="255"  value="{{  $ov_info->at_accounting_doc }}" />
                        </div>
                    </div>
                </div>
               <div class="row" style="height:25px;"></div> 
               <div class="row">
               		<div class="col-md-12" id="LstOpeningMovements">
               			
               		</div>
               </div>
               <div class="row" style="height:25px;"></div>
               <div class="row">
                    <div class="col-md-12" align="right">
                    	<button type="button" name="btn_new_movement" id="BTN_NEW_MOVEMENT" class="btn btn-primary" >New Movement</button>
               		</div>
               </div>
                <div class="row" style="height:25px;"></div>
                <div class="row">
                    <div class="col-md-9"></div>
                    <div class="col-md-3" align="right">
                         <button type="submit" name="btn_save_transaction" id="BTN_SAVE_TRANSACTION"  class="btn btn-info">Save</button>
                        <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
 </div>
@endsection