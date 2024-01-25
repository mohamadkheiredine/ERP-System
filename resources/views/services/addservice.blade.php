<?php
/***********************************************************
addservice.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 12, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



?>
@extends('layouts.layout',['page_title' => "Services Management"])
@section('plugins')
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/services.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/saveservice.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Service</h3>
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
    <form name="frm_save_service" id="FORM_SAVE_SERVICE">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Service Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Service Title <span class="required"> * </span></label>
                                <input type="text" name="cs_service_title" id="CS_SERVICE_TITLE" class="form-control" required="required" maxlength="100"  value="" />
                            </div>
                        </div>
                         <div class="col-md-4" style="display: none">
                             <div class="form-group">
                                <label class="control-label">Service Cost</label>
                                <input type="text" name="cs_cost_per_hour" id="CS_COSTPER_HOUR" class="form-control" maxlength="100"  value="0" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Service Category</label>
                                <select class="bs-select form-control" name="fk_category_id" id="FK_CATEGORY_ID" data-actions-box="true">
                                        <option value="">No Category</option>
                                        @foreach ( $lst_service_categories as $key => $category_info )
                                                <option value="{{ $category_info->sc_id }}">{{ $category_info->sc_category_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Sales Accounting&nbsp;<a href="#" id="ADD_SALES_ACCOUNT" style="text-decoration: none;"  data-dropdown_name="cs_sale_accounting_code" ><i class="flaticon-add-circular-button"></i></a>&nbsp;<span class="required"> * </span></label>
                                <select class="bs-select form-control" name="cs_sale_accounting_code" id="P_SALE_ACCOUNTING_CODE" required="required" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                <option value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> Purchase Accounting&nbsp;<a href="#" id="ADD_PURCHASE_ACCOUNT" style="text-decoration: none;"  data-dropdown_name="cs_purchase_accounting_code" ><i class="flaticon-add-circular-button"></i></a>&nbsp;<span class="required"> * </span></label>
                                <select class="bs-select form-control" name="cs_purchase_accounting_code" id="P_PURCHASE_ACCOUNTING_CODE" required="required" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                <option value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Validate Payment Type On invoice record</label><br/>
                                <input type="checkbox" name="cs_validate_payment_type"  id="CS_VALIDATE_PAYMENT_TYPE" class="form-control ValidatePaymentType" value="1" />
                            </div>
                        </div>
                        <div class="col-md-4" style="display: none">
                            <div class="form-group">
                                <label> Currency </label>
                                <select class="bs-select form-control" name="cs_currency_id" id="CS_CURRENCY_ID" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ session("company_currency") == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Service Description </label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CS_SERVICE_DESCRIPTION"  class="form-control" name="cs_service_description"  cols=""></textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_service" id="BTN_SAVE_SERVICE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
 
<!--begin:: Account Modal-->
<div class="modal fade" id="AccountAccounting" tabindex="-1" role="dialog" aria-labelledby="AcctAccountingModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="AcctAccountingModalLabel">
					Account Accounting
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_acc_account" id="FRM_ACC_ACCOUNT" action="#" > 
				   <span id="hidden_fields">
    				   {!! csrf_field() !!}
    				   <input type="hidden" name="dropdown_name" value="" />
				   </span>
				 	<div class="row">
				 		<div class="col-md-12">
				 			<div class="form-group">
                                <label class="control-label"> Parent Account </label><br/>
                                 <select class="bs-select form-control" name="aa_parent_account" style="width:100%" id="AA_PARENT_ACCOUNT" data-actions-box="true">
                                        <option value="">Customer Account</option>
                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                <option data-account_id="{{ $acc_info->aa_account }}" value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label  }}</option>
                                        @endforeach
                                </select>
                            </div>
				 		</div>
				 		 <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Account Label <span class="required"> * </span></label><br/>
                                <input type="text" name="aa_account_label" style="width:100%" id="AA_ACCOUNT_LABEL" class="form-control" maxlength="255"  value="" />
                            </div>
                        </div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">
				<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
					Close
				</button>
				<button type="button" name="btn_add_account" id="BTN_ADD_ACCOUNT" class="btn btn-primary">
					Add Account
				</button>
			</div>
		</div>
	</div>
</div>
<!--end:: Accounting Modal-->
@endsection