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

@extends('layouts.layout',['page_title' => "Warehouse Management"])


@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/warehouses.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/inventory/warehousesmanagement.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Warehouses</h3>
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
											<a href="{{ url('inventory/addnewwarehouse') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Warehouse
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
										<table class="table" width="100%">
                                        		<thead>
                                        			<tr class="fw-bold fs-6 text-gray-800">
                                        				<th title="#">#</th>
                                        				<th title="Id">ID</th>
                                        				<th title="Name">Warehouse Name</th>
                                        				<th title="City">warehouse City</th>
                                        				<th title="Status">Status</th>
                                        				<th title="edit">edit</th>
                                        				<th title="settings">settings</th>
                                        				<th title="delete">Delete</th>
                                        			</tr>
                                        		</thead>
                                        		<tbody id="LstWarehouses">
                                        
                                        
                                        
                                        			</tbody>
                                        </table>
								</div>
								<!--end: Datatable -->
								<div class="row">
									<div class="col-xl-12 order-1 order-xl-12 align-right">
											<a href="{{ url('inventory/addnewwarehouse') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Warehouse
													</span>
												</span>
											</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
								</div>
    </div>
</div>
 
@endsection