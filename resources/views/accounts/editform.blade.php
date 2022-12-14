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

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Edit Existing Client</h3>
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
             <form name="frm_save_account" id="FORM_SAVE_ACCOUNT">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                      <input type="hidden" name="ca_id" id="CA_ID" value="" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Client Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                    	<div class="col-md-4">
                              <div class="form-group">
                                <label>Account Responsible <span class="required"> * </span> </label>
                                <select class="bs-select form-control" name="fk_account_owner_id" id="FK_ACCOUNT_OWNER_ID" data-actions-box="true">
                                        <option value="">-- Select Owner --</option>
                                        @foreach( $lst_users as $key => $user_info )
                                          <option {{ $account_info->fk_account_owner_id  ==  $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                        @endforeach
                                    
                                </select>
                            </div>
                        </div> 
                    	<div class="col-md-4">
                              <div class="form-group">
                                <label>Lead </label>
                                <select class="bs-select form-control" name="ca_lead_id" id="CA_LEAD_ID" data-actions-box="true">
                                        <option value="">-- Select Lead --</option>
                                        @foreach( $lst_leads as $key => $lead_info )
                                          <option {{ $account_info->ca_lead_id ==  $lead_info->cl_id ? "selected" : "" }} value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</option>
                                        @endforeach
                                    
                                </select>
                            </div>
                        </div> 
                    	<div class="col-md-4">
                              <div class="form-group">
                                <label>Parent Account</label>
                                <select class="bs-select form-control" name="ca_parent_account" id="CA_PARENT_ACCOUNT" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach( $lst_accounts as $key => $acc_info )
                                          <option  {{ $account_info->ca_parent_account ==  $acc_info->ca_id ? "selected" : "" }} value="{{ $acc_info->ca_id }}">{{ $acc_info->ca_account_name }}</option>
                                        @endforeach
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <div class="form-group">
                         <label>Account Category<span class="required"> * </span> </label>
                           <select class="bs-select form-control" id="CA_ACCOUNT_CATEGORY"  required="required" name="ca_account_category">
                    			<option value="0">-- Select Category --</option>
                                @foreach($lst_client_categories as $index => $cc_info)
                                  <option {{ $account_info->ca_account_category ==  $cc_info->cc_id ? "selected" : "" }} value="{{ $cc_info->cc_id }}">{{  $cc_info->cc_category_ref . " - " . $cc_info->cc_category_name }}</option>
                                @endforeach
                            </select>
                        </div>
    					</div> 
                        <div class="col-md-4">
                        <div class="form-group">
                         <label>Account Type&nbsp;<span class="required"> * </span> </label>
                           <select class="bs-select form-control" id="CA_ACCOUNT_TYPE_ID" required="required" name="ca_account_type_id">
                    			<option value="0">-- Select Type --</option>
                                @foreach($lst_account_types as $index => $at_info)
                                  <option {{ $account_info->ca_account_type_id ==  $at_info->at_id ? "selected" : "" }} value="{{ $at_info->at_id }}">{{  $at_info->at_account_type }}</option>
                                @endforeach
                            </select>
                        </div>
    					</div> 
                        <div class="col-md-4">
                        <div class="form-group">
                         <label>Ownership&nbsp; </label>
                           <select class="bs-select form-control" id="CA_ACCOUNT_OWNERSHIP" required="required" name="ca_account_ownership">
                    			<option value="0">-- Select Ownership --</option>
                    			<option {{ $account_info->ca_account_ownership == 1 ? "selected" : "" }} value="1">Public</option>
                    			<option {{ $account_info->ca_account_ownership == 2 ? "selected" : "" }} value="2">Private</option>
                    			<option {{ $account_info->ca_account_ownership == 3 ? "selected" : "" }} value="3">Other</option>
                    			<option {{ $account_info->ca_account_ownership == 4 ? "selected" : "" }} value="4">Subsidiary</option>
                            </select>
                        </div>
    					</div> 
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Account Rating</label>
                                <input type="number" min="1" max="5" name="ca_account_rating" id="CA_ACCOUNT_RATING" class="form-control" value="{{ $account_info->ca_account_rating }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Account Name <span class="required"> * </span></label>
                                <input type="text" name="ca_account_name" id="CA_ACCOUNT_NAME" class="form-control" required="required" maxlength="255"  value="{{ $account_info->ca_account_name }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Name <span class="required"> * </span></label>
                                <input type="text" name="ca_company_name" id="CA_COMPANY_NAME" class="form-control" required="required" maxlength="255"  value="{{ $account_info->ca_company_name }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Account Phone <span class="required"> * </span></label>
                                <input type="text" name="ca_account_phone" id="CA_ACCOUNT_PHONE" class="form-control" required="required" maxlength="255"  value="{{ $account_info->ca_account_phone }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Account Website</label>
                                <input type="url" name="ca_account_website" id="CA_ACCOUNT_WEBSITE" class="form-control" maxlength="255"  value="{{ $account_info->ca_account_website }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Account Fax</label>
                                <input type="text" name="ca_account_fax" id="CA_ACCOUNT_FAX" class="form-control"  maxlength="255"  value="{{ $account_info->ca_account_fax }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Account Email <span class="required"> * </span></label>
                                <input type="text" name="ca_account_email" id="CA_ACCOUNT_EMAIL" class="form-control"  maxlength="255"  value="{{ $account_info->ca_account_email }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Account Mobile <span class="required"> * </span></label>
                                <input type="text" name="ca_account_mobile" id="CA_ACCOUNT_MOBILE" class="form-control"  maxlength="255"  value="{{ $account_info->ca_account_mobile }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Account Site</label>
                                <input type="text" name="ca_account_site" id="CA_ACCOUNT_SITE" class="form-control"  maxlength="255"  value="{{ $account_info->ca_account_site }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Account Number <span class="required"> * </span> </label>
                                <input type="text" name="ca_account_number" id="CA_ACCOUNT_NUMBER" class="form-control"  required="required"  maxlength="15"  value="{{ $account_info->ca_account_number }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Ticker Symbol</label>
                                <input type="text" name="ca_ticker_symbol" id="CA_TICKER_SYMBOL" class="form-control"   maxlength="25"  value="{{ $account_info->ca_ticker_symbol }}" />
                            </div>
                        </div>
                    	<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Number of Employees</label>
                                <input type="number" min="1" max="100" name="ca_nbr_of_employees" id="CA_NBR_OF_EMPLOYEES" class="form-control" value="{{ $account_info->ca_nbr_of_employees }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Annual Revenue</label>
                                <input type="text" name="ca_annual_revenue" id="CA_ANNUAL_REVENUE" class="form-control"  maxlength="255"  value="{{ $account_info->ca_annual_revenue }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">SIC Code</label>
                                <input type="text" name="ca_account_sic_code" id="CA_ACCOUNT_SIC_CODE" class="form-control"  maxlength="45"  value="{{ $account_info->ca_account_sic_code }}" />
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:50px;"></div>
                    <div class="row">
                    	<div class="col-md-12" align="left">
                		<h3 class="m--font-primary">Lead Image </h3>
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
                			<h3 class="m--font-primary">Billing Address </h3>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Country</label>
                                <select class="bs-select form-control" name="ca_billing_country" id="CA_BILLING_COUNTRY" data-actions-box="true">
                                        <option value="">-- Select Country --</option>
                                        @foreach ($lst_countries as $c_index => $c_info )
                                                <option {{ $account_info->ca_shipping_country == $c_info->id ? "selected" : "" }} value="{{ $c_info->id }}">{{ $c_info->name }}</option>
                                        @endforeach
                                </select>
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
                                <input type="text" name="ca_billing_state" id="CA_BILLING_STATE" class="form-control" maxlength="255" value="{{ $account_info->ca_billing_state  }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Zip Code</label>
                                <input type="text" name="ca_billing_code" id="CA_BILLING_CODE" class="form-control" maxlength="5" value="{{ $account_info->ca_billing_code  }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Street Name</label>
                                <input type="text" name="ca_billing_street" id="CA_BILLING_STREET" class="form-control" maxlength="255" value="{{ $account_info->ca_billing_street  }}" />
                            </div>
                        </div>
                     </div>
                     <div class="row" style="height:50px;"></div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                			<h3 class="m--font-primary">Shipping Address </h3>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Country</label>
                                <select class="bs-select form-control" name="ca_shipping_country" id="CA_SHIPPING_COUNTRY" data-actions-box="true">
                                        <option value="">-- Select Country --</option>
                                        @foreach ($lst_countries as $c_index => $c_info )
                                                <option {{ $account_info->ca_shipping_country == $c_info->id ? "selected" : "" }} value="{{ $c_info->id }}">{{ $c_info->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">City</label>
                                <input type="text" name="ca_shipping_city" id="CA_SHIPPING_CITY" class="form-control" maxlength="255" value="{{ $account_info->ca_shipping_city  }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">State</label>
                                <input type="text" name="ca_shipping_state" id="CA_SHIPPING_STATE" class="form-control" maxlength="255" value="{{ $account_info->ca_shipping_state }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Zip Code</label>
                                <input type="text" name="ca_shipping_code" id="CA_SHIPPING_CODE" class="form-control" maxlength="5" value="{{ $account_info->ca_shipping_code }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Street Name</label>
                                <input type="text" name="ca_shipping_street" id="CA_SHIPPING_STREET" class="form-control" maxlength="255" value="{{ $account_info->ca_shipping_street }}" />
                            </div>
                        </div>
                     </div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                			<h3 class="m--font-primary">Client Description </h3>
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