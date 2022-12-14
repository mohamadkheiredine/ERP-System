<?php
/***********************************************************
timesheet.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 4, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page to manage timesheet 
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Timesheet Management"])

@section('themes')
<style>
th{
    cursor: pointer;
}
#ModelPopUp{
	width:800px;
}
td{
	font-weight: bold;
	color:white;
}
</style>
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/timesheetmanagement.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/timesheetmanagement.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
<div class="m-portlet__head">
	<div class="m-portlet__head-caption">
		<div class="m-portlet__head-title">
			<h3 class="m-portlet__head-text">
				Timesheet Management 
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
	<!--begin: Search Form -->
	<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
		<div class="row align-items-center">
			<div class="col-xl-8 order-2 order-xl-1">
				<div class="form-group m-form__group row align-items-center">
					<div class="col-md-4">
						<div class="d-md-none m--margin-bottom-10"></div>
					</div>
					<div class="col-md-4">
                        <div class="d-md-none m--margin-bottom-10"></div>
					</div>
					<div class="col-md-4">
                        <div class="d-md-none m--margin-bottom-10"></div>
					</div>
				</div>
			</div>
			<div class="col-xl-4 order-1 order-xl-2 m--align-right">
				<div class="m-separator m-separator--dashed d-xl-none"></div>
			</div>
		</div>
	</div>
	<!--end: Search Form -->
      <!--begin: Datatable -->
	<div class="row">
		 <input type="hidden" name="action" value="checkin" />
		 <div class="col-md-12" id="TimeSheetManagement">
		 </div>
	</div>
	<!--end: Datatable -->
 	<div class="row">
 		<div class="col-md-6" align="left">
 		
 		</div>
 		<div class="col-md-6" align="right">
 			<button type="button" name="btn_checkin" id="BTN_CHECKIN" class="btn btn-info">Checkin/Checkout</button>
 		</div>
 	</div>
	</div>
</div>
<div class="modal fade" id="CheckOutInfo" tabindex="-1" role="dialog" aria-labelledby="CheckOutInfoModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InserItemsModalLabel">
					CheckIn/CheckOut Information
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_checkin_timesheet" id="FRM_CHECKIN_TIMESHEET" method="post"  enctype="multipart/form-data"> 
				    {!! csrf_field() !!}
				    <input type="hidden" name="action" value="checkout" />
				 	<div class="row"> 
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> Day Type </label><br/>
                                     <select class="bs-select form-control" name="ts_day_type" id="TS_DAY_TYPE"  style="width:100%" data-actions-box="true">
                                            <option value=""> -- Day Type -- </option>
                                            @foreach($list_days_type as $key => $day_type)
                                                    <option value="{{ $day_type->dt_id }}">{{ $day_type->dt_day_type }}</option>
                                            @endforeach
                                    </select>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> Note </label><br/>
                                    <textarea name="timesheet_note" class="form-control" style="width:100%;height:150px;"></textarea>
                                </div>
				 		</div>
				 		<div class="col-md-6"></div>
				 		<div class="col-md-6">
				 			<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
            					Close
            				</button>
            				<button type="submit" name="btn_save_checkin" id="BTN_SAVE_CHECKIN" class="btn btn-primary">
            					Confirm
            				</button>
				 		</div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">
				
			</div>
		</div>
	</div>
</div>
@endsection