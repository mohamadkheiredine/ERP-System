<?php
/***********************************************************
categories.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



?>

@extends('layouts.layout',['page_title' => "Supplier Categories Management"])

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
<script type="text/javascript" src="{{ url('js/modules/suppliercategories.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/srm/suppliercategories.js') }}"></script>
@endsection

@section('content')


<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Supplier Categories Management</h3>
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
											<a href="{{ url('srm/suppliers/addcategory') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Category
													</span>
												</span>
											</a> 
										</div>
									</div>
								</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div class="table-responsive">
                                       <table class="table table-row-dashed table-row-gray-300 gy-7">
                                        		<thead>
                                        			<tr class="fw-bold fs-6 text-gray-800">
                                        				<th title="#">#</th>
                                        				<th title="Id"> ID </th>
                                        				<th title="Parent Category"> Parent Category </th>
                                        				<th title="Category Ref"> Category Ref </th>
                                        				<th title="Category Title"> Category Title </th>
                                        				<th style="width:2px;" nowrap title="#"> edit </th>
                                        				<th style="width:2px;" nowrap title="#"> Delete </th>
                                        			</tr>
                                        		</thead>
                                        		<tbody id="LstSupplierCategories">
                                        
                                        		</tbody>
                                        </table>
								</div>
								<div class="col-md-12" style="text-align:right">
								<a href="{{ url('srm/suppliers/addcategory') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Category
													</span>
												</span>
											</a> 
								</div>
								<!--end: Datatable -->
    </div>
</div> 
@endsection