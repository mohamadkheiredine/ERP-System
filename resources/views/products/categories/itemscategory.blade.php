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
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Products
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
												<a href="" id="DUPLICATE_PRODUCT" class="m-nav__link">
													<i class="m-nav__link-icon fas fa-clone"></i>
													<span class="m-nav__link-text">
														Duplicate
													</span>
												</a>
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
						<div class="m-input-icon m-input-icon--left">
								<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch" name="general_search">
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
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					 
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div  id="LstProductsMain">
			<table class="table m-table m-table--head-bg-brand" id="html_table" width="100%">
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
				<button type="button" name="btn_new_product" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill" >
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