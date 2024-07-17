<?php
/***********************************************************
customers.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Vendor Page to Manage Vendors saved in the database
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Customers Management"])

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
<script type="text/javascript" src="{{ url('js/modules/customers.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/inventory/customers.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Customers Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
                    <li><a class="dropdown-item" data-action_type="EXPORT_AS_CSV" href="#">Export As CSV</a></li>
                    <li><a class="dropdown-item" data-action_type="IMPORT" href="#" data-bs-toggle="modal" data-bs-target="#modal_import">Import</a></li>
                    <li><a class="dropdown-item" data-action_type="DOWNLOAD_TEMPLATE" href="#">Download Import Template</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    	 <span id="hidden_fields">
                                <input type="hidden" name="page_number" value="1" />
                            </span>
								<!--begin: Search Form -->
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
											<input type="text" class="form-control form-control-solid ps-10" name="search_query" id="generalSearch" value="" placeholder="Search" />
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
											<a href="{{ url('inventory/customers/addform') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Customer
													</span>
												</span>
											</a>
										 
										</div>
									</div>
								</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div class="row">
                                <div class="col-md-12 tabel-responsive"  id="LstallCustomers">
                               			<table class="table table-row-dashed table-row-gray-300 gy-7">
                            <thead>
                              <tr class="fw-bold fs-6 text-gray-800">
                                    				<th title="#">#</th>
                                    				<th title="Id"> ID </th>
                                    				<th title="Customer Code"> Customer Code </th>
                                    				<th title="Customer Name"> Customer Name </th>
                                    				<th title="Customer Account"> Customer Email </th>
                                    				<th title="Customer Phone"> Customer Phone </th>
                                    				<th title="Edit"> Edit </th> 
                                    				<th title="Delete"> Delete </th> 
                                    			</tr>
                                    		</thead>
                                    		<tbody id="LstCustomers">
                                    			  
                                    		</tbody>
                                    </table>
                                </div>
								</div>
								
								 <div class="row">
                                     <div class="col-md-10" align="left">
                                        <ul id="CustomersPagination" class="pagination-sm"></ul>
                                     </div>
                                     <div class="col-md-2" align="right"></div>
                                 </div>
								<!--end: Datatable -->
								<div class="row">
									<div class="col-md-12" align="right">
										<a href="{{ url('inventory/customers/addform') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Customer
													</span>
												</span>
											</a>
									</div>
								</div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="modal_import">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Import List Customers</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form name="FORM_IMPORT_CUSTOMERS" id="FORM_IMPORT_CUSTOMERS">
                             <span id="hidden_fields">
                                {!! csrf_field() !!} 
                            </span><div class="row">
                                <div class="col-md-12" style="height:20px;"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                     <div class="form-group">
                                        <label class="control-label">Customers List <span class="required"> * </span></label>
                                        <input type="file" name="cc_customers_list" id="CC_CUSTOMERS_LIST" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="height:20px;"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                     <div class="form-group" style="text-align:right">
                                   
                          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="btn_upload_customers" id="BTN_UPLOAD_CUSTOMERS" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </form
                    </div>
                </div>
            </div>

            <div class="modal-footer">
              
            </div>
        </div>
    </div>
</div>
@endsection