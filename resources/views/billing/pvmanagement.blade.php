<?php
/***********************************************************
paymentvouchers.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 21, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Billing Management"])

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
<script type="text/javascript" src="{{ url('js/modules/paymentvouchers.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/onepagerpv.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Payment Vouchers Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              		<li><a class="dropdown-item" data-action_type="DOWNLOAD" href="#">Download</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="col-md-12">
             <form name="frm_save_voucher" id="FORM_SAVE_VOUCHER">
                 <div class="card shadow-sm">
                        <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible">
                              <h3 class="card-title">Create New Payment Voucher</h3>
                              <div class="card-toolbar rotate-180">
                                  <i class="ki-duotone ki-down fs-1"></i>
                              </div>
                          </div>
                          <div id="kt_docs_card_collapsible" class="collapse show">
                              <div class="row" style="margin:10px;">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="pv_user_id" value="{{ session('user_id') }}" /> 
                        <input type="hidden" name="pv_id" value="0" /> 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong>Payment Voucher information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong>You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Voucher Ref </label>
                                    <input type="text" name="pv_code" id="PV_CODE" class="form-control"  maxlength="15"  value=""  />
                                </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Voucher Description</label>
                                    <input type="text" name="pv_voucher_label" id="PV_VOUCHER_LABEL" class="form-control"  maxlength="255"  value="" />
                                </div>
                        </div>  
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Voucher Date </label>
                                    <input type="text" name="pv_creation_date" id="PV_CREATION_DATE" class="form-control"  maxlength="50" readonly="readonly"  value="{{ date('Y-m-d') }}" />
                                </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                               <label> Account Payable <span class="required"> * </span></label>
                                <select  class="bs-select form-control" name="pv_account_payable" id="PV_ACCOUNT_PAYABLE" required="required" data-control="select2" data-placeholder="Select Payable">
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
                               <select  class="bs-select form-control" name="pv_account_receivable" id="PV_ACCOUNT_RECEIVABLE" required="required" data-control="select2" data-placeholder="Select Receivable">
                                      <option value="">-- Select Account --</option>
                                        @foreach ( $lst_chart_accounts as $key => $ca_info )
                                                <option value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Voucher Ammount </label>
                                    <input type="text"  name="pv_payment_amount" id="PV_PAYMENT_AMOUNT" class="form-control"  maxlength="50"  value="1" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency </label>
                                <select  class="bs-select form-control" name="pv_currency_id" id="PV_CURRENCY_ID"  required="required" data-control="select2" data-placeholder="Select Currency">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ session("company_currency") == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Second Currency </label>
                                <select  class="bs-select form-control" name="pv_sec_currency_id" id="PV_SEC_CURRENCY_ID" required="required" data-control="select2" data-placeholder="Select Second Currency">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{ session("secondary_currency") == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Exchange Rate </label>
                                    <input type="text"  name="pv_exchange_rate" id="PV_EXCHANGE_RATE" class="form-control" value="{{ session('cd_exchange_rate') }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Voucher Second Currency Ammount </label>
                                    <input type="text"  name="pv_amount_secondary_amount" id="PV_AMOUNT_SECONDARY_AMOUNT" class="form-control"  maxlength="50"  value="1" />
                                </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Voucher Notes</label>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="pv_voucher_description" id="PV_VOUCHER_DESCRIPTION" ></textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_voucher" id="BTN_SAVE_VOUCHER"  class="btn btn-info">Save</button>
                             <button type="button" name="btn_new_voucher" id="BTN_NEW_VOUCHER"  class="btn btn-success">New Voucher</button>
                             <button type="reset" id="BACK_FORM" name="back_form" class="btn btn-danger">Back</button>
                        </div>
                    </div>
                </div> 
                              </div> 
                            </div> 
                  </div>
            </form>
        </div>
        <div class="col-md-12">
        	<span id="hidden_fields">
    			<input type="hidden" name="page_number" value="1" />
    		</span>
    		<!--begin: Search Form -->
    		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
    			<div class="row align-items-center">
    				<div class="col-xl-12 order-2 order-xl-1">
    					<div class="form-group m-form__group row align-items-center">
    						<div class="col-md-4">
    							<div class="d-flex align-items-center">
									<!--begin::Input group-->
									<div class="position-relative w-md-400px me-md-2">
										<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
										<input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" />
									</div>
									<!--end::Input group-->
								</div>
    						</div>
    						<div class="col-md-4">
    							 <div class="form-group">
                                    <label> Payable Account : </label>
                                    <select  class="bs-select form-control" name="account_payable" id="ACCOUNT_PAYABLE" required="required" data-control="select2" data-placeholder="Select Account Payable">
                                            <option value="0"> --Select Account--</option>
                                            @foreach ( $lst_chart_accounts as $key => $account_info )
                                                    <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref . " - " . $account_info->aa_account_label }}</option>
                                            @endforeach
                                    </select>
                                </div>
                               <br/>
    						</div>
    						<div class="col-md-4">
    							 <div class="form-group">
                                    <label>Receivable Account </label>
                                    <select  class="bs-select form-control" name="account_receivable" id="ACCOUNT_RECEIVABLE" required="required" data-control="select2" data-placeholder="Select Account Receivable">
                                            <option value="0"> --Select Account--</option>
                                            @foreach ( $lst_chart_accounts as $key => $account_info )
                                                    <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref . " - " . $account_info->aa_account_label }}</option>
                                            @endforeach
                                    </select>
                                </div>
                              <br/>
    						</div>
    						<div class="col-md-4">
    							 <div class="form-group">
                                    <label> From Date </label><br/>
                                    <input type="text" name="start_date" id="START_DATE" value="" class="form-control" />
                                </div>
                                <br/>
    						</div>
    						<div class="col-md-4">
    							 <div class="form-group">
                                    <label> To Date </label><br/>
                                    <input type="text" name="end_date" id="END_DATE" value="" class="form-control" />
                                </div>
                               <br/>
    						</div>
    					</div>
    				</div>
    				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
    					
    				</div>
    			</div>
    		</div>
    		<!--end: Search Form -->
            <div class="row">
        		<div class="col-md-12 table-responsive">
        				<table class="table table-rounded table-striped border gy-7 gs-7" id="html_table" width="100%">
                		<thead>
                			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                				<th style="width:2px;white-space: nowrap;" title="#">#</th>
                				<th style="width:2px;white-space: nowrap;" title="Id"> ID </th>
                				<th title="Voucher Date"> Voucher Date </th>
                				<th title="Voucher Ref"> Voucher Ref </th>
                				<th title="Account Sender"> Account Sender </th>
                				<th title="Account Receiver"> Account Receiver </th>
                				<th title="Total Price"> Total Price </th>
                				<th title="currency"> Currency </th>
                			</tr>
                		</thead>
                		<tbody id="LstPaymentVouchers">
                		</tbody>
                	</table>
        		</div>
    		</div>
        	 <div class="row">
                 <div class="col-md-10" align="left">
                    <ul id="VouchersPagination" class="pagination-sm"></ul>
                 </div>
                 <div class="col-md-2" align="right"></div>
             </div> 
        </div>
	</div>
</div>
 
@endsection