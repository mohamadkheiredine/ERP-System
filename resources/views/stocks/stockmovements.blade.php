<?php
/***********************************************************
stockmovements.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 24, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Products Management > Stock Movement"])

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
<script type="text/javascript" src="{{ url('js/modules/products.module.js') }}"></script>
<script type="text/javascript">
$(function(){
	products_module.DisplayAllStockMovement();
	$(".DownloadTransferStock").on("click",products_module.DownloadTransferStock);
})
</script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Stock Movement</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              		<li><a class="dropdown-item DownloadTransferStock" data-action_type="PRINT" href="#">Download Transfer Voucher</a></li>
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

												</div>
												<div class="col-md-4">

												</div>
											</div>
										</div>
										<div class="col-xl-4 order-1 order-xl-2 align-right">
											<a href="{{ url('inventory/transferstock') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														Move Stock
													</span>
												</span>
											</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
									</div>
								</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div  id="LstProductsMain" class="table-responsive">
									<div class="table-responsive">
                                	<table class="table table-rounded table-striped border gy-7 gs-7">
                                		<thead>
                                			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                		<thead>
                                			<tr>
                                				<th>#</th>
                                				<th title="Date" > Date </th>
                                				<th title="User" > User </th>
                                				<th title="Warehouse"> Warehouse Source  </th>
                                				<th title="Warehouse" > Warehouse Destination  </th>
                                				<th title="Total Quantity" > Total Quantity  </th>
                                			</tr>
                                		</thead>
                                    	<tbody  id="LstTransferStocks" ></tbody>
                                    </table>

								</div>
								<!--end: Datatable -->
								<div class="row">
									<div class="col-md-8"></div>
									<div class="col-md-4" align="right">
										<a href="{{ url('inventory/transferstock') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														Move Stock
													</span>
												</span>
											</a>
									</div>
								</div>
  	</div>
</div>
</div>

@endsection
