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
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Admin Timesheet Management </h3>
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
    <div class="col-md-12">
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
                       		<input type="text" name="ts_date" id="TS_DATE" value="{{ date("m/d/Y") }}" class="form-control" />
						</div>
					</div>
					<div class="col-md-4">
						<br/>
						<button type="button" class="btn btn-success" style="width:120px;" name="btn_find" id="BTN_FIND">Find</button>
					</div>
				</div>
			</div>
			<div class="col-xl-4 order-1 order-xl-2 align-right">
				<br/>
			</div>
		</div>
	</div>
        <div class="row">
            <div class="col-md-12">
                <form name="frm_timesheet_management" id="FRM_TIMESHEET_MANAGEMENT">
                    <span id="hidden_fields">
                            {!! csrf_field() !!}
                    </span>
                    <div class="row">
                             <div class="col-md-12 table-responsive" >
                            <table class="table table-rounded table-striped border gy-7 gs-7">
                                    <thead>
                                            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                    <th style="width:10%">Date</th>
                                                    <th style="width:20%">Day Type</th>
                                                    <th style="width:20%">Checkin</th>
                                                    <th style="width:20%">Checkout</th>
                                                    <th style="width:10%">Overwork</th>
                                                    <th style="width:20%"> hours </th>
                                            </tr>
                                    </thead>
                                    <tbody id="TimeSheetManagement"></tbody>
                            </table>
                             </div>
                    </div>
                 </form>
            </div>
        </div>
     
    </div>
 </div>
 
@endsection