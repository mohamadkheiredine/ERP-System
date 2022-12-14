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

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">

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