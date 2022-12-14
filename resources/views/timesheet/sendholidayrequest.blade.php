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
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/companyholidays.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/sendholidayrequest.js') }}"></script>
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