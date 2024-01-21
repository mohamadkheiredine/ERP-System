<?php
/***********************************************************
editform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 28, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


{
    $image_src_url  = url('/')."/".Config::get('constants.SRM_PATH').$supplier_info->ss_logo_base_src.$supplier_info->ss_logo_file_name.".".$supplier_info->ss_logo_file_extension;
 
    $image_src_path = public_path(). "/" .Config::get('constants.SRM_PATH').$supplier_info->ss_logo_base_src.$supplier_info->ss_logo_file_name.".".$supplier_info->ss_logo_file_extension;
    
    if( is_file($image_src_path) ){
        $img_src = $image_src_url;
    }else{
        $img_src = url('images/NoImageAvailable.jpg');
    }
 
}
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
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/suppliers.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/srm/savesuppliers.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Existing Supplier</h3>
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
    <form name="frm_save_supplier" id="FORM_SAVE_SUPPLIER">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                      <input type="hidden" name="fk_owner_id" id="FK_OWNER_ID" value="{{ session('user_id') }}" />
                      <input type="hidden" name="ss_id" id="SS_ID" value="{{ $supplier_info->ss_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
        				<strong>Success!</strong> Supplier Information is saved successfully!
        			</div>
        			<div class="alert alert-danger" style="display:none">
        				<strong>Error!</strong> You have some form errors. Please check below.
        			</div>
            			
            		<div class="row">
                     		<div class="col-md-12">
                     			<div class="card shadow-sm">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">Basic Information</h3>
                                        <div class="card-toolbar">
                                            <button type="button" class="btn btn-sm btn-light">
                                                Action
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
        									<div class="col-md-12" align="left">
                                    		<label>Supplier Logo </label>
                                            	</div>
                                                 <div class="col-md-4">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                            <img id="AVATAR_PIC" width="100" src="{{ $img_src }}" alt="" /> </div>
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
                                                                  <option {{ $supplier_info->fk_category_id == $cat_info->sc_id ? "selected" : "" }}  value="{{ $cat_info->sc_id }}">{{  $cat_info->sc_category_title }}</option>
                                                                @endforeach
                                                            
                                                        </select>
                                                    </div>
                                                </div>  
                                            	<div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Supplier Name <span class="required"> * </span></label>
                                                        <input type="text" name="ss_supplier_name" id="SS_SUPPLIER_NAME" class="form-control" required="required" maxlength="255"  value="{{ $supplier_info->ss_supplier_name }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Supplier Phone</label>
                                                        <input type="text" name="ss_supplier_phone" id="SS_SUPPLIER_PHONE" class="form-control" maxlength="255"  value="{{ $supplier_info->ss_supplier_phone }}" />
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
                            		 	<textarea class="form-control" id="SS_SUPPLIER_DESCRIPTION" name="ss_supplier_description" style="width:100%;height:250px;resize:none" >{{ $supplier_info->ss_supplier_description }}</textarea>
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
                                <div class="card shadow-sm">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">Extra Information</h3>
                                        <div class="card-toolbar">
                                            <button type="button" class="btn btn-sm btn-light">
                                                Action
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                    <div class="row">
        									<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Company Name</label>
                                                    <input type="text" name="ss_company_name" id="SS_COMPANY_NAME" class="form-control" maxlength="255"  value="{{ $supplier_info->ss_company_name }}" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Supplier Mobile</label>
                                                    <input type="url" name="ss_supplier_mobile" id="SS_SUPPLIER_MOBILE" class="form-control" maxlength="255"  value="{{ $supplier_info->ss_supplier_mobile }}" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Supplier Fax</label>
                                                    <input type="text" name="ss_supplier_fax" id="SS_SUPPLIER_FAX" class="form-control"  maxlength="255"  value="{{ $supplier_info->ss_supplier_fax }}" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Supplier Email</label>
                                                    <input type="email" name="ss_supplier_email" id="SS_SUPPLIER_EMAIL" class="form-control"  maxlength="255"  value="{{ $supplier_info->ss_supplier_email }}" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Supplier Skype</label>
                                                    <input type="text" name="ss_skype_id" id="SS_SKYPE_ID" class="form-control"  maxlength="255"  value="{{ $supplier_info->ss_skype_id }}" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Twitter Account</label>
                                                    <input type="text" name="ss_twitter_account" id="SS_TWITTER_ACCOUNT" class="form-control"  maxlength="255"  value="{{ $supplier_info->ss_twitter_account }}" />
                                                </div>
                                            </div>
                                        	<div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Facebook Account</label>
                                                    <input type="text" name="ss_facebook_id" id="SS_FACEBOOK_ID" class="form-control"  maxlength="255"  value="{{ $supplier_info->ss_facebook_id }}" />
                                                </div>
                                            </div>
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                 <label>Country</label>
                                                   <select class="bs-select form-control" id="FK_country_ID"  name="fk_country_id">
                                            			<option value="0">-- Select Country --</option>
                                                        @foreach($lst_countries as $index => $country_info)
                                                          <option {{ $supplier_info->fk_country_id == $country_info->id ? "selected" : ""  }} value="{{ $country_info->id }}">{{  $country_info->name }}</option>
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
                                                          <option {{ $supplier_info->fk_industry_id ==  $industry_info->si_id ? "selected" : ""  }} value="{{ $industry_info->si_id }}">{{ $industry_info->si_industry }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                            				</div> 
                                        	 <div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">City Name</label>
                                                    <input type="text" name="ss_city_name" id="SS_CITY_NAME" class="form-control" maxlength="255"  value="{{ $supplier_info->ss_city_name }}" />
                                                </div>
                                            </div>
                                             <div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Zip Code</label>
                                                    <input type="text" name="ss_zip_code" id="SS_ZIP_CODE" class="form-control"  maxlength="5"  value="{{ $supplier_info->ss_zip_code }}" />
                                                </div>
                                            </div>
                                             <div class="col-md-4">
                                                 <div class="form-group">
                                                    <label class="control-label">Street Name</label>
                                                    <input type="text" name="ss_street_name" id="SS_STREET_NAME" class="form-control" maxlength="255"  value="{{ $supplier_info->ss_street_name }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label class="control-label">Address</label>
                                                    <textarea class="form-control" id="SS_ADDRESS" name="ss_address" style="width:100%;height:150px;resize:none" >{{ $supplier_info->ss_address }}</textarea>
                                                </div>
                                            </div>
        								</div>
                                    </div>
                                </div> 
                     		</div>
                     	</div>
                     	 <div class="row" style="height:50px;"></div>
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
@endsection