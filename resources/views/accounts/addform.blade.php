<?php
/***********************************************************
addform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 24, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Add Form for the Accounts
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
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/clients.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/saveclients.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add Client</h3>
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
                      <input type="hidden" name="fk_account_owner_id" value="{{ session('user_id') }}" />
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
                                        <option value="0">-- Select Lead --</option>
                                        @foreach( $lst_leads as $key => $lead_info )
                                          <option value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</option>
                                        @endforeach

                                </select>
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="ca_lead_id" value="0" />
                        @endif
                    	<div class="col-md-4">
                              <div class="form-group">
                                <label>Parent Account</label>
                                <select name="ca_parent_account" id="CA_PARENT_ACCOUNT" class="form-control form-select" data-control="select2" data-placeholder="Select Parent Account">
                                        <option value="0">-- Select Account --</option>
                                        @foreach( $lst_accounts as $key => $acc_info )
                                          <option value="{{ $acc_info->ca_id }}">{{ $acc_info->ca_account_name }}</option>
                                        @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <div class="form-group">
                         <label> Category<span class="required"> * </span> </label>
                               <select name="ca_account_category" id="CA_ACCOUNT_CATEGORY" class="form-control form-select" data-control="select2" data-placeholder="Select Account Category">
                    			<option value="0">-- Select Category --</option>
                                @foreach($lst_client_categories as $index => $cc_info)
                                  <option value="{{ $cc_info->cc_id }}">{{  $cc_info->cc_category_ref . " - " . $cc_info->cc_category_name }}</option>
                                @endforeach
                            </select>
                        </div>
    					</div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Name <span class="required"> * </span></label>
                                <input type="text" name="ca_account_name" id="CA_ACCOUNT_NAME" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Name <span class="required"> * </span></label>
                                <input type="text" name="ca_company_name" id="CA_COMPANY_NAME" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Phone <span class="required"> * </span></label>
                                <input type="text" name="ca_account_phone" id="CA_ACCOUNT_PHONE" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Website</label>
                                <input type="url" name="ca_account_website" id="CA_ACCOUNT_WEBSITE" class="form-control" maxlength="255"  value="" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Fax</label>
                                <input type="text" name="ca_account_fax" id="CA_ACCOUNT_FAX" class="form-control"  maxlength="255"  value="" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Email</label>
                                <input type="text" name="ca_account_email" id="CA_ACCOUNT_EMAIL" class="form-control"  maxlength="255"  value="" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Mobile <span class="required"> * </span></label>
                                <input type="text" name="ca_account_mobile" id="CA_ACCOUNT_MOBILE" class="form-control"  maxlength="255"  value="" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Site</label>
                                <input type="text" name="ca_account_site" id="CA_ACCOUNT_SITE" class="form-control"  maxlength="255"  value="" />
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:50px;"></div>
                    <div class="row">
                    	<div class="col-md-12" align="left">
                		<label>Account Image </label>
                        	</div>
                             <div class="col-md-4">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img id="AVATAR_PIC" width="100" src="{{ url('images/NoImageAvailable.jpg') }}" alt="" /> </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>

                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="clearfix margin-top-10">
                                    <div>
                                        <span class="btn default btn-file" style="text-align: left;">
                                            <span class="fileinput-new"> Select image </span><br/>
                                            <input type="file" name="ca_avatar_pic" id="CA_AVATAR_PIC" /> </span>
                                    </div>
                                    <br>
                                    <span class="label label-danger"> NOTE! </span><br><br>
                                    <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                </div>
                            </div>
                    </div>
                     <div class="row" style="height:50px;"></div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                			<label>Billing Address </label>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Country</label>
                                <select name="ca_billing_country" id="CA_BILLING_COUNTRY" class="form-control form-select" data-control="select2" data-placeholder="Select Billing Country">
                                        <option value="">-- Select Country --</option>
                                        @foreach ($lst_countries as $c_index => $c_info )
                                                <option value="{{ $c_info->id }}">{{ $c_info->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">City</label>
                                <input type="text" name="ca_billing_city" id="CA_BILLING_CITY" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">State</label>
                                <input type="text" name="ca_billing_state" id="CA_BILLING_STATE" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Zip Code</label>
                                <input type="text" name="ca_billing_code" id="CA_BILLING_CODE" class="form-control" maxlength="5" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Street Name</label>
                                <input type="text" name="ca_billing_street" id="CA_BILLING_STREET" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                     </div>
                     <div class="row" style="height:50px;"></div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                			<label>Shipping Address </label>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Country</label>
                                <select name="ca_shipping_country" id="CA_SHIPPING_COUNTRY" class="form-control form-select" data-control="select2" data-placeholder="Select Shipping Country">
                                        <option value="">-- Select Country --</option>
                                        @foreach ($lst_countries as $c_index => $c_info )
                                                <option value="{{ $c_info->id }}">{{ $c_info->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">City</label>
                                <input type="text" name="ca_shipping_city" id="CA_SHIPPING_CITY" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">State</label>
                                <input type="text" name="ca_shipping_state" id="CA_SHIPPING_STATE" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Zip Code</label>
                                <input type="text" name="ca_shipping_code" id="CA_SHIPPING_CODE" class="form-control" maxlength="5" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Street Name</label>
                                <input type="text" name="ca_shipping_street" id="CA_SHIPPING_STREET" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                     </div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                			<label>Client Description </label>
                        </div>
                    </div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                		 	<textarea class="form-control" id="CA_ACCOUNT_DESCRIPTION" name="ca_account_description" style="width:100%;height:250px;resize:none" ></textarea>
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
