<?php
/***********************************************************
casestatus.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
Cost Center Categories Management 
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Call Center Management"])

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
<script type="text/javascript" src="{{ url('js/modules/casestatus.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/callcenter/casestatus.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
	<div class="card-header">
		<h3 class="card-title">Case Status Management</h3>
		<div class="card-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle"
					data-bs-toggle="dropdown" aria-expanded="false">Action</button>
				<ul class="dropdown-menu">
				</ul>
			</div>
		</div>
	</div>
	<div class="card-body">
		<span id="hidden_fields"> 
			<input type="hidden" name="page_number" value="1" />
		</span>
		<!--begin: Search Form -->
		<div class="form">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
								<label>&nbsp;</label>
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
                                                    <label class="control-label">Case</label>
                                                    <select name="fk_parent_status" id="FK_PARENT_STATUS"  class="form-control form-select" data-control="select2" data-placeholder="Select Parent Status">
                                                           <option value="">No Parent</option>
                                                           @foreach ( $lst_statuses as $key => $status_info )
                                                                   <option value="{{ $status_info->cc_id }}">{{ $status_info->cc_status_title }}</option>
                                                           @endforeach
                                                   </select>
						</div>
						<div class="col-md-4">
                                                    
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 align-right">
					<a href="{{ url('/callcenter/casestatus/addform') }}"
						class="btn btn-info"> <span> <i class="flaticon-grid-menu-v2"></i>
							<span> New Status </span>
					</span>
					</a>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
		<!--begin: Datatable -->
		<div class="table-responsive">
			<table class="table table-striped gy-7 gs-7">
				<thead>
					<tr
						class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
						<th style="width: 2px;">#</th>
						<th style="width: 2px;">ID</th>
						<th>Parent Status</th>
						<th>Status Label</th>
						<th style="width: 2px;white-space: nowrap;">edit</th>
						<th style="width: 2px;white-space: nowrap;">Delete</th>
					</tr>
				</thead>
				<tbody class="LstCaseStatus" id="LstCaseStatus"></tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-md-10" align="left">
				<ul id="CaseStatusPagination" class="pagination-sm"></ul>
			</div>
			<div class="col-md-2" align="right"></div>
		</div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-xl-8 order-1 order-xl-1 align-right"></div>
			<div class="col-xl-2 order-2 order-xl-2 align-right">
				 
			</div>
			<div class="col-xl-2 order-3 order-xl-3 align-right">
				<a href="{{ url('/callcenter/casestatus/addform') }}"
					class="btn btn-info"> <span> <i class="flaticon-grid-menu-v2"></i>
						<span> New Status </span>
				</span>
				</a> 
			</div>
		</div>
	</div>
</div>

@endsection