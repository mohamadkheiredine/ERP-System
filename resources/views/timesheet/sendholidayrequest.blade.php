<?php
/***********************************************************
sendholidayrequest.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 3, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



?>

@extends('layouts.layout',['page_title' => "Holiday Management > Send Holiday Request"])

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
<script type="text/javascript" src="{{ url('js/modules/companyholidays.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/sendholidayrequest.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Send Holiday Request</h3>
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
        <form name="frm_send_holiday_request" id="FORM_SEND_HOLIDAY_REQUEST">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Holiday Request Has been Sent successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
            			 
                    <div class="row">
                        <div class="col-md-8">
                              <div class="form-group">
                                    <label class="control-label">Holiday Date <span class="required"> * </span></label>
                                    <div class="input-group input-daterange">
                                        <input type="text" name="tr_holiday_date_from" id="TR_HOLIDAY_DATE_FROM" class="form-control" maxlength="100" readonly="readonly"  required="required" value="">
                                        <div class="input-group-addon">&nbsp;&nbsp;to</div>
                                        <input type="text" name="tr_holiday_date_to" id="TR_HOLIDAY_DATE_TO"  class="form-control" maxlength="100" readonly="readonly"  required="required" value="">
                                    </div>
                                </div>
                        </div>
                         <div class="col-md-12">
                              <div class="form-group">
                                    <label class="control-label">Holiday Reason</label>
                                    <textarea class="form-control" id="TR_REASON_FOR_HOLIDAY" name="tr_reason_for_holiday" style="width:100%;height:250px;resize:none" ></textarea>
                                </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_send_request" id="BTN_SEND_REQUEST"  class="btn btn-info">Send Request</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>

 

@endsection