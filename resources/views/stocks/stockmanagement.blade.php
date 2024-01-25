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
		</span>
		<!--begin: Search Form -->
		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
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
							<select class="bs-select form-control" name="stock_warehouse" id="STOCK_WAREHOUSE" data-actions-box="true">
                                    <option value="">Select Warehouse</option>
                                    @foreach ( $lst_warehouse as $key => $warehouse_info )
                                            <option value="{{ $warehouse_info->w_id  }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                            </select>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
							<select class="bs-select form-control" name="stock_product" id="STOCK_PRODUCT" data-actions-box="true">
                                    <option value="">Select Product</option>
                                    @foreach ( $lst_products as $key => $product_info )
                                            <option value="{{ $product_info->p_id  }}">{{  $product_info->p_product_name  }}</option>
                                    @endforeach
                            </select>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-12" style="height:15px;">&nbsp</div>
						<div class="col-md-4">
							<select class="bs-select form-control" name="stock_currency" id="STOCK_CURRENCY" data-actions-box="true">
                                    <option value="">Select Currency</option>
                                    <?php foreach ( $lst_currencies as $key => $currency_info ) { ?>
                                            <option value="<?php echo $currency_info->cc_id;  ?>"><?php echo $currency_info->cc_currency_code;  ?>&nbsp;-&nbsp;<?php echo $currency_info->cc_currency_name;  ?></option>
                                    <?php  } ?>
                            </select>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					<a href="{{ url('inventory/addstock') }}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
						<span>
							<i class="fas fa-user"></i>
							<span>
								Create Stock
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div  id="LstProductsMain">
			<table class="table m-table m-table--head-bg-brand" id="html_table" width="100%">
        		<thead>
        			<tr>
        				<th style="width:10px">#</th>
        				<th title="Id" style="width:10px"> ID </th>
        				<th title="Product" style="width:250px;white-space: nowrap;"> Product </th>
        				<th title="Warehouse" style="width:150px;white-space: nowrap;"> Warehouse  </th>
        				<th title="Price" style="width:150px;white-space: nowrap;"> Price </th>
        				<th title="Quantity" style="width:150px;white-space: nowrap;"> Quantity </th>
        				<th style="width:4px !important;" nowrap title="#"> edit </th> 
        				<th style="width:4px !important;" nowrap title="#"> Delete </th>
        			</tr>
        		</thead>
            	<tbody  id="LstProductStocks" ></tbody>
            </table>
            									
		</div>
		<div class="row">
             <div class="col-md-6" align="left">
                <ul id="StocksPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-6" align="right">
             	<span class="TotalCost"></span>
             </div>
         </div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-md-8"></div>
			<div class="col-md-4" align="right">
				<a href="{{ url('inventory/addstock') }}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
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