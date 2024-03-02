<?php
/***********************************************************
editshipcompany.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 12, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


{
    $image_src_url  = url('/')."/".Config::get('constants.LOGISTICS_PATH').$shipcompany_info->sc_logo_base_src.$shipcompany_info->sc_logo_file_name.".".$shipcompany_info->sc_logo_file_extension;
    $image_src_path = public_path(). "/" .Config::get('constants.LOGISTICS_PATH').$shipcompany_info->sc_logo_base_src.$shipcompany_info->sc_logo_file_name.".".$shipcompany_info->sc_logo_file_extension;
    
    if(strlen($shipcompany_info->sc_logo_file_extension) > 0 ){
        $img_src = $image_src_url;
    }else{
        $img_src = url('images/NoImageAvailable.jpg');
    }
    
}

?>

@extends('layouts.layout',['page_title' => "Shipment Companies Management > Edit Shipment Company Info"])

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
<script type="text/javascript" src="{{ url('js/modules/shipcompanies.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/logistics/saveshipcompanies.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">vehicules Management</h3>
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
                        {!! csrf_field() !!} 
                        <input type="hidden" name="sc_id" id="SC_ID" value="{{ $shipcompany_info->sc_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong>Shipment Company Information is saved successfully!
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
                                <img id="AVATAR_PIC" height="120" src="{{ $img_src }}" alt="" /> </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>

                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="clearfix margin-top-10">
                            <div>
                                <span class="btn default btn-file" style="text-align: left;">
                                    <span class="fileinput-new"> Select image </span><br/>
                                    <input type="file" name="sc_avatar_pic" id="SC_AVATAR_PIC" /> </span>
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
                                    <input type="text" name="sc_company_name" id="SC_COMPANY_NAME" class="form-control" required="required" maxlength="155"  value="{{ $shipcompany_info->sc_company_name }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Company Owner</label>
                                    <input type="text" name="sc_company_owner" id="SC_COMPANY_OWNER" class="form-control" maxlength="255"  value="{{ $shipcompany_info->sc_company_owner }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Phone</label>
                                <input type="text" name="sc_company_phone" id="SC_COMPANY_PHONE" class="form-control" maxlength="50"  value="{{ $shipcompany_info->sc_company_phone }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Mobile</label>
                                <input type="text" name="sc_company_mobile" id="SC_COMPANY_MOBILE" class="form-control" maxlength="50"  value="{{ $shipcompany_info->sc_company_mobile }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Website</label>
                                <input type="url" name="sc_company_website" id="SC_COMPANY_WEBSITE" class="form-control" maxlength="255"  value="{{ $shipcompany_info->sc_company_website }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company Email</label>
                                <input type="text" name="sc_company_email" id="SC_COMPANY_EMAIL" class="form-control" maxlength="255"  value="{{ $shipcompany_info->sc_company_email }}" />
                            </div>
                        </div>
                         <div class="col-md-8">
                             <div class="form-group">
                                <label class="control-label">Company Address</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="SC_COMPANY_ADDRESS"  class="form-control" name="sc_company_address"  cols="">{{ $shipcompany_info->sc_company_address }}</textarea>
                             </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Company Country <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="sc_company_country" id="SC_COMPANY_COUNTRY" required="required" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_countries as $index => $count_info)
                                        <option {{ $shipcompany_info->sc_company_country == $count_info->id ? "selected" : "" }} value="{{ $count_info->id }}">{{ $count_info->name }}</option>
                                        @endforeach 
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Company Rate <span class="required"> * </span></label>
                                    <input type="text" name="sc_company_rate" id="SC_COMPANY_RATE" class="form-control" required="required" maxlength="255"  value="{{ $shipcompany_info->sc_company_rate }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Company Currency Used <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="sc_company_currency" id="SC_COMPANY_CURRENCY" required="required" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_currencies as $index => $curr_info)
                                        <option {{ $shipcompany_info->sc_company_currency == $curr_info->cc_id ? "selected" : "" }} value="{{ $curr_info->cc_id }}">{{  $curr_info->cc_currency_code . " - " . $curr_info->cc_currency_name }}</option>
                                        @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Rate Type</label>
                                 <select class="bs-select form-control" name="sc_rate_type" id="SC_RATE_TYPE" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        <option {{ $shipcompany_info->sc_company_rate == 1 ? "selected" : "" }} value="1">Rate By Distance</option>
                                        <option {{ $shipcompany_info->sc_company_rate == 2 ? "selected" : "" }} value="2">Rate By Time</option>
                                        <option {{ $shipcompany_info->sc_company_rate == 3 ? "selected" : "" }} value="3">Rate By Shipment</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> About Company <span class="required"> * </span></label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="SC_ABOUT_COMPANY"  class="form-control" name="sc_about_company"  cols="">{{ $shipcompany_info->sc_about_company }}</textarea>
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