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
        <h3 class="card-title">purchase Quotations</h3>
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
						<label></label>
						<div class="m-input-icon m-input-icon--left">
								<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." name="general_search" id="generalSearch">
								<span class="m-input-icon__icon m-input-icon__icon--right">
									<span>
										<i class="la la-search"></i>
									</span>
								</span>
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
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					<br/>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div id="LstQuotations">

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
								New Pruchase Quotation
							</span>
						</span>
					</a>
			</div>
		</div>
    </div>
</div>

@endsection