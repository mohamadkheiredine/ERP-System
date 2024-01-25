<?php
/***********************************************************
companies.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page to manage companies 
***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Companies Management"])

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
<script type="text/javascript" src="{{ url('js/modules/companies.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/system/companies.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Companies Management</h3>
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
    <div class="row">
									<div class="row align-items-center">
										<div class="col-xl-8 order-2 order-xl-1">
											<div class="form-group m-form__group row align-items-center">
												<div class="col-md-4">
												<div class="m-input-icon m-input-icon--left">
														<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
														<span class="m-input-icon__icon m-input-icon__icon--right">
															<span>
																<i class="la la-search"></i>
															</span>
														</span>
													</div>

												</div>
												<div class="col-md-4">
                                                    <div class="d-md-none m--margin-bottom-10"></div>
												</div>
												<div class="col-md-4">
                                                    <div class="d-md-none m--margin-bottom-10"></div>
												</div>
											</div>
										</div>
										<div class="col-xl-4 order-1 order-xl-2 align-right">
											<a href="{{ url('system/companies/addform') }}" class="btn btn-info">
												<span>
													<i class="fa fa-building"></i>
													<span>
														New Company
													</span>
												</span>
											</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
									</div>
								</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div class="table-responsive">
									<table class="table">
                						<thead>
                							<tr class="fw-bold fs-6 text-gray-800">
                								<th><input type="checkbox" name="ck_cmp_all" id="CK_CMP_ALL" class="group-checkable" value="1" /></th>
                								<th>ID</th>
                								<th>Company Name</th>
                								<th>Company Owner</th>
                								<th>edit</th>
                								<th>Delete</th>
                							</tr>
                						</thead>
                						<tbody  class="LstCompaniesGrid"></tbody>
                					</table>
								</div>
								<!--end: Datatable -->
								<div class="col-xl-12 order-1 order-xl-12 align-right">
											<a href="{{ url('system/companies/addform') }}" class="btn btn-info">
												<span>
													<i class="fa fa-building"></i>
													<span>
														New Company
													</span>
												</span>
											</a>
										</div>
    </div>
</div>
@endsection