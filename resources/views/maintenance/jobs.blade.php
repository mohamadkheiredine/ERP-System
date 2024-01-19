<?php
/***********************************************************
jobs.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 5, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/



?>

@extends('layouts.layout',['page_title' => "Jobs Management"])

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
<script type="text/javascript" src="{{ url('js/modules/jobs.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/maintenance/jobs.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Maintenance Jobs</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              	<li><a class="dropdown-item quickactions" data-action_type="PRINT_REQUEST" href="#">Print Request</a></li>
                <li><a class="dropdown-item quickactions" data-action_type="PRINT_ORDER" href="#">Print Order</a></li>
                <li><a class="dropdown-item quickactions" data-action_type="EXPORT_CSV" href="#">Export As CSV</a></li> 
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    	<div class="row">
    		<div class="col-md-12">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group row align-items-center">
						<div class="col-md-4">
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
						<div class="col-md-4">
							<select class="bs-select form-control" id="JOB_STATUS" name="job_status">
                    			<option value="0">-- Select Job Status --</option>
                                @foreach($lst_job_status as $index => $js_info)
                                  <option value="{{ $js_info->js_id }}">{{ $js_info->js_status_title }}</option>
                                @endforeach
                            </select>
                           <br/>
						</div>
						<div class="col-md-4">
							<select class="bs-select form-control" id="CUSTOMERS" name="customers">
                    			<option value="0">-- Select Customer --</option>
                                @foreach($lst_customers as $index => $ic_info)
                                  <option value="{{ $ic_info->ic_id }}">{{ $ic_info->ic_customer_name }}</option>
                                @endforeach
                            </select>
                           <br/>
						</div>
						<div class="col-md-12" style="height:10px;"></div>
						<div class="col-md-4">
                            <br/>
								<input type="text" name="j_due_date" id="J_DUE_DATE" class="form-control" required="required" maxlength="10"  value="" />
                            <br/>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 align-right">
					<a href="{{ url('maintenance/jobs/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-tools"></i>
							<span>
								New Jobs
							</span>
						</span>
					</a>
					<br/>
				</div>
			</div>
		</div>
    	</div>
		<div class="row">
			<div class="col-md-12" style="height:10px;"></div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div class="row">
			<div class="col-md-12 table-responsive">
			<table class="table table-striped gy-7 gs-7">
            		<thead>
            			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            				<th title="#">#</th>
            				<th title="Id"> ID </th>
            				<th title="Job Code"> Job Code </th>
            				<th title="Job Label"> Job Label </th>
            				<th title="Customer"> Customer </th>
            				<th title="Due Date"> Due Date </th>
            				<th style="width:2px;" nowrap title="#"> edit </th>
            				<th style="width:2px;" nowrap title="#"> Delete </th>
            			</tr>
            		</thead>
            		<tbody  id="LstMaintenanceJobs">
            
            		</tbody>
            </table>
			</div>
		</div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-md-12 col-lg-12 col-xs-12" align="right">
				<a href="{{ url('maintenance/jobs/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-tools"></i>
							<span>
								New Job
							</span>
						</span>
					</a>
			</div>
		</div>
    </div>
</div>
@endsection