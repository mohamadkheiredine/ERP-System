<?php
/***********************************************************
addform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Add Form for new Vendors
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Vendors Management"])

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
<script type="text/javascript" src="{{ url('js/modules/vendors.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/inventory/savevendors.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Vendor</h3>
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
    <form name="frm_save_vendor" id="FORM_SAVE_VENDOR">
                <div class="form-body">
                     <span id="hidden_fields">
                      	{!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Vendor Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
            		<div class="row" style="height:50px;"></div>
                    <div class="row">
                    	<div class="col-md-12" align="left">
                		<label>Vendor Logo </label>
                        	</div>
                             <div class="col-md-4">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img id="VENDOR_LOGO_PIC" width="100" src="{{ url('images/NoImageAvailable.jpg') }}" alt="" /> </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
        
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="clearfix margin-top-10">
                                    <div>
                                        <span class="btn default btn-file" style="text-align: left;">
                                            <span class="fileinput-new"> Select image </span><br/>
                                            <input type="file" name="iv_avatar_pic" id="IV_AVATAR_PIC" /> </span>
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
                                    <label class="control-label">Vendor Code <span class="required"> * </span></label>
                                    <input type="text" name="iv_vendor_code" readonly="readonly" id="IV_VENDOR_CODE" class="form-control" required="required" maxlength="15"  value="{{ $vendor_code }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Vendor Name <span class="required"> * </span></label>
                                <input type="text" name="iv_vendor_name" id="IV_VENDOR_NAME" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Vendor Warehouse  <span class="required"> * </span></label>
                                 <select class="bs-select form-control" name="iv_warehouse_id" id="IV_WAREHOUSE_ID" data-actions-box="true">
                                        <?php foreach ( $lst_warehouses as $key => $warehouse_info ) { ?>
                                                <option value="<?php echo $warehouse_info->w_id;  ?>"><?php echo $warehouse_info->w_warehouse_name;  ?></option>
                                        <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Vendor Country </label>
                                 <select class="bs-select form-control" name="iv_vendor_country" id="IV_VENDOR_COUNTRY" data-actions-box="true">
                                        @foreach ( $lst_countries as $key => $count_info )
                                                <option value="{{ $count_info->id }}">{{ $count_info->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Vendor Tax </label>
                                        <select class="bs-select form-control" name="iv_vendor_tax_id" id="IV_VENDOR_TAX_ID" data-actions-box="true">
                                        @foreach ( $lst_vat_tax as $key => $vat_info )
                                                <option value="{{ $vat_info->av_id }}">{{ $vat_info->av_vat_label . "(" . $vat_info->av_vat_code . ")" }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Vendor Email <span class="required"> * </span></label>
                                <input type="email" name="iv_vendor_email" id="IV_VENDOR_EMAIL" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Vendor Website </label>
                                <input type="url" name="iv_vendor_website" id="IV_VENDOR_WEBSITE" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Vendor Phone </label>
                                <input type="tel" name="iv_vendor_phone" id="IV_VENDOR_PHONE" class="form-control" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Vendor mobile </label>
                                <input type="tel" name="iv_vendor_mobile" id="IV_VENDOR_MOBILE" class="form-control"   maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-8">
                             <div class="form-group">
                                <label class="control-label">Vendor Address </label>
                                <textarea style="width:100%;height:250px;resize:none" id="IV_VENDOR_ADDRESS"  class="form-control" name="iv_vendor_address"  cols=""></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Accounting <a href="#" id="ADD_ACCOUNT" data-toggle="modal" data-target="#AccountAccounting" ><i class="flaticon-add-circular-button"></i></a> </label>
                                <select class="bs-select form-control" name="iv_vendor_account_id" id="IV_VENDOR_ACCOUNT_ID" data-actions-box="true">
                                        <option value="">-- Select Account --</option>
                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                <option value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Vendor Description <span class="required"> * </span></label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="IV_VENDOR_DESCRIPTION"  class="form-control" name="iv_vendor_description"  cols=""></textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                        <div class="row">
                            <div class="col-md-9"></div>
                            <div class="col-md-3" align="right">
                                 <button type="submit" name="btn_save_vendpr" id="BTN_SAVE_VENDOR"  class="btn btn-info">Save</button>
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
                                        <option value="">Vendor Account</option>
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