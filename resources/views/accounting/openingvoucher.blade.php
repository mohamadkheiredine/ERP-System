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
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Opening Voucher</h3>
			</div>
		</div>
		<div class="m-portlet__head-tools">
			<ul class="m-portlet__nav">
				<li class="m-portlet__nav-item">
					<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
						<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
							<i class="la la-ellipsis-h m--font-brand"></i>
						</a>
						<div class="m-dropdown__wrapper">
							<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
							<div class="m-dropdown__inner">
								<div class="m-dropdown__body">
									<div class="m-dropdown__content">
										<ul class="m-nav">
											<li class="m-nav__section m-nav__section--first">
												<span class="m-nav__section-text">
													Quick Actions
												</span>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="m-portlet__body">
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