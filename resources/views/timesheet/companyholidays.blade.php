<?php
/***********************************************************
 companyholidays.blade.php
 Product :
 Version : 1.0
 Release : 1
 Date Created : Jul 5, 2019
 Developed By  : Mohamad Mantach   PHP Department itm Solutions
 All Rights Reserved ,   itm Solutions COPYRIGHT 2019
 
 Page Description :
 
 ***********************************************************/
?>


@extends('layouts.layout',['page_title' => "Company Holidays"])

@section('themes')
<style>
th {
	cursor: pointer;
}

#ModelPopUp {
	width: 800px;
}
</style>
@endsection @section('plugins')
<script type="text/javascript"
	src="{{ url('js/modules/companyholidays.module.js') }}"></script>
<script type="text/javascript"
	src="{{ url('js/libraries/timesheet/companyholidays.js') }}"></script>
@endsection @section('content')
<div class="card shadow-sm">
	<div class="card-header">
		<h3 class="card-title">Company Holidays</h3>
		<div class="card-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle"
					data-bs-toggle="dropdown" aria-expanded="false">Action</button>
				<ul class="dropdown-menu"></ul>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div
			class="col-md-12">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
							<div class="d-flex align-items-center">
								<!--begin::Input group-->
								<div class="position-relative w-md-400px me-md-2">
									<i
										class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
										<span class="path1"></span> <span class="path2"></span>
									</i> <input type="text"
										class="form-control form-control-solid ps-10"
										name="general_search" id="generalSearch" value=""
										placeholder="Search" />
								</div>
								<!--end::Input group-->
							</div>
						</div>
						<div class="col-md-4">
							<div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
							<select name="holiday_year" id="HOLIDAY_YEAR"
								class="form-control">
								<option value="{{ date('Y') - 1 }}">{{ date("Y") - 1 }}</option>
								<option selected="selected" value="{{ date('Y') }}">{{ date("Y")
									}}</option>
								<option value="{{ date('Y') + 1 }}">{{ date("Y") + 1 }}</option>
								<option value="{{ date('Y') + 2 }}">{{ date("Y") + 2 }}</option>
								<option value="{{ date('Y') + 3 }}">{{ date("Y") + 3 }}</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 align-right">
					<a href="{{ url('timesheet/holidays/addform') }}"
						class="btn btn-info"> <span> <i class="fas fa-user"></i> <span>
								New Holiday </span>
					</span>
					</a> <br />
				</div>
			</div>
		</div>
		<!--end: Search Form -->
		<!--begin: Datatable -->
		<div class="col-md-12 table-responsive">
			<table class="table table-striped gy-7 gs-7">
				<thead>
					<tr
						class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
						<th>#</th>
						<th>ID</th>
						<th>year</th>
						<th>Holiday Title</th>
						<th>Holiday Date</th>
						<th>edit</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody class="LstHolidaysGrid"></tbody>
			</table>
		</div>
		<!--end: Datatable -->
		<div class="col-xl-12 order-1 order-xl-12 align-right">
			<a href="{{ url('timesheet/holidays/addform') }}"
				class="btn btn-info"> <span> <i class="fas fa-user"></i> <span> New
						Holiday </span>
			</span>
			</a> <br />
		</div>
	</div>
</div>

@endsection
