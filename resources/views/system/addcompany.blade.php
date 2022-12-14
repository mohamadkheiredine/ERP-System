<?php
/***********************************************************
addcompany.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Companies Management"])

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
<script type="text/javascript" src="{{ url('js/modules/companies.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/system/savecompanies.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Add New Company</h3>
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
             <form name="frm_save_company" id="FORM_SAVE_COMPANY">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong>Company Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
            			<div class="row">
                	<div class="col-md-12" align="left">
                		<label>Logo </label>
                	</div>
                     <div class="col-md-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                <img id="LOGO_PIC" width="240" src="{{ url('images/NoImageAvailable.jpg') }}" alt="" /> </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>

                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="clearfix margin-top-10">
                            <div>
                                <span class="btn default btn-file" style="text-align: left;">
                                    <span class="fileinput-new"> Select image </span><br/>
                                    <input type="file" name="cd_logo_pic" id="CD_LOGO_PIC" /> </span>
                            </div>
                            <br>
                            <span class="label label-danger"> NOTE! </span><br><br>
                            <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Company Name <span class="required"> * </span></label>
                                    <input type="text" name="cd_company_name" id="CD_COMPANY_NAME" class="form-control" required="required" maxlength="155"  value="" />
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Company Owner <span class="required"> * </span></label>
                                    <input type="text" name="cd_company_owner" id="CD_COMPANY_OWNER" class="form-control" required="required" maxlength="255"  value="" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Phone <span class="required"> * </span></label>
                                <input type="text" name="cd_company_phone" id="CD_COMPANY_PHONE" class="form-control" required="required" maxlength="50"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Mobile <span class="required"> * </span></label>
                                <input type="text" name="cd_company_mobile" id="CD_COMPANY_MOBILE" class="form-control" required="required" maxlength="50"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Website</label>
                                <input type="url" name="cd_company_website" id="CD_COMPANY_WEBSITE" class="form-control"  maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Email <span class="required"> * </span></label>
                                <input type="text" name="cd_company_email" id="CD_COMPANY_EMAIL" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Contact Name <span class="required"> * </span></label>
                                <input type="text" name="cd_contact_name" id="CD_CONTACT_NAME" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Contact Phone <span class="required"> * </span></label>
                                <input type="text" name="cd_contact_mobile" id="CD_CONTACT_MOBILE" class="form-control" required="required" maxlength="25"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Contact Email <span class="required"> * </span></label>
                                <input type="email" name="cd_contact_email" id="CD_CONTACT_EMAIL" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Starting Date<span class="required"> * </span></label>
                                <input type="text" name="cd_company_starting_date" id="CD_COMPANY_STARTING_DATE" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Transportation Fees <span class="required"> * </span></label>
                                <input type="text" name="cd_transportation_fees" id="CD_TRANSPORTATION_FEES" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Company Country</label>
                                <select class="bs-select form-control" name="cd_company_country" id="CD_COMPANY_COUNTRY" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_countries as $index => $count_info)
                                        <option value="{{ $count_info->id }}">{{ $count_info->name }}</option>
                                        @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Company Tax&nbsp;:</label>
                                <select class="bs-select form-control" name="cd_company_tax" id="CD_COMPANY_TAX" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_taxes as $index => $tax_info)
                                        <option value="{{ $tax_info->av_id }}">{{ $tax_info->av_vat_label }}&nbsp;(&nbsp;{{ $tax_info->av_vat_rate }}&nbsp;%&nbsp;)&nbsp;</option>
                                        @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Company Currency Used&nbsp;:</label>
                                <select class="bs-select form-control" name="cd_company_currency" id="CD_COMPANY_CURRENCY" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_currencies as $index => $curr_info)
                                        <option value="{{ $curr_info->cc_id }}">{{  $curr_info->cc_currency_code . " - " . $curr_info->cc_currency_name }}</option>
                                        @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Second Currency Used&nbsp;:</label>
                                <select class="bs-select form-control" name="cd_secondary_currency" id="CD_SECONDARY_CURRENCY" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_currencies as $index => $curr_info)
                                        <option value="{{ $curr_info->cc_id }}">{{  $curr_info->cc_currency_code . " - " . $curr_info->cc_currency_name }}</option>
                                        @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Default Item Type&nbsp;:</label>
                                <select class="bs-select form-control" name="cd_default_item" id="CD_DEFAULT_ITEM" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        <option value="1">Products</option>
                                        <option value="2">Services</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Company Homepage&nbsp;:</label>
                                <select class="bs-select form-control" name="cd_company_homepage" id="CD_COMPANY_HOMEPAGE" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        <option value="1">Dashboard</option>
                                        <option value="2">Account Statment</option>
                                        <option value="3">Services Dashboard</option>
                                </select>
                            </div>
                        </div>
                         <div class="col-md-6">
                             <div class="form-group">
                                <label class="control-label"> Company Address</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CD_COMPANY_ADDRESS"  class="form-control" name="cd_company_address"  cols=""></textarea>
                             </div>
                        </div>
                        <div class="col-md-4">
                          <label> Primary Compoany </label>
                           <div class="m-form__group form-group row">
								<div class="col-12">
									<span class="m-switch m-switch--icon m-switch--info">
										<label>
											<input type="checkbox"  name="cd_primary_company" value="1" />
											<span></span>
										</label>
									</span>
								</div>
								</div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> About Company</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CD_ABOUT_COMPANY"  class="form-control" name="cd_about_company"  cols=""></textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_company" id="BTN_SAVE_COMPANY"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>
@endsection