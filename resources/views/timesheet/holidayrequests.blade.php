<?php
/***********************************************************
holidayrequests.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 3, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



?>

@extends('layouts.layout',['page_title' => "Holiday Requests"])

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
<script type="text/javascript" src="{{ url('js/libraries/timesheet/holidayrequest.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
	<div class="card-header">
		<h3 class="card-title">Holiday Requests</h3>
		<div class="card-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle"
					data-bs-toggle="dropdown" aria-expanded="false">Action</button>
				<ul class="dropdown-menu">
					<li><a class="dropdown-item" data-action_type="PRINT" href="#">Print</a></li>
                    <li><a class="dropdown-item" data-action_type="EXPORT_AS_CSV" href="#">Export As CSV</a></li>
                    <li><a class="dropdown-item" data-action_type="IMPORT" href="#">Import</a></li>
                    <li><a class="dropdown-item" data-action_type="CHANGE_REQUEST_STATUS" href="#">Change Request Status</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="card-body">
	<!--begin: Search Form -->
		<div class="col-md-12">
			<div class="row align-items-center">
				<div class="col-xl-12">
					<div class="form-group row align-items-center">
						<div class="col-md-3">
							<label>&nbsp;</label>
					 		<div class="d-flex align-items-center">
								<!--begin::Input group-->
								<div class="position-relative w-md-400px me-md-2">
									<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									<input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" />
								</div>
								<!--end::Input group-->
							</div>
						</div>
						<div class="col-md-3">
                           	<label class="control-label">Department</label>
                            <select name="hr_department_id" id="HR_DEPARTMENT_ID" class="form-control m-select2" style="width:100%">
                                <option value="">--Select One--</option>
                                @foreach( $lst_departments as $key => $dep_info)
                                 <option value="{{ $dep_info->sd_id }}">{{ $dep_info->sd_department_title }}</option>
                                @endforeach 
                            </select>
						</div>
						<div class="col-md-3">
                           	<label class="control-label">Users</label>
                            <select name="hr_user_id" id="HR_USER_ID" class="form-control m-select2" style="width:100%">
                                <option value="">--Select One--</option>
                                @foreach( $lst_users as $key => $user_info )
                                 <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach 
                            </select>
						</div>
					</div>
					<div class="col-md-3">
					<label class="control-label">Status</label>
                    <select name="hr_request_status" id="HR_REQUEST_STATUS" class="form-control m-select2" style="width:100%">
                        <option value="0">Pending</option>
                        <option value="1">Allow</option>
                        <option value="2">Denied</option>
                    </select>
				</div>
				</div>
				
			</div>
		</div>
		<div class="col-md-12" style="height:10px;"></div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div class="col-md-12 tabel-responsive">
            <table class="table table-rounded table-striped border gy-7 gs-7">
            	<thead>
					<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            			<th title="Id">#</th>
            			<th title="Id">ID</th>
            			<th title="Department">Department</th>
            			<th title="User">User</th>
            			<th title="From">From</th>
            			<th title="To">To</th>
            			<th title="Status">Status</th>
            			<th style="width:4px !important;" nowrap title="#">Details</th>
            		</tr>
            	</thead>
            	<tbody id="LstHolidayRequests">
            
            	</tbody>
            </table>
		</div>
		<div class="col-md-12" style="height:10px;"></div>
		<!--end: Datatable -->
		<div class="modal fade" id="ApproveDenyModel" tabindex="-1" role="dialog" aria-labelledby="ApproveDenyModelLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Request Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  		<form name="frm_change_status" id="FRM_CHANGE_STATUS">
                  				<input type="hidden" name="tr_request_ids" value="" />
                  			    {!! csrf_field() !!}
                          	<div class="row">
                          		<div class="col-md-12">
                          			<div class="form-group">
                          				<label class="control-label">Status</label>
                                        <select name="request_status" id="REQUEST_STATUS" class="form-control m-select2" style="width:100%">
                                            <option value="0">Pending</option>
                                            <option value="1">Allow</option>
                                            <option value="-1">Denied</option>
                                        </select>
                          			</div>
                          		</div>
                          		<div class="col-md-12">
                          			<div class="form-group">
                          				<label class="control-label">Information</label>
                                        <textarea rows="" style="width:100%;height:150px;" id="REQUEST_INFORMATION" name="request_information"></textarea>
                          			</div>
                          		</div>
                          	</div>
                          	 	</form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" name="btn_change_status" id="BTN_CHANGE_STATUS" class="btn btn-primary">Change Status</button>
                          </div>
                 
                </div>
              </div>
            </div>
	</div>
</div>
@endsection