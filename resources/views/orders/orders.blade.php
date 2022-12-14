<?php
/***********************************************************
orders.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 9, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Orders Management"])

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
<script type="text/javascript" src="{{ url('js/modules/orders.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/orders/orders.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Orders Management
				</h3>
			</div>
		</div>
		<div class="m-portlet__head-tools">
			<ul class="m-portlet__nav">
				<li class="m-portlet__nav-item">
					<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
						<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
							<i class="la la-ellipsis-h m--font-brand"></i>
						</a>
						<div class="m-dropdown__wrapper">
							<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
							<div class="m-dropdown__inner">
								<div class="m-dropdown__body">
									<div class="m-dropdown__content">
										<ul class="m-nav">
											<li class="m-nav__section m-nav__section--first">
												<span class="m-nav__section-text">
													Quick Actions
												</span>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-share"></i>
													<span class="m-nav__link-text">
														Print
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-chat-1"></i>
													<span class="m-nav__link-text">
														Export As CSV
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-multimedia-2"></i>
													<span class="m-nav__link-text">
														Import
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-multimedia-2"></i>
													<span class="m-nav__link-text">
														Download Import Template
													</span>
												</a>
											</li>

										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="m-portlet__body">
		<span id="hidden_fields">
            <input type="hidden" name="page_number" value="1" />
		</span>
		<!--begin: Search Form -->
		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
						<div class="m-input-icon m-input-icon--left">
								<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch" name="general_search" />
								<span class="m-input-icon__icon m-input-icon__icon--right">
									<span>
										<i class="la la-search"></i>
									</span>
								</span>
							</div>

						</div>
						<div class="col-md-4">
                             <select class="bs-select form-control" name="so_order_customer" id="SO_ORDER_CUSTOMER" data-actions-box="true"  tabindex="2">
                                    <option value="0"> -- Customer -- </option>
                                    @foreach ( $lst_customers as $key => $customer_info )
                                            <option value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_code }}&nbsp;-&nbsp;{{ $customer_info->ic_customer_name }}</option>
                                    @endforeach
                            </select>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
                             <select class="bs-select form-control" name="so_vendor_id" id="SO_VENDOR_ID" data-actions-box="true"  tabindex="3">
                                    <option value="0"> -- Vendor -- </option>
                                    @foreach ( $lst_vendors as $key => $vendor_info )
                                            <option value="{{ $vendor_info->iv_id }}">{{ $vendor_info->iv_vendor_name }}</option>
                                    @endforeach
                            </select>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
                             <div class="col-md-12" style="height:15px"></div>
                             <select class="bs-select form-control" name="so_order_warehouse" id="SO_ORDER_WAREHOUSE" data-actions-box="true"  tabindex="1">
                                    <option value="0" selected="selected"> -- Warehouse -- </option>
                                    @foreach ( $lst_warehouses as $key => $warehouse_info )
                                            <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                            </select> 
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					<a href="{{ url('sales/orders/addform') }}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
						<span>
							<i class="fas fa-user"></i>
							<span>
								New Order
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div id="LstOrders">
			<table class="table table-striped">
              <thead>
               	<tr>
    				<th title="#">#</th>
    				<th title="Id"> ID </th>
    				<th title="Order Code"> Order Code </th>
    				<th title="Order Name"> Order Name </th>
    				<th title="Order total"> Order total </th>
    				<th title="edit"> edit </th>
    				<th title="delete"> Delete </th>
    			</tr>
              </thead>
              <tbody class="LstOrdersBody">
              </tbody>
             </table>
		
		
		</div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-md-10 col-lg-10 col-xs-10" align="left">
				 <ul id="SalesOrdersPagination" class="pagination-sm"></ul>
			</div>
			<div class="col-md-2 col-lg-2 col-xs-2" align="right">
				<a href="{{ url('sales/orders/addform') }}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
						<span>
							<i class="fas fa-user"></i>
							<span>
								New Order
							</span>
						</span>
					</a>
			</div>
		</div>
	</div>
</div>
@endsection