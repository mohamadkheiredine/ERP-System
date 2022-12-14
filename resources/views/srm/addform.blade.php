<?php
/***********************************************************
addform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 28, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Suppliers Management"])

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
<script type="text/javascript" src="{{ url('js/modules/suppliers.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/srm/savesuppliers.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Add New Supplier
				</h3>
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
             <form name="frm_save_supplier" id="FORM_SAVE_SUPPLIER">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                      <input type="hidden" name="fk_owner_id" id="FK_OWNER_ID" value="{{ session('user_id') }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
        				<strong>Success!</strong> Supplier Information is saved successfully!
        			</div>
        			<div class="alert alert-danger" style="display:none">
        				<strong>Error!</strong> You have some form errors. Please check below.
        			</div>
            			
            		<div class="row">
                     		<div class="col-md-12">
                     			<div class="m-portlet m-portlet--mobile">
        							<div class="m-portlet__head">
        								<div class="m-portlet__head-caption">
        									<div class="m-portlet__head-title">
        										<h3 class="m-portlet__head-text">
        											Basic Information
        										</h3>
        									</div>
        								</div>
        							</div>
        							<div class="m-portlet__body">
        								<div class="row">
        									<div class="col-md-12" align="left">
                                    		<label>Supplier Logo </label>
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
                                                                <input type="file" name="ss_logo_pic" id="SS_LOGO_PIC" /> </span>
                                                        </div>
                                                        <br>
                                                        <span class="label label-danger"> NOTE! </span><br><br>
                                                        <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                        <label>Category</label>
                                                        <select class="bs-select form-control" name="fk_category_id" id="fk_category_id" data-actions-box="true">
                                                                <option value="0">-- Select Category --</option>
                                                                @foreach( $lst_srm_categories as $key => $cat_info )
                                                                  <option value="{{ $cat_info->sc_id }}">{{  $cat_info->sc_category_title }}</option>
                                                                @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>  
                                            	<div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Supplier Name <span class="required"> * </span></label>
                                                        <input type="text" name="ss_supplier_name" id="SS_SUPPLIER_NAME" class="form-control" required="required" maxlength="255"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Supplier Phone</label>
                                                        <input type="text" name="ss_supplier_phone" id="SS_SUPPLIER_PHONE" class="form-control" maxlength="255"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                  <div class="form-group">
                                                        <label> Sales Account &nbsp;<a href="#" id="ADD_SALES_ACCOUNT" style="text-decoration: none;"  data-dropdown_name="ss_sale_account_id" ><i class="flaticon-add-circular-button"></i></a>&nbsp;  <span class="required"> * </span></label>
                                                        <select class="bs-select form-control" name="ss_sale_account_id" id="SS_SALE_ACCOUNT_ID" data-actions-box="true">
                                                                <option value="">-- Select Account --</option>
                                                                @foreach ( $lst_accounts as $key => $acc_info )
                                                                        <option value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Purchase Account  &nbsp;<a href="#" id="ADD_PURCHASE_ACCOUNT" style="text-decoration: none;"  data-dropdown_name="ss_purchase_account_id" ><i class="flaticon-add-circular-button"></i></a>&nbsp;  <span class="required"> * </span></label>
                                                        <select class="bs-select form-control" name="ss_purchase_account_id" id="SS_PURCHASE_ACCOUNT_ID" data-actions-box="true">
                                                                <option value="">-- Select Account --</option>
                                                                @foreach ( $lst_accounts as $key => $acc_info )
                                                                        <option value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
        								</div>
        							</div>
        						</div>
        						 <div class="row" style="height:50px;"></div>
                                 <div class="row">
                                	<div class="col-md-12" align="left">  
                            			<label>Supplier Description </label>
                                    </div>
                                </div>
                                 <div class="row">
                                	<div class="col-md-12" align="left">
                            		 	<textarea class="form-control" id="SS_SUPPLIER_DESCRIPTION" name="ss_supplier_description" style="width:100%;height:250px;resize:none" ></textarea>
                                    </div>
                                </div>
                                <div class="row" style="height:50px;"></div>    
                                 <div class="row">
                                    <div class="col-md-9"></div>
                                    <div class="col-md-3" align="right">
                                         <button type="submit" name="btn_save_supplier" id="BTN_SAVE_SUPPLIER_TOP"  class="btn btn-info">Save</button>
                                        <button type="button" id="BACK_FORM_TOP" name="back_form" class="btn default">Back</button>
                                    </div>
                                </div>  
                                <div class="row" style="height:50px;"></div>
        						<div class="m-portlet m-portlet--mobile">
        							<div class="m-portlet__head">
        								<div class="m-portlet__head-caption">
        									<div class="m-portlet__head-title">
        										<h3 class="m-portlet__head-text">
        											Extra Information
        										</h3>
        									</div>
        								</div>
        							</div>
        							<div class="m-portlet__body">
        								<div class="row">
        									<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Company Name</label>
                                                    <input type="text" name="ss_company_name" id="SS_COMPANY_NAME" class="form-control" maxlength="255"  value="" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Supplier Mobile</label>
                                                    <input type="url" name="ss_supplier_mobile" id="SS_SUPPLIER_MOBILE" class="form-control" maxlength="255"  value="" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Supplier Fax</label>
                                                    <input type="text" name="ss_supplier_fax" id="SS_SUPPLIER_FAX" class="form-control"  maxlength="255"  value="" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Supplier Email</label>
                                                    <input type="email" name="ss_supplier_email" id="SS_SUPPLIER_EMAIL" class="form-control"  maxlength="255"  value="" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Supplier Skype</label>
                                                    <input type="text" name="ss_skype_id" id="SS_SKYPE_ID" class="form-control"  maxlength="255"  value="" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Twitter Account</label>
                                                    <input type="text" name="ss_twitter_account" id="SS_TWITTER_ACCOUNT" class="form-control"  maxlength="255"  value="" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Facebook Account</label>
                                                    <input type="text" name="ss_facebook_id" id="SS_FACEBOOK_ID" class="form-control"  maxlength="255"  value="" />
                                                </div>
                                            </div>
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                 <label>Country</label>
                                                   <select class="bs-select form-control" id="FK_country_ID"  name="fk_country_id">
                                            			<option value="0">-- Select Country --</option>
                                                        @foreach($lst_countries as $index => $country_info)
                                                          <option value="{{ $country_info->id }}">{{  $country_info->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                            				</div> 
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                 <label>Industry</label>
                                                   <select class="bs-select form-control" id="FK_INDUSTRY_ID" name="fk_industry_id">
                                            			<option value="0">-- Select Industry --</option>
                                                        @foreach($lst_industries as $index => $industry_info)
                                                          <option value="{{ $industry_info->si_id }}">{{ $industry_info->si_industry }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                            				</div> 
                                        	 <div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">City Name</label>
                                                    <input type="text" name="ss_city_name" id="SS_CITY_NAME" class="form-control" maxlength="255"  value="" />
                                                </div>
                                            </div>
                                             <div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Zip Code</label>
                                                    <input type="text" name="ss_zip_code" id="SS_ZIP_CODE" class="form-control"  maxlength="5"  value="" />
                                                </div>
                                            </div>
                                             <div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Street Name</label>
                                                    <input type="text" name="ss_street_name" id="SS_STREET_NAME" class="form-control" maxlength="255"  value="" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label class="control-label">Address</label>
                                                    <textarea class="form-control" id="SS_ADDRESS" name="ss_address" style="width:100%;height:150px;resize:none" ></textarea>
                                                </div>
                                            </div>
        								</div>
        							</div>
        						</div>
                     		</div>
                     	</div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_supplier" id="BTN_SAVE_SUPPLIER"  class="btn btn-info">Save</button>
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
                                        <option value="">Supplier Parent Account</option>
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