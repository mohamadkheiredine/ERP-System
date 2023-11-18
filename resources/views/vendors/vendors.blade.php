<?php
/***********************************************************
vendors.blade.php
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


@extends('layouts.layout',['page_title' => "Vendors Management"])

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
<script type="text/javascript" src="{{ url('js/modules/vendors.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/inventory/vendors.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Vendors Management</h3>
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
						<a href="{{ url('inventory/vendors/addform') }}" class="btn btn-info">
							<span>
								<i class="fas fa-user"></i>
								<span>
									New Vendor
								</span>
							</span>
						</a>
						<div class="m-separator m-separator--dashed d-xl-none"></div>
					</div>
				</div>
			</div>
			<!--end: Search Form -->
              <!--begin: Datatable -->
			<div class="col-md-12">
                    <div class="table-responsive" id="LstallVendors">
                    <table class="table" id="VendorsDatatables" width="100%">
                        		<thead>
                        			<tr>
                        				<th title="#">#</th>
                        				<th title="Account"> Account </th>
                        				<th title="Vendor Code"> Vendor Code </th> 
                        				<th title="Vendor Name"> Vendor Name </th>
                        				<th title="Vendor Phone"> Vendor Phone </th>
                        				<th title="Edit"> Edit </th> 
                        				<th title="Delete"> Delete </th> 
                        			</tr>
                        		</thead>
                        		<tbody id="LstVendors">
                        			  
                        		</tbody>
                        </table>
                    </div>
			</div>
			<!--end: Datatable -->
			<div class="row">
				<div class="col-md-12" align="right">
					<a href="{{ url('inventory/vendors/addform') }}" class="btn btn-info">
							<span>
								<i class="fas fa-user"></i>
								<span>
									New Vendor
								</span>
							</span>
						</a>
				</div>
			</div>
    </div>
 </div>

@endsection