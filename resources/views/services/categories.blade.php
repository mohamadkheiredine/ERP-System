<?php
/***********************************************************
categories.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Service Categories Management"])

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
<script type="text/javascript" src="{{ url('js/modules/servicecategories.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/servicecategories.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Service Categories</h3>
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
											<a href="{{ url('crm/services/addcategory') }}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Category
													</span>
												</span>
											</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
									</div>
								</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable --> 
								<div class="table-responsive col-md-12">
                                    <table class="table" id="html_table" width="100%">
                                    		<thead>
                                    			<tr class="fw-bold fs-6 text-gray-800">
                                    				<th title="Id">ID</th>
                                    				<th title="ref">Category ref</th>
                                    				<th title="Name">Category Name</th>
                                    				<th title="edit">edit</th>
                                    				<th title="delete">Delete</th>
                                    			</tr>
                                    		</thead>
                                    		<tbody  id="LstServiceCategories">
                                    
                                    
                                    
                                    			</tbody>
                                    </table>

								</div> 
								<!--end: Datatable -->
    
    
    </div>
</div>
 
@endsection