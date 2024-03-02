<?php
/***********************************************************
holidayemployees.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

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
</style>
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/timesheetmanagement.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/holidayemployees.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Holiday Employees</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              	              		<li><a class="dropdown-item" data-action_type="PRINT" href="#">Print</a></li>
                    <li><a class="dropdown-item" data-action_type="EXPORT_AS_CSV" href="#">Export As CSV</a></li>
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
                                <label class="control-label"> Year <span class="required"> * </span></label><br/>
                                <select name="ts_year" class="form-control" id="TS_YEAR">
                                	<option value="{{ date('Y') - 3 }}">{{ date('Y') - 3 }}</option>
                                	<option value="{{ date('Y') - 2 }}">{{ date('Y') - 2 }}</option>
                                	<option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                                	<option selected="selected" value="{{ date('Y') }}">{{ date('Y') }}</option>
                                </select>
                            </div>
						</div> 
						<div class="col-md-4" style="text-align: left">
							<label class="control-label">&nbsp;</label><br/>
                            <button type="button" name="btn_search" class="btn btn-primary"> Search </button>
						</div>
					</div>
				</div>
				<div class="col-xl-12 order-1 order-xl-2 align-right table-responsive">
				<table class="table table-striped gy-7 gs-7">
            		<thead>
            			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                			<th>#</th>
                			<th>User Fullname</th>
                			<th>Number Of holidays Allowed</th>
                			<th>Number Of holidays taken</th>
                			<th>Number Of holidays Remaining</th>
                		</tr>
                	</thead>
                	<tbody id="LstHolidayEmployees">
                
                	</tbody>
                </table>
				</div>
			</div>
		</div>
    </div>
</div>
 
@endsection