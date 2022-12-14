<?php
/***********************************************************
timesheetmanagement.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Manage the timesheet for all users
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Admin Timesheet Management"])

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
<script type="text/javascript" src="{{ url('js/modules/timesheetmanagement.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/admintimesheet.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
<div class="m-portlet__head">
	<div class="m-portlet__head-caption">
		<div class="m-portlet__head-title">
			<h3 class="m-portlet__head-text">
				Admin Timesheet Management 
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
						<div class="form-group">
                            <label> User</label>
                            <select class="bs-select form-control" name="ts_user" id="TS_USER" required="required" data-actions-box="true">
                                    <option value="">-- Select User --</option>
                                    @foreach ( $list_users as $key => $user_info )
                                            <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                    @endforeach
                            </select>
                        </div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
						 	<label> Date </label>
                       		<input type="text" name="ts_date" value="{{ date("Y-m-d") }}" class="form-control" />
						</div>
					</div>
					<div class="col-md-4">
						<br/>
						<button type="button" class="btn btn-success" style="width:120px;" name="btn_find" id="BTN_FIND">Find</button>
					</div>
				</div>
			</div>
			<div class="col-xl-4 order-1 order-xl-2 m--align-right">
				<div class="m-separator m-separator--dashed d-xl-none"></div>
			</div>
		</div>
	</div>
	<!--end: Search Form -->
     <form name="frm_timesheet_management" id="FRM_TIMESHEET_MANAGEMENT">
     	<span id="hidden_fields">
     		{!! csrf_field() !!}
     	</span>
     	<div class="row">
    		 <div class="col-md-12" id="TimeSheetManagement">
    		 </div>
    	</div>
     </form>
	<!--end: Datatable --> 
	</div>
</div>
@endsection