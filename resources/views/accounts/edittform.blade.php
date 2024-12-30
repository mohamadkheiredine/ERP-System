<?php
/***********************************************************
editform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 24, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Edit Form For Accounts
***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Clients Management"])

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
<script type="text/javascript" src="{{ url('js/modules/clients.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/saveclients.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Client</h3>
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
    <form name="frm_save_account" id="FORM_SAVE_ACCOUNT">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                      <input type="hidden" name="ca_id" id="CA_ID" value="{{ $account_info->ca_id }}" />
                      <input type="hidden" name="fk_account_owner_id" value="{{ $account_info->fk_account_owner_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Client Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    
                    <div class="row">
                              @if($crm_client_select_lead == 1)
                    	<div class="col-md-4">
                              <div class="form-group">
                                <label>Lead </label>
                                <select name="ca_lead_id" id="CA_LEAD_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Lead Source">
                                        <option value="">-- Select Lead --</option>
                                        @foreach( $lst_leads as $key => $lead_info )
                                          <option {{ $account_info->ca_lead_id ==  $lead_info->cl_id ? "selected" : "" }} value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</option>
                                        @endforeach
                                    
                                </select>
                            </div>
                        </div> 
                        @else
                        <input type="hidden" name="ca_lead_id" value="0" />
                        @endif
                    	 
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Name <span class="required"> * </span></label>
                                <input type="text" name="ca_account_name" id="CA_ACCOUNT_NAME" class="form-control" required="required" maxlength="255"  value="{{ $account_info->ca_account_name }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Client Code <span class="required"> * </span></label>
                                <input type="text" name="ca_account_code" id="CA_ACCOUNT_CODE" class="form-control" required="required" maxlength="255"  value="{{ $account_info->ca_account_code }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                              <div class="form-group">
                                <label>Contract Type </label>
                                <select name="ca_contract_type" id="CA_CONTRACT_TYPE" class="form-control form-select" data-control="select2" data-placeholder="Select Contract Type">
                                        <option value="0">-- Select Contract Type --</option>
                                        @foreach( $lst_contract_types as $key => $type_info )
                                          <option {{ $account_info->ca_contract_type ==  $type_info->ct_id ? "selected" : "" }} value="{{ $type_info->ct_id }}">{{ $type_info->ct_contract_type }}</option>
                                        @endforeach
                                    
                                </select>
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Email <span class="required"> * </span></label>
                                <input type="text" name="ca_account_email" id="CA_ACCOUNT_EMAIL" required="required"  class="form-control"  maxlength="255"  value="{{ $account_info->ca_account_email }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Mobile <span class="required"> * </span></label>
                                <input type="text" name="ca_account_mobile" required="required" id="CA_ACCOUNT_MOBILE" class="form-control"  maxlength="255"  value="{{ $account_info->ca_account_mobile }}" />
                            </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Nationality </label>
                                <select name="ca_nationality_id" id="CA_NATIONALITY_ID" required="required"  class="form-control form-select" data-control="select2" data-placeholder="Select Nationality">
                                        <option value="">-- Select Nationality --</option>
                                        @foreach( $lst_countries as $key => $country_info )
                                        <option {{ $account_info->ca_nationality_id == $country_info->id ? "selected" : "" }} value="{{ $country_info->id }}">{{ $country_info->code . " - " . $country_info->name }}</option>
                                        @endforeach
                                    
                                </select>
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">National ID <span class="required"> * </span></label>
                                <input type="text" name="ca_national_id" id="CA_NATIONAL_ID" required="required"  class="form-control"  maxlength="255"  value="{{ $account_info->ca_national_id }}" />
                            </div>
                        </div> 
                    </div>
                     <div class="row" style="height:50px;"></div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                                  <div class="text-inverse-primary bg-primary" style="height: 43px;text-align: center;padding-top: 10px;font-size: 15px;">Address</div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Area</label>
                                <input type="text" name="ca_billing_area" id="CA_BILLING_AREA" class="form-control" maxlength="255" value="{{ $account_info->ca_billing_area }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Region</label>
                                <input type="text" name="ca_billing_region" id="ca_billing_region" class="form-control" maxlength="255" value="{{ $account_info->ca_billing_region }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">City</label>
                                <input type="text" name="ca_billing_city" id="CA_BILLING_CITY" class="form-control" maxlength="255" value="{{ $account_info->ca_billing_city }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">State</label>
                                <input type="text" name="ca_billing_state" id="CA_BILLING_STATE" class="form-control" maxlength="255" value="{{ $account_info->ca_billing_state }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Street Name</label>
                                <input type="text" name="ca_billing_street" id="CA_BILLING_STREET" class="form-control" maxlength="255" value="{{ $account_info->ca_billing_street }}" />
                            </div>
                        </div>
                          <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">House</label>
                                <input type="text" name="ca_billing_house" id="CA_BILLING_HOUSE" class="form-control" maxlength="255" value="{{ $account_info->ca_billing_house }}" />
                            </div>
                        </div>
                          <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Full Address</label>
                                <textarea style="width:100%;height:250px;resize:none" name="ca_billing_address" id="CA_BILLING_ADDRESS" class="form-control">{{ $account_info->ca_billing_address }}</textarea>
                            </div>
                        </div>
                     </div>
                     <div class="row" style="height:50px;"></div>
                      
                     <div class="row">
                    	<div class="col-md-12" align="left">
                			<label>Client Description </label>
                        </div>
                    </div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                		 	<textarea class="form-control" id="CA_ACCOUNT_DESCRIPTION" name="ca_account_description" style="width:100%;height:250px;resize:none" >{{ $account_info->ca_account_description }}</textarea>
                        </div>
                    </div>
                    <div class="row" style="height:50px;"></div> 
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_account" id="BTN_SAVE_ACCOUNT"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>

@endsection