<?php
/***********************************************************
statuses.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/




?>

@extends('layouts.layout',['page_title' => "Supplier Status Management"])

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
<script type="text/javascript" src="{{ url('js/modules/supplierstatus.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/srm/supplierstatus.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Supplier Status Management</h3>
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
						<a href="{{ url('srm/suppliers/addstatus') }}" class="btn btn-info">
							<span>
								<i class="fas fa-user"></i>
								<span>
									New Status
								</span>
							</span>
						</a>
					</div>
				</div>
			</div>
			<!--end: Search Form -->
              <!--begin: Datatable -->
			<div class="table-responsive" >
                <table class="table" id="html_table" width="50%">
                		<thead>
                			<tr>
                				<th title="#">#</th>
                				<th title="Id"> ID </th>
                				<th title="Status Name"> Status Name </th>
                				<th style="width:2px;" nowrap title="#"> edit </th>
                				<th style="width:2px;" nowrap title="#"> Delete </th>
                			</tr>
                		</thead>
                		<tbody id="LstSupplierStatuses">
                
                		</tbody>
                </table>
			</div>
			<div class="row">
			<div class="col-xl-12 col-md-12 order-1 order-xl-2 align-right">
						<a href="{{ url('srm/suppliers/addstatus') }}" class="btn btn-info">
							<span>
								<i class="fas fa-user"></i>
								<span>
									New Status
								</span>
							</span>
						</a>
					</div>
			</div>
    </div>
</div>
@endsection