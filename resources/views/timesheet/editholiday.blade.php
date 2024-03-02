<?php
/***********************************************************
editholiday.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Holiday Management > Edit Yearly Holiday"])

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
<script type="text/javascript" src="{{ url('js/modules/companyholidays.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/saveholiday.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Holiday</h3>
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
    <form name="frm_save_holiday" id="FORM_SAVE_HOLIDAY">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="th_id" id="TH_ID" value="{{ $yearly_holiday->th_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Holiday Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
            			 
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Holiday Title <span class="required"> * </span></label>
                                    <input type="text" name="th_holiday_name" id="TH_HOLIDAY_NAME" class="form-control" required="required" value="{{ $yearly_holiday->th_holiday_name }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Holiday Date <span class="required"> * </span></label>
                                <input type="text" name="th_holiday_date" id="TH_HOLIDAY_DATE" class="form-control"  maxlength="100"  value="{{ $yearly_holiday->th_holiday_date }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Holiday Date <span class="required"> * </span></label>
                                <select name="th_year" id="TH_YEAR" class="form-control">
                                	<option {{ $yearly_holiday->th_year == date('Y') ? "selected" : "" }} value="{{ date('Y') }}">{{ date("Y") }}</option> 
                                	<option {{ $yearly_holiday->th_year == ( date('Y') + 1 ) ? "selected" : "" }} value="{{ date('Y') + 1 }}">{{ date("Y") + 1 }}</option> 
                                	<option {{ $yearly_holiday->th_year == ( date('Y') + 2 ) ? "selected" : "" }} value="{{ date('Y') + 2 }}">{{ date("Y") + 2 }}</option> 
                                	<option {{ $yearly_holiday->th_year == ( date('Y') + 3 ) ? "selected" : "" }} value="{{ date('Y') + 3 }}">{{ date("Y") + 3 }}</option> 
                                </select>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_holiday" id="BTN_SAVE_HOLIDAY"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>

@endsection