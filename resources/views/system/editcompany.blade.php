<?php
/***********************************************************
editcompany.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


{
    $image_src_url  = url('/')."/".Config::get('constants.COMPANY_PATH').$company_info->cd_logo_base_src.$company_info->cd_logo_file_name.".".$company_info->cd_logo_file_extension;
    $image_src_path = public_path(). "/" .Config::get('constants.COMPANY_PATH').$company_info->cd_logo_base_src.$company_info->cd_logo_file_name.".".$company_info->cd_logo_file_extension;
    
    if(strlen($company_info->cd_logo_base_src) > 0 ){
        $img_src = $image_src_url;
    }else{
        $img_src = url('images/NoImageAvailable.jpg');
    }
    
}

?>

@extends('layouts.layout',['page_title' => "Companies Management"])

@section('plugins')

<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-inline.bundle.js') }}"></script>
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-balloon.bundle.js') }}"></script>
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-balloon-block.bundle.js') }}"></script>
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-document.bundle.js') }}"></script>

<script type="text/javascript" src="{{ url('js/modules/companies.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/system/savecompanies.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Existing Company</h3>
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
    <form name="frm_save_company" id="FORM_SAVE_COMPANY">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                        <input type="hidden" name="cd_id" value="{{ $company_info->cd_id }}" />
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
                                <img id="LOGO_PIC" width="240" src="{{ $img_src }}" alt="" /> </div>
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
                                    <input type="text"  name="cd_company_name" id="CD_COMPANY_NAME" class="form-control" required="required" maxlength="155"  value="{{ $company_info->cd_company_name }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Company Owner <span class="required"> * </span></label>
                                    <input type="text" name="cd_company_owner" id="CD_COMPANY_OWNER" class="form-control" required="required" maxlength="255"  value="{{ $company_info->cd_company_owner }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Phone <span class="required"> * </span></label>
                                <input type="text" name="cd_company_phone" id="CD_COMPANY_PHONE" class="form-control" required="required" maxlength="50"  value="{{ $company_info->cd_company_phone }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Mobile <span class="required"> * </span></label>
                                <input type="text" name="cd_company_mobile" id="CD_COMPANY_MOBILE" class="form-control" required="required" maxlength="50"  value="{{ $company_info->cd_company_mobile }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Website</label>
                                <input type="text" name="cd_company_website" id="CD_COMPANY_WEBSITE" class="form-control" maxlength="255"  value="{{ $company_info->cd_company_website }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Email <span class="required"> * </span></label>
                                <input type="email" name="cd_company_email" id="CD_COMPANY_EMAIL" class="form-control" required="required" maxlength="255"  value="{{ $company_info->cd_company_email }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Contact Name <span class="required"> * </span></label>
                                <input type="text" name="cd_contact_name" id="CD_CONTACT_NAME" class="form-control" required="required" maxlength="255"  value="{{ $company_info->cd_contact_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Contact Phone <span class="required"> * </span></label>
                                <input type="text" name="cd_contact_mobile" id="CD_CONTACT_MOBILE" class="form-control" required="required" maxlength="25"  value="{{ $company_info->cd_contact_mobile }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Contact Email <span class="required"> * </span></label>
                                <input type="email" name="cd_contact_email" id="CD_CONTACT_EMAIL" class="form-control" required="required" maxlength="255"  value="{{ $company_info->cd_contact_email }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Starting Date<span class="required"> * </span></label>
                                <input type="text" name="cd_company_starting_date" id="CD_COMPANY_STARTING_DATE" class="form-control" required="required" maxlength="255"  value="{{ $company_info->cd_company_starting_date }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Transportation Fees <span class="required"> * </span></label>
                                <input type="text" name="cd_transportation_fees" id="CD_TRANSPORTATION_FEES" class="form-control" required="required" maxlength="255"  value="{{ $company_info->cd_transportation_fees }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Company Country</label>
                                <select class="bs-select form-control" name="cd_company_country" id="CD_COMPANY_COUNTRY" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_countries as $index => $count_info)
                                        <option {{ $company_info->cd_company_country == $count_info->id ? "selected" : "" }} value="{{ $count_info->id }}">{{ $count_info->name }}</option>
                                        @endforeach 
                                </select>
                            </div>
						</div>
						<div class="col-md-4">
                            <div class="form-group">
                                <label>Company Tax:</label>
                                <select class="bs-select form-control" name="cd_company_tax" id="CD_COMPANY_TAX" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_taxes as $index => $tax_info)
                                        <option value="{{ $tax_info->av_id }}" {{ $company_info->cd_company_tax == $tax_info->av_id ? "selected" : "" }} >{{ $tax_info->av_vat_label }}&nbsp;(&nbsp;{{ $tax_info->av_vat_rate }}&nbsp;%&nbsp;)&nbsp;</option>
                                        @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Company Currency Used:</label>
                                <select class="bs-select form-control" name="cd_company_currency" id="CD_COMPANY_CURRENCY" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_currencies as $index => $curr_info)
                                        <option {{ $company_info->cd_company_currency == $curr_info->cc_id ? "selected" : "" }} value="{{ $curr_info->cc_id }}">{{  $curr_info->cc_currency_code . " - " . $curr_info->cc_currency_name }}</option>
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
                                        <option {{ $company_info->cd_secondary_currency == $curr_info->cc_id ? "selected" : "" }} value="{{ $curr_info->cc_id }}">{{  $curr_info->cc_currency_code . " - " . $curr_info->cc_currency_name }}</option>
                                        @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Default Item Type&nbsp;:</label>
                                <select class="bs-select form-control" name="cd_default_item" id="CD_DEFAULT_ITEM" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        <option {{ $company_info->cd_default_item == 1 ? "selected" : "" }} value="1">Products</option>
                                        <option {{ $company_info->cd_default_item == 2 ? "selected" : "" }}  value="2">Services</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Company Homepage&nbsp;:</label>
                                <select class="bs-select form-control" name="cd_company_homepage" id="CD_COMPANY_HOMEPAGE" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        <option {{ $company_info->cd_company_homepage == 1 ? "selected" : "" }} value="1">Dashboard</option>
                                        <option {{ $company_info->cd_company_homepage == 2 ? "selected" : "" }} value="2">Account Statment</option>
                                        <option {{ $company_info->cd_company_homepage == 3 ? "selected" : "" }} value="3">Services Dashboard</option>
                                </select>
                            </div>
                        </div>
                        </div> <div class="col-md-6">
                             <div class="form-group">
                                <label class="control-label"> Company Address</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CD_COMPANY_ADDRESS"  class="form-control" name="cd_company_address"  cols="">{{ $company_info->cd_company_address }}</textarea>
                             </div>
                        </div>
                        <div class="col-md-4">
                          <label> Primary Compoany</label>
                           <div class="m-form__group form-group row">
								<div class="col-12">
									<span class="m-switch m-switch--icon m-switch--info">
										<label>
											<input type="checkbox" {{ $company_info->cd_primary_company == 1 ? "checked='checked'" : ""  }} name="cd_primary_company"  value="1"  />
											<span></span>
										</label>
									</span>
								</div>
								</div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> About Company</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CD_ABOUT_COMPANY"  class="form-control" name="cd_about_company"  cols="">{{  $company_info->cd_about_company }}</textarea>
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
            </form>
    </div>
</div>
@endsection