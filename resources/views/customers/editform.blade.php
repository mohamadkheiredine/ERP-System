<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

{
    $image_src_url  = url('/')."/".Config::get('constants.CUSTOMERS_PATH').$customer_info->ic_image_base_src.$customer_info->ic_image_file_name.".".$customer_info->ic_image_extension;
    $image_src_path = public_path(). "/" .Config::get('constants.CUSTOMERS_PATH').$customer_info->ic_image_base_src.$customer_info->ic_image_file_name.".".$customer_info->ic_image_extension;
    if(strlen($customer_info->ic_image_base_src) > 0 ){
        $img_src = $image_src_url;
    }else{
        $img_src = url('images/NoImageAvailable.jpg');
    }
    
}

?>



@extends('layouts.layout',['page_title' => "Customers Management"])

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
<script type="text/javascript" src="{{ url('js/modules/customers.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/inventory/savecustomers.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Existing Customer</h3>
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
    <form name="frm_save_customer" id="FORM_SAVE_CUSTOMER">
                <div class="form-body">
                     <span id="hidden_fields">
                      	{!! csrf_field() !!}
                      	<input type="hidden" name="ic_id" id="IC_ID" value="{{ $customer_info->ic_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Customer Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
            		<div class="row" style="height:50px;"></div>
                    <div class="row">
                    	<div class="col-md-12" align="left">
                		<label>Customer Logo </label>
                        	</div>
                             <div class="col-md-4">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img id="CUSTOMER_LOGO_PIC" width="100" src="{{ $img_src }}" alt="" /> </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
        
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="clearfix margin-top-10">
                                    <div>
                                        <span class="btn default btn-file" style="text-align: left;">
                                            <span class="fileinput-new"> Select image </span><br/>
                                            <input type="file" name="ic_avatar_pic" id="IC_AVATAR_PIC" /> </span>
                                    </div>
                                    <br>
                                    <span class="label label-danger"> NOTE! </span><br><br>
                                    <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                </div>
                            </div>
                    </div>
                    <div class="row" style="height:50px;"></div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Customer Code <span class="required"> * </span></label>
                                    <input type="text" name="ic_customer_code" readonly="readonly" id="IC_CUSTOMER_CODE" class="form-control" required="required" maxlength="15"  value="{{ ($customer_code != '') ? $customer_code : $customer_info->ic_customer_code }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Customer Name <span class="required"> * </span></label>
                                <input type="text" name="ic_customer_name" id="IC_CUSTOMER_NAME" class="form-control" required="required" maxlength="255"  value="{{ $customer_info->ic_customer_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Customer Country </label>
                                 <select class="bs-select form-control" name="ic_customer_country" id="IC_CUSTOMER_COUNTRY" data-actions-box="true">
                                        @foreach ( $lst_countries as $key => $count_info )
                                                <option {{ $customer_info->ic_customer_country == $count_info->id ? "selected" : "" }} value="{{ $count_info->id }}">{{ $count_info->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> Customer Account <a href="#" id="ADD_NEW_ACCOUNT" data-toggle="modal" data-target="#AccountAccounting" ><i class="flaticon-add-circular-button"></i></a></label>
                                <select class="bs-select form-control" name="ic_account_number" required="required" id="IC_ACCOUNT_NUMBER" data-actions-box="true">
                                        <option value="">Customer Account</option>
                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                <option {{ $customer_info->ic_account_number == $acc_info->aa_id ? "selected" : "" }} value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label  }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Customer Website </label>
                                <input type="url" name="ic_customer_website" id="IC_CUSTOMER_WEBSITE" class="form-control" maxlength="255"  value="{{ $customer_info->ic_customer_website }}" />
                            </div>
                        </div> 
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Customer Email</label>
                                <input type="email" name="ic_customer_email" id="IC_CUSTOMER_EMAIL" class="form-control" maxlength="255"  value="{{ $customer_info->ic_customer_email }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Customer mobile </label>
                                <input type="tel" name="ic_customer_mobile" id="IC_CUSTOMER_MOBILE" class="form-control"   maxlength="255"  value="{{ $customer_info->ic_customer_mobile }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Customer Phone </label>
                                <input type="tel" name="ic_customer_phone" id="IC_CUSTOMER_PHONE" class="form-control" maxlength="255"  value="{{ $customer_info->ic_customer_phone }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Default POS Customer</label><br/>
                               	 <input data-switch="true" type="checkbox"  name="ic_default_customer" id="IC_DEFAULT_CUSTOMER" {{ $customer_info->ic_default_customer == 1  ? 'checked="checked"' : '' }} value="1" data-on-text="Yes" data-handle-width="50" data-off-text="No" data-on-color="success" />
                            </div>
                        </div>
                        <div class="col-md-8">
                             <div class="form-group">
                                <label class="control-label">Customer Address </label>
                                <textarea style="width:100%;height:250px;resize:none" id="IC_CUSTOMER_ADDRESS"  class="form-control" name="ic_customer_address"  cols="">{{ $customer_info->ic_customer_address }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Customer Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="IC_CUSTOMER_DESCRIPTION"  class="form-control" name="ic_customer_description"  cols="">{{ $customer_info->ic_customer_description }}</textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                        <div class="row">
                            <div class="col-md-9"></div>
                            <div class="col-md-3" align="right">
                                 <button type="submit" name="btn_save_customer" id="BTN_SAVE_CUSTOMER"  class="btn btn-info">Save</button>
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
                                                <option data-account_id="{{ $acc_info->aa_account }}" {{ $customer_account_id == $acc_info->aa_id ? "selected" : ""  }} value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label  }}</option>
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