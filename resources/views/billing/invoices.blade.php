<?php
/***********************************************************
invoices.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/




?>

@extends('layouts.layout',['page_title' => "Invoices Management"])

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
<script type="text/javascript" src="{{ url('js/modules/invoices.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/invoicesmanagement.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Invoices Management
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
												<a data-action_type="PRINT"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon fa fa-print"></i>
													<span class="m-nav__link-text">
														Print
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a data-action_type="EXPORT_AS_CSV"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon fa fa-download"></i>
													<span class="m-nav__link-text">
														Export As CSV
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a data-action_type="IMPORT"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon fa fa-upload"></i>
													<span class="m-nav__link-text">
														Import
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a  data-action_type="DOWNLOAD_TEMPLATE"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-download"></i>
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
		<input type="hidden" name="page_number" value="1" />
		<input type="hidden" name="fisical_year" value="{{ date('Y') }}" />
		<!--begin: Search Form -->
		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
			<div class="row align-items-center">
				<div class="col-xl-12 order-2 order-xl-1">
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
                            <div class="m-input-icon m-input-icon--left">
                            		<select class="bs-select form-control" id="INVOICE_CUSTOMER" name="invoice_customer">
                            			<option value="0">-- Select Customer --</option>
                                        @foreach($list_customers as $index => $customer_info)
                                          <option value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
						 <div class="col-md-4">
							 <div class="m-input-icon m-input-icon--left">
                            		<select class="bs-select form-control" id="INVOICE_BANK" name="invoice_bank">
                            			<option value="0">-- Select Bank --</option>
                                        @foreach($lst_banks_info as $index => $bank_info)
                                          <option value="{{ $bank_info->ba_id }}">{{ $bank_info->ba_account_label }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
						<div class="col-md-12">&nbsp;</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <input type="text" placeholder=" From Date" name="start_date" id="START_DATE" value="" class="form-control" />
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <input type="text"  placeholder="To Date" name="end_date" id="END_DATE" value="" class="form-control" />
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4"></div>
					</div>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
          <div class="row">
		<div class="col-md-12">
            <table class="table">
            		<thead>
            			<tr>
            				<th style="width:2%" title="#">#</th>
            				<th style="width:2%" title="Id"> ID </th>
            				<th  style="width:5%" title="Invoice Ref"> Invoice Ref </th>
            				<th  style="width:5%" title="Date"> Date </th>
            				<th  style="width:20%" title="Description"> Description </th>
            				<th  style="width:12%" title="Customers"> Customers </th>
            				<th  style="width:12%" title="Total Price"> Total Price </th>
            				<th  style="width:10%" title="Created User">Created User</th>
            				<th  style="width:10%" title="Updated User">Updated User</th>
            				<th style="width:2px;white-space: nowrap;"  title="Download Invoice"> Download </th>
            				<th style="width:2px;" nowrap title="edit"> edit </th>
            				<th style="width:2px;" nowrap title="delete"> Delete </th>
            			</tr>
            		</thead>
            		<tbody id="LstInvoices">
            		</tbody>
            </table>
		</div>
		</div>
    	 <div class="row">
             <div class="col-md-10" align="left">
                <ul id="InvoicesPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-2" align="right"></div>
         </div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-md-12 order-1 order-xl-2 m--align-right">
					<a href="{{ url('billing/invoices/addform') }}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
						<span>
							<i class="flaticon-tabs"></i>
							<span>
								New Invoice
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
		</div>
	</div>
</div>
@endsection