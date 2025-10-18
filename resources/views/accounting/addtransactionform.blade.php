<?php
/***********************************************************
addtransactionform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Add Transaction Form
***********************************************************/

?>
@extends('layouts.layout',['page_title' => "Accounting Management > Add New Transaction"])

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
<script type="text/javascript" src="{{ url('js/modules/transactions.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/savetransaction.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add New Category</h3>
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
            <form name="frm_save_transaction" id="FORM_SAVE_TRANSACTION">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Transaction Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Transaction Date </label>
                                <input type="text" name="at_transaction_date" id="AT_TRANSACTION_DATE" class="form-control"  maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Journal </label>
                                <select class="bs-select form-control" name="fk_acc_journal_id" id="FK_ACC_JOURNAL_ID" data-actions-box="true">
                                    <option value="0">Accounting Journal</option>
                                    @foreach ( $lst_journals as $key => $journal_info )
                                        <option value="{{ $journal_info->aj_id }}">{{ $journal_info->aj_journal_code . " - " . $journal_info->aj_journal_label  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency </label>
                                <select class="bs-select form-control" name="at_currency_id" id="AT_CURRENCY_ID" data-actions-box="true">
                                    <option value="0">Select Currency</option>
                                    @foreach ( $lst_currencies as $key => $curr_info )
                                        <option value="{{ $curr_info->cc_id }}">{{ $curr_info->cc_currency_code . " - " . $curr_info->cc_currency_name  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Accounting Doc. <span class="required"> * </span></label>
                                <input type="text" name="at_accounting_doc" id="AT_ACCOUNTING_DOC" class="form-control" maxlength="255"  value="" />
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_transaction" id="BTN_SAVE_TRANSACTION"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
