<?php
/***********************************************************
editservice.blade.php
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
<script type="text/javascript" src="{{ url('js/modules/services.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/saveservice.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Edit Existing Service</h3>
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
             <form name="frm_save_service" id="FORM_SAVE_SERVICE">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                        <input type="hidden" name="cs_id" id="CS_ID" value="{{ $services->cs_id }}" />
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
                                <input type="text" name="cs_service_title" id="CS_SERVICE_TITLE" class="form-control" required="required" maxlength="100"  value="{{ $services->cs_service_title }}" />
                            </div>
                        </div>
                        <div class="col-md-4" style="display: none">
                             <div class="form-group">
                                <label class="control-label">Service Cost</label>
                                <input type="text" name="cs_cost_per_hour" id="CS_COSTPER_HOUR" class="form-control" maxlength="100"  value="{{ $services->cs_cost_per_hour }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Service Category</label>
                                <select class="bs-select form-control" name="fk_category_id" id="FK_CATEGORY_ID" data-actions-box="true">
                                        <option value="">No Category</option>
                                        @foreach ( $lst_service_categories as $key => $category_info )
                                                <option {{ $services->fk_category_id == $category_info->sc_id ? "selected" : "" }} value="{{ $category_info->sc_id }}">{{ $category_info->sc_category_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> Sales Accounting &nbsp;<a href="#" id="ADD_SALES_ACCOUNT" style="text-decoration: none;"   data-dropdown_name="cs_sale_accounting_code" ><i class="flaticon-add-circular-button"></i></a>&nbsp; <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="cs_sale_accounting_code" id="P_SALE_ACCOUNTING_CODE" required="required" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                <option {{ $services->cs_sale_accounting_code == $acc_info->aa_id ? "selected" : "" }} value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Purchase Accounting &nbsp;<a href="#" id="ADD_PURCHASE_ACCOUNT" style="text-decoration: none;"  data-dropdown_name="cs_purchase_accounting_code" ><i class="flaticon-add-circular-button"></i></a>&nbsp; <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="cs_purchase_accounting_code" id="P_PURCHASE_ACCOUNTING_CODE" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                <option {{ $services->cs_purchase_accounting_code == $acc_info->aa_id ? "selected" : "" }} value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Validate Payment Type On invoice record</label><br/>
                                <input type="checkbox" {{  $services->cs_validate_payment_type == 1 ? "checked='checked'" : "" }} name="cs_validate_payment_type"  id="CS_VALIDATE_PAYMENT_TYPE" class="form-control ValidatePaymentType" value="1" />
                            </div>
                        </div>
                        <div class="col-md-4" style="display: none">
                            <div class="form-group">
                                <label> Currency </label>
                                <select class="bs-select form-control" name="cs_currency_id" id="CS_CURRENCY_ID" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ $services->cs_currency_id == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Service Description </label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CS_SERVICE_DESCRIPTION"  class="form-control" name="cs_service_description"  cols="">{{ $services->cs_service_description }}</textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                   <div class="row">
                   		<div class="col-md-12">
                       		<table class="table m-table m-table--head-bg-success">
    							<thead>
    								<tr>
    									<th>
    										#
    									</th><th>
    										id
    									</th>
    									<th>
    										Payment Type
    									</th>
    									<th>
    										sales Account
    									</th>
    									<th>
    										Purchase Account
    									</th>
    									<th>Edit</th>
    									<th>delete</th>
    								</tr>
    							</thead>
    							<tbody class="PaymentTypeAccounting"></tbody>
    						</table>
                   		</div>
                   		<div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="button" name="btn_add_payment_type" id="BTN_ADD_PAYMENT_TYPE"  class="btn btn-success">Add Payment Type</button> 
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
				   </span>
				   
				 	<div class="row">
				 		<div class="col-md-12">
				 			<div class="form-group">
                                <label class="control-label"> Parent Account </label><br/>
                                 <select class="bs-select form-control" name="aa_parent_account" style="width:100%" id="AA_PARENT_ACCOUNT" data-actions-box="true">
                                        <option value="">Customer Account</option>
                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                <option data-account_id="{{ $acc_info->aa_account }}"  value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label  }}</option>
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

<div class="modal fade" id="PaymentType" tabindex="-1" role="dialog" aria-labelledby="SrvPaymentTypeModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="SrvPaymentTypeModalLabel">
					Payment Type
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_srv_paymenttype" id="FRM_SRV_PAYMENTTYPE" action="#" > 
				   <span id="hidden_fields">
				   {!! csrf_field() !!}
				   <input type="hidden" name="st_id" value="" />
				   </span>
				   
				 	<div class="row">
				 		<div class="col-md-12">
				 			<div class="form-group">
                                <label class="control-label"> Payment Type </label><br/>
                                 <select class="bs-select form-control" name="pt_payment_type" style="width:100%" id="PT_PAYMENT_TYPE" data-actions-box="true">
                                        <option value="">Payment Type</option>
                                        @foreach ( $lst_payment_types as $key => $pt_info )
                                                <option data-pt_id="{{ $pt_info->pt_id }}"  value="{{ $pt_info->pt_id }}">{{ $pt_info->pt_payment_type  }}</option>
                                        @endforeach
                                </select>
                            </div>
				 		</div>
				 		<div class="col-md-12">
				 			<div class="form-group">
                                <label class="control-label"> Income Account </label><br/>
                                 <select class="bs-select form-control" name="st_account_income_id" style="width:100%" id="ST_ACCOUNT_INCOME_ID" data-actions-box="true">
                                        <option value="">Incoming Account</option>
                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                <option data-account_id="{{ $acc_info->aa_account }}"  value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label  }}</option>
                                        @endforeach
                                </select>
                            </div>
				 		</div>
				 		<div class="col-md-12">
				 			<div class="form-group">
                                <label class="control-label"> Purchase Account </label><br/>
                                 <select class="bs-select form-control" name="st_account_purchase_Id" style="width:100%" id="ST_ACCOUNT_PURCHASE_ID" data-actions-box="true">
                                        <option value="">Purchase Account</option>
                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                <option data-account_id="{{ $acc_info->aa_account }}"  value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label  }}</option>
                                        @endforeach
                                </select>
                            </div>
				 		</div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">
				<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
					Close
				</button>
				<button type="button" name="btn_save_pt" id="BTN_SAVE_PT" class="btn btn-primary">
					Save Payment Type
				</button>
			</div>
		</div>
	</div>
</div>
@endsection