<?php
/***********************************************************
adddebitnote.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 3, 2021
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
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/debitnotes.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/savedebitnotes.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Add New Debit Note</h3>
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
             <form name="frm_save_debitnotes" id="FORM_SAVE_DEBITNOTES">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="dn_created_by" value="{{ session('user_id') }}" /> 
                        <input type="hidden" name="dn_debit_code" value="{{ $dn_code }}" /> 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong>Debit Notes information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong>You have some form errors. Please check below.
            			</div>
                    <div class="row"> 
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Debit Code</label>
                                    <input type="text" name="dn_code" id="DN_CODE" class="form-control"  maxlength="255" readonly="readonly"  value="{{ $dn_code }}" />
                                </div>
                        </div>   
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Debit Label</label>
                                    <input type="text" name="dn_debit_label" id="DN_DEBIT_LABEL" class="form-control"  maxlength="255"  value="" />
                                </div>
                        </div>   
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Debit Date </label>
                                    <input type="text" name="dn_creation_date" id="DN_CREATION_DATE" class="form-control"  maxlength="255"  value="" />
                                </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Payable <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="dn_account_sender" id="DN_ACCOUNT_SENDER" required="required" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div> 
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Receivable <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="dn_account_receivable" id="DN_ACCOUNT_RECEIVABLE" required="required" data-actions-box="true">
                                      <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Debit Ammount </label>
                                    <input type="text"  name="dn_debit_value" id="DN_DEBIT_VALUE" class="form-control"  maxlength="50"  value="1" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency </label>
                                <select class="bs-select form-control" name="dn_debit_currency" id="DN_DEBIT_CURRENCY" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Second Currency </label>
                                <select class="bs-select form-control" name="dn_second_currency" id="DN_SECOND_CURRENCY" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Exchange Rate </label>
                                    <input type="text"  name="dn_exchange_rate" id="DN_EXCHANGE_RATE" class="form-control" value="0" />
                                </div>
                        </div> 
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Debit Notes</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="dn_debit_notes" id="DN_DEBIT_NOTES" ></textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_dnote" id="BTN_SAVE_DNOTE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>
@endsection