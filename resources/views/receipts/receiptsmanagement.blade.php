<?php

/***********************************************************
receiptsmanagement
Product : titanerp
Version : 1.0
Release : 1
Date Created : Oct 26, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Receipts Management"])

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
<script type="text/javascript" src="{{ url('js/modules/receipts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/onereceipts.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Receipts Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item quickactions" data-action_type="PRINT" href="#">Print</a></li>
                    <li><a class="dropdown-item quickactions" data-action_type="EXPORT_AS_CSV" href="#">Export As CSV</a></li>
                    <li><a class="dropdown-item quickactions" data-action_type="IMPORT" href="#">Import</a></li>
                    <li><a class="dropdown-item quickactions" data-action_type="DOWNLOAD_TEMPLATE" href="#">Download Import Template</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <span id="hidden_fields">
            <input type="hidden" name="page_number" value="1" />
        </span>
        <div class="col-md-12">
            <form name="form_save_receipt" id="FORM_SAVE_RECEIPT">
                 <div class="card shadow-sm">
                        <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible">
                              <h3 class="card-title">Create New Receipt</h3>
                              <div class="card-toolbar rotate-180">
                                  <i class="ki-duotone ki-down fs-1"></i>
                              </div>
                          </div>
                          <div id="kt_docs_card_collapsible" class="collapse show">
                          <div class="row" style="margin:10px;">
                              <div class="col-md-12">
                                  <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="br_id" value="0" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Receipt information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong>You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Receipt Ref </label>
                                    <input type="text" name="br_receipt_number" id="BR_RECEIPT_NUMBER" class="form-control"  maxlength="15"  value="" readonly="readonly" />
                                </div>
                        </div>
                        <div class="col-md-4" >
                             <div class="form-group">
                                <label class="control-label"> Source Account </label><br/>
                                 <select id="BR_ACCOUNT_FROM" name="br_account_from" class="form-control form-select" data-control="select2" data-placeholder="Select Source Account">
                        			<option value="0">-- Select Account --</option>
                                    @foreach($lst_accounts as $index => $acc_info)
                                      <option value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account }}&nbsp;-&nbsp;{{ $acc_info->aa_account_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" >
                             <div class="form-group">
                                <label class="control-label"> Received To <span class="required"> * </span></label><br/>
                                 <select required="required" id="BR_ACCOUNT_ID" name="br_account_id" class="form-control form-select" data-control="select2" data-placeholder="Select Account">
                        			<option value="0">-- Select Account --</option>
                                    @foreach($lst_accounts as $index => $acc_info)
                                      <option value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account }}&nbsp;-&nbsp;{{ $acc_info->aa_account_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                            		<label class="control-label">Client</label><br/>
                                        <select id="BR_CLIENT_ID" name="br_client_id" class="form-control form-select" data-control="select2" data-placeholder="Select Client">
                            			<option value="0">-- Select Client --</option>
                                        @foreach($lst_clients as $index => $client_info)
                                          <option value="{{ $client_info->ca_id }}">( {{ $client_info->ca_account_code }}) &nbsp;-&nbsp;{{ $client_info->ca_account_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
			</div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Receipt Description </label><br/>
                                    <input type="text" name="br_receipt_label" id="BR_RECEIPT_LABEL" class="form-control"  maxlength="255"  value="" />
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Receipt Date </label><br/>
                                    <input type="text" name="br_receipt_date" id="BR_RECEIPT_DATE" class="form-control"  maxlength="50" readonly="readonly"  value="{{ date('d/m/Y') }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Payment Type</label><br/>
                                <select required="required"  id="FK_PAYMENT_TYPE" name="fk_payment_type" class="form-control form-select" data-control="select2" data-placeholder="Select Payment Type">
                        			<option value="">-- Select Payment Type --</option>
                                    @foreach($lst_payment_types as $index => $paytype_info)
                                      <option value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Receipt Amount </label><br/>
                                    <input type="text" name="br_payment_value" id="BR_PAYMENT_VALUE" class="form-control"  maxlength="10"  value="" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency <span class="required"> * </span></label><br/>
                                <select name="br_receipt_currency" required="required" id="BR_RECEIPT_CURRENCY" class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option {{  $currency_info->cc_id == session("company_currency") ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Exchange Rate </label><br/>
                                    <input type="number" min="0" max="9999999" step="1.0" name="br_exchange_rate" id="BR_EXCHANGE_RATE" class="form-control"  maxlength="15"  value="{{ session('cd_exchange_rate') }}" />
                                </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> Second Currency</label><br/>
                                <select name="br_second_currency_id" id="BR_SECOND_CURRENCY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                        <option value="">-- Select Currency --</option>
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                                <option  {{ $currency_info->cc_id == session("secondary_currency") ? "selected" : "" }}  value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Voucher Second Currency Ammount </label>
                                    <input type="text"  name="br_amount_secondary_amount" id="BR_AMOUNT_SECONDARY_AMOUNT" class="form-control"  maxlength="50"  value="1" />
                                </div>
                        </div>
                        <div class="col-md-4">
                          <label> Receipt Paid </label>
                           <div class="m-form__group form-group row">
								<div class="col-12">
                                                                             <br/>
                                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                                          <input class="form-check-input" type="checkbox" name="br_receipt_paid" checked="checked" value="1"  />
                                                                          <span class="form-check-label fw-semibold text-muted">
                                                                            Receipt Paid
                                                                          </span>
                                                                      </label>  
								</div>
								</div>
                                                        </div> 
                                                        <div class="col-md-12">
                                                             <div class="form-group">
                                                                <label class="control-label">Receipt Notes</label>
                                                                <textarea style="width:100%;height: 250px;" class="form-control" name="br_receipt_note" id="BR_RECEIPT_NOTE" ></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   <div class="row" style="height:5px;"></div>
                                                    <div class="row">
                                                        <div class="col-md-9"></div>
                                                        <div class="col-md-3" align="right">
                                                             <button type="submit" name="btn_save_receipt" id="BTN_SAVE_RECEIPT"  class="btn btn-info">Save</button>
                                                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                                                        </div>
                                                    </div>
                                                </div>
                              </div>
                              <div style="height:50px" class="col-md-12"></div>
                              <div style="text-align: right" class="col-md-12">
                                  <button name="btn_save_receipt" class="btn btn-primary" type="submit">Save Info</button>
                                  <button name="btn_new_receipt" class="btn btn-success" type="submit">New Receipt</button>
                                  <button name="btn_reset" class="btn btn-danger" type="reset">Reset</button>
                              </div>
                          </div> 
                      </div> 
                  </div>
            </form>
        </div>
         <div class="col-md-12">
            <div class="row align-items-center">
                <div class="col-xl-12 order-2 order-xl-1">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <!--begin::Input group-->
                                <div class="position-relative w-md-400px me-md-2">
                                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" tabindex="1" />
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                        <div class="col-md-4">
                           <select id="BR_RECEIPT_CLIENT" name="br_receipt_client" class="form-control form-select" data-control="select2" data-placeholder="Select Client">
                                        <option value="0">-- Select Clients --</option>
                                @foreach($lst_clients as $index => $client_info)
                                  <option value="{{ $client_info->ca_id }}">( {{ $client_info->ca_account_code }} ) &nbsp;-&nbsp;{{ $client_info->ca_account_name }}</option>
                                @endforeach
                            </select>
                        </div>  
                        <div class="col-md-4">
                            	 <div class="form-group">
                                <input type="text" placeholder=" From Date" name="start_date" id="START_DATE" value="" class="form-control" />
                            </div>
                        </div> 
                        <div class="col-md-12" style="height:10px;"></div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <input type="text"  placeholder="To Date" name="end_date" id="END_DATE" value="" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-12">&nbsp;</div>
                        <div class="col-md-12" style="text-align: right">
                        </div>
                        <div class="col-md-12" style="height:10px;"></div>
                    </div>
                </div>

            </div>
        </div> 
         <div class="row">
    		<div class="col-md-12">
    		 <div class="table-responsive">
        		 <table class="table table-rounded table-striped border gy-7 gs-7">
    				<thead>
    					<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
    						<th style="width:2px;">#</th>
    						<th style="width:2px;">ID</th>
    						<th>Receipt Code</th>
    						<th>Receipt Label</th>
    						<th>Receipt Note</th>
    						<th>Receipt Date</th>
    						<th>Receipt Amount</th> 
    						<th style="width:4px;white-space: nowrap;text-align: center">Download</th>
    						<th style="width:4px;white-space: nowrap;text-align: center">Delete</th>
    					</tr>
    				</thead>
    				<tbody  class="LstOneReceiptsGrid"></tbody>
    			</table>
    		 </div>
    			
    		</div>
    	</div>
    	 <div class="row">
             <div class="col-md-10" align="left">
                <ul id="ReceiptsPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-2" align="right"></div>
         </div>
		<div class="row">
			<div class="col-md-12 order-1 order-xl-2 align-right">
					<a href="{{ url('billing/receipts/addform') }}" class="btn btn-info">
						<span>
							<i class="flaticon-tabs"></i>
							<span>
								New Receipt
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
		</div>
    </div>
</div>

 
@endsection