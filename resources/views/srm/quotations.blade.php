<?php
/***********************************************************
quotations.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Quotations apply for bidding register in the Software
***********************************************************/

?>



@extends('layouts.layout',['page_title' => "Supplier Management"])

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
<script type="text/javascript" src="{{ url('js/modules/quotations.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/srm/quotations.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">purchase Invoice</h3>
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
    	<input type="hidden" name="page_number" id="PAGE_NUMBER" value="1" />
		<!--begin: Search Form -->
		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
							<label>&nbsp;</label><br/>
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
							<label>&nbsp;</label><br/>
							 <select class="bs-select form-control" name="quotation_supplier" id="QUOTATION_SUPPLIER" data-actions-box="true">
                                    <option value="">Select Supplier</option>
                                    @foreach ( $lst_suppliers as $key => $supplier_info )
                                            <option value="{{ $supplier_info->ss_id }}">{{ $supplier_info->ss_supplier_name }}</option>
                                    @endforeach
                            </select>
  							<div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
						     <label>&nbsp;</label><br/>
							 <select class="bs-select form-control" name="quotation_warehouse" id="QUOTATION_WAREHOUSE" data-actions-box="true">
                                    <option value="">Select Warehouse</option>
                                    @foreach ( $lst_warehouses as $key => $warehouse_info )
                                            <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                            </select>
                            <div class="d-md-none margin-bottom-10"></div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 align-right">
					<br/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" style="height:20px;"></div>
		</div>
		<div class="row">
			<div class="col-md-12 table-responsive">
			 	<table class="table table-row-dashed table-row-gray-300 gy-7">
                		<thead>
                			<tr class="fw-bold fs-6 text-gray-800">
                				<th title="#">#</th>
                				<th title="Id"> ID </th>
                				<th title="Invoice Number"> Invoice Number </th>
                				<th title="Container Number"> Container Number </th>
                				<th title="Supplier"> Supplier </th>
                				<th title="Date Submit"> Date Submit </th>
                				<th title="Total Price"> Total Price </th>
                				<th title="Currency"> Currency </th>
                				<th title="Status"> Status </th>
                				<th style="width:2px;" nowrap title="#"> edit </th>
                				<th style="width:2px;" nowrap title="#"> Delete </th>
                				<th style="width:2px;" nowrap title="#"> View </th>
                			</tr>
                		</thead>
                		<tbody id="LstQuotations">
                		</tbody>
                </table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12" style="height: 25px">&nbsp;</div>
		</div>
		<div class="row">
         <div class="col-md-10" align="left">
            <ul id="QuotationsPagination" class="pagination-sm"></ul>
         </div>
         <div class="col-md-2" align="right"></div>
     </div>
		<div class="row">
			<div class="col-md-12" style="height: 25px">&nbsp;</div>
		</div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-md-7"></div>
			<div class="col-md-5" align="right">
				<a href="{{ url('srm/quotation/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-user"></i>
							<span>
								New Purchase Invoice
							</span>
						</span>
					</a>
			</div>
		</div>
    </div>
</div>

@endsection
