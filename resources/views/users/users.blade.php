<?php
/***********************************************************
users.blade.php
Product :
Version : 1.0
Release : 2
Date Created :Sep 6, 2018
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Users Management"])

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
<script type="text/javascript" src="{{ url('js/modules/users.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/admin/usersmanagement.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Users</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Export as CSV</a></li>
                <li><a class="dropdown-item" href="#">Download Import Template</a></li>
                <li><a class="dropdown-item" href="#">Import</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <span id="hidden_fields">
									<input type="hidden" name="page_number" value="1" />
								</span>
								<!--begin: Search Form -->
								<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
									<div class="row align-items-center">
										<div class="col-xl-8 order-2 order-xl-1">
											<div class="form-group m-form__group row align-items-center">
												<div class="col-md-4">
												<div class="m-input-icon m-input-icon--left">
														<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch" name="general_search" />
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
										<div class="col-xl-4 order-1 order-xl-2 align-right" style="text-align: right">
											<a href="{{ url('administrator/users/addform') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New User
													</span>
												</span>
											</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
									</div>
								</div>
								<!--end: Search Form -->
								<div class="col-md-12">
									
									<table class="table table-bordered table-hover" width="100%">
                                		<thead>
                                			<tr>
                                				<th style="width:4px;white-space: nowrap;"  title="Id">ID</th>
                                				<th title="Username">UserName</th>
                                				<th title="Full Name">Full Name</th>
                                				<th title="Email">email</th>
                                				<th title="Phone">Phone</th>
                                				<th style="width:4px;white-space: nowrap;"  title="Online/Offline">Online/Offline</th>
                                				<th style="width:4px;white-space: nowrap;"  title="#">edit</th>
                                				<th style="width:4px;white-space: nowrap;"  title="#">Delete</th>
                                			</tr>
                                		</thead>
                                		<tbody id="LstUsers">
										</tbody>
									</table>
								</div>
								<div class="row">
                                     <div class="col-md-10" align="left">
                                        <ul id="UsersPagination" class="pagination-sm"></ul>
                                     </div>
                                     <div class="col-md-2" align="right"></div>
                                 </div>
								<div class="row">
									<div class="col-md-8"></div>
									<div class="col-md-4" align="right">
											<a href="{{ url('administrator/users/addform') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New User
													</span>
												</span>
											</a>
									</div>
								</div>
    </div>
    <div class="card-footer">
     
    </div>
</div>
@endsection