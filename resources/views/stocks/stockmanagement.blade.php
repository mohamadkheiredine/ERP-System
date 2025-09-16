<?php
/***********************************************************
stockmanagement.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 23, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

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
<script type="text/javascript" src="{{ url('js/modules/products.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/inventory/stockmanagement.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Stock Management</h3>
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
    <span id="hiddenP_fields">
			<input type="hidden" name="page_number" id="PAGE_NUMBER" value="1" />
			<input type="hidden" name="list_type" id="LIST_TYPE" value="list" />
		</span>
		<!--begin: Search Form -->
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
                            <br/>
                        </div>
						<div class="col-md-4">
							<select  name="stock_warehouse" id="STOCK_WAREHOUSE" data-actions-box="true" class="form-control form-select" data-control="select2" data-placeholder="Select warehouse">
                                    <option value="0">Select Warehouse</option>
                                    @foreach ( $lst_warehouse as $key => $warehouse_info )
                                            <option value="{{ $warehouse_info->w_id  }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                            </select>
						</div>
						<div class="col-md-4">
							<select  name="stock_product" id="STOCK_PRODUCT" class="form-control form-select" data-control="select2" data-placeholder="Select Product" >
                                    <option value="0">Select Product</option>
                                    @foreach ( $lst_products as $key => $product_info )
                                            <option value="{{ $product_info->p_id  }}">{{  $product_info->p_product_name  }}</option>
                                    @endforeach
                            </select>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-12" style="height:15px;">&nbsp</div>
						<div class="col-md-4">
							<select name="stock_currency" id="STOCK_CURRENCY" class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                    <option value="0">Select Currency</option>
                                    <?php foreach ( $lst_currencies as $key => $currency_info ) { ?>
                                            <option value="<?php echo $currency_info->cc_id;  ?>"><?php echo $currency_info->cc_currency_code;  ?>&nbsp;-&nbsp;<?php echo $currency_info->cc_currency_name;  ?></option>
                                    <?php  } ?>
                            </select>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
					</div>
				</div>
				<div class="col-xl-12 order-1 order-xl-2 align-right">
					<a href="{{ url('inventory/addstock') }}" class="btn btn-success">
						<span>
							<i class="fas fa-user"></i>
							<span>
								Create Stock
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
                <div class="col-xl-12 order-1 order-xl-2 align-right">&nbsp;</div>
                <div class="col-xl-12 order-1 order-xl-2 align-right">
                    <a href="#" class="SwitchView" title="List Stock By Group" data-view="group"><i class="fas fa-object-group" style="font-size:24px;" ></i></a>
                    <a href="#" class="SwitchView" title="list stock by transaction" data-view="list"><i class="fas fa-list" style="font-size:24px;" ></i></a>
                </div>
                <div class="col-xl-12 order-1 order-xl-2 align-right">&nbsp;</div>
			</div>
		</div>
        <div class="row">
            <div class="col-md-12" align="left">
                <ul id="TopStocksPagination" class="pagination-sm"></ul>
            </div>
        </div>
		<div  class="table-responsive" id="LstProductsMain">
            <table class="table table-striped gy-7 gs-7">
                <thead>
                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
        				<th style="width:10px">#</th>
        				<th title="Id" style="width:10px"> ID </th>
        				<th title="Product" style="width:250px;white-space: nowrap;"> Product </th>
        				<th title="Warehouse" style="width:150px;white-space: nowrap;"> Warehouse  </th>
        				<th title="Price" style="width:150px;white-space: nowrap;"> Price </th>
        				<th title="Item Price" style="width:150px;white-space: nowrap;"> Item Price </th>
        				<th title="Quantity" style="width:150px;white-space: nowrap;"> Quantity </th>
        				<th style="width:4px !important;" nowrap title="#"> edit </th>
        				<th style="width:4px !important;" nowrap title="#"> Delete </th>
        			</tr>
        		</thead>
            	<tbody  id="LstProductStocks" ></tbody>
            </table>

		</div>
        <div class="row">
            <div class="col-md-12" style="height:20px">&nbsp;</div>
        </div>
		<div class="row">
             <div class="col-md-6" align="left">
                <ul id="StocksPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-6" align="right">
             	<span class="TotalCost"></span>
             </div>
         </div>
        <div class="row">
            <div class="col-md-12" style="height:20px">&nbsp;</div>
        </div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-md-8"></div>
			<div class="col-md-4" align="right">
				<a href="{{ url('inventory/addstock') }}" class="btn btn-success">
						<span>
							<i class="fas fa-user"></i>
							<span>
								Create Stock
							</span>
						</span>
					</a>
			</div>
		</div>
    </div>
</div>

@endsection
