<?php
/***********************************************************
itemscategory.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 8, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
Display List Items inside selected category
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Products Management"])

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
<script type="text/javascript" src="{{ url('js/modules/itemscategory.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/inventory/itemscategory.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Products</h3>
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
		<span id="hidden_fields">
			<input type="hidden" name='page_number' value="1" />
			<input type="hidden" name="pc_id" value="{{ $pc_id }}" />
		</span>
		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
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
					 
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div  id="LstProductsMain" class="table-responsive">
			<table class="table" id="html_table" width="100%">
        		<thead>
        			<tr>
        				<th title="#">#</th>
        				<th title="Id"> ID </th> 
        				<th title="Name"> Product Name  </th>
        				<th title="Price"> Selling Price </th>
        				<th title="Discount"> Discount </th>
        				<th title="Price"> WholeSales Price </th>
        				<th title="Price"> Vendor Price </th>
        				<th title="edit"> edit </th>
        			</tr>
        		</thead>
            	<tbody  id="LstProducts" ></tbody>
            </table>
            									
		</div>
		<div class="row">
             <div class="col-md-10" align="left">
                <ul id="ProductsPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-2" align="right"></div>
         </div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-md-8"></div>
			<div class="col-md-4" align="right">
				<button type="button" name="btn_new_product" class="btn btn-info" >
					<span>
						<i class="fa fa-code-fork"></i>
						<span>
							New Product
						</span>
					</span>
				</button>
			</div>
		</div>
    </div>
</div>
 
@endsection