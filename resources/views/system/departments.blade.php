<?php
/***********************************************************
departments.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 2, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Departments Management"])

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
<script type="text/javascript" src="{{ url('js/modules/departments.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/system/departments.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Departments</h3>
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
    					 <span id="hidden_fields">
                                <input type="hidden" name="page_number" value="1" />
                            </span>
								<!--begin: Search Form -->
								<div class="form">
									<div class="row align-items-center">
										<div class="col-xl-8 order-2 order-xl-1">
											<div class="form-group m-form__group row align-items-center">
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

												</div>
												<div class="col-md-4">
                                                  

												</div>
											</div>
										</div>
										<div class="col-xl-4 order-1 order-xl-2 align-right">
											<a href="{{ url('system/departments/addform') }}" class="btn btn-info">
												<span>
													<i class="flaticon-grid-menu-v2"></i>
													<span>
														New Department
													</span>
												</span>
											</a>
										</div>
									</div>
								</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div class="table-responsive">
									<table class="table">
                						<thead>
                							<tr class="fw-bold fs-6 text-gray-800">
                								<th style="width:2px;">#</th>
                								<th style="width:2px;">ID</th>
                								<th>Department Title</th>
                								<th>edit</th>
                								<th>Delete</th>
                							</tr>
                						</thead>
                						<tbody  class="LstDepartmentsGrid"></tbody>
                					</table>
								</div>
							    <div class="row">
                                     <div class="col-md-10" align="left">
                                        <ul id="DepartmentsPagination" class="pagination-sm"></ul>
                                     </div>
                                     <div class="col-md-2" align="right"></div>
                                 </div>
								<!--end: Datatable -->
								<div class="row">
									<div class="col-xl-8 order-1 order-xl-1 align-right"></div>
								<div class="col-xl-2 order-2 order-xl-2 align-right">
									<a href="{{ url('system/departments/drawhierarchy') }}" class="btn btn-warning">
										<span>
											<i class="fas fa-sitemap"></i>
											<span>
												Draw Hierarchy
											</span>
										</span>
									</a>
								</div>
								<div class="col-xl-2 order-3 order-xl-3 m--align-right">
									<a href="{{ url('system/departments/addform') }}" class="btn btn-info">
										<span>
											<i class="flaticon-grid-menu-v2"></i>
											<span>
												New Department
											</span>
										</span>
									</a>
									<div class="m-separator m-separator--dashed d-xl-none"></div>
								</div>
								
								</div>
    
    
    </div>
</div>

@endsection