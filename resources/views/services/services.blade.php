<?php
/***********************************************************
services.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 12, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Services Management"])

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
<script type="text/javascript" src="{{ url('js/modules/services.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/services.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Services</h3>
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
                                                   <br/>
												</div>
												<div class="col-md-4">
                                                   <br/>
												</div>
											</div>
										</div>
										<div class="col-xl-4 order-1 order-xl-2 align-right">
											<a href="{{ url('crm/services/addform') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Service
													</span>
												</span>
											</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">&nbsp;</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div class="table-responsive">
                                        <table class="table" id="html_table" width="100%">
                                        		<thead>
                                        			<tr class="fw-bold fs-6 text-gray-800">
                                        			<th title="#">#</th>
                                        				<th title="Id" >ID</th>
                                        				<th title="code">Service Code</th>
                                        				<th title="Name">Service Name</th> 
                                        				<th title="edit">edit</th>
                                        				<th title="delete">Delete</th>
                                        			</tr>
                                        		</thead>
                                        		<tbody id="LstServices">
                                        
                                        
                                        
                                        			</tbody>
                                        </table>
								</div>
								<!--end: Datatable -->
    </div>
    </div>
@endsection