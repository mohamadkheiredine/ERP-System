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
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Invoices Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item quickactions" data-action_type="PRINT" href="#">Print</a></li>
                    <li><a class="dropdown-item quickactions" data-action_type="EXPORT_AS_CSV" href="#">Export As CSV</a></li>
                    <li><a class="dropdown-item quickactions" data-action_type="IMPORT" href="#">Import</a></li>
                    <li><a class="dropdown-item quickactions" data-action_type="DOWNLOAD_TEMPLATE" href="#">Download Import Template</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    <input type="hidden" name="page_number" value="1" />
		<input type="hidden" name="fisical_year" value="{{ date('Y') }}" />
		<!--begin: Search Form -->
		<div class="col-md-12">
			<div class="row align-items-center">
				<div class="col-xl-12 order-2 order-xl-1">
					<div class="row">
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
                            <select class="bs-select form-control" id="INVOICE_CUSTOMER" name="invoice_customer">
                            			<option value="0">-- Select Customer --</option>
                                        @foreach($list_customers as $index => $customer_info)
                                          <option value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                    </select>
                                    <br/>
						</div>
						 <div class="col-md-4">
							 <select class="bs-select form-control" id="INVOICE_BANK" name="invoice_bank">
                            			<option value="0">-- Select Bank --</option>
                                        @foreach($lst_banks_info as $index => $bank_info)
                                          <option value="{{ $bank_info->ba_id }}">{{ $bank_info->ba_account_label }}</option>
                                        @endforeach
                                    </select>
                                    <br/>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <input type="text" placeholder=" From Date" name="start_date" id="START_DATE" value="" class="form-control" />
                            </div>
                            <br/>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <input type="text"  placeholder="To Date" name="end_date" id="END_DATE" value="" class="form-control" />
                            </div>
                            <br/>
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
            <div class="table-responsive">
            <table class="table table-rounded table-striped border gy-7 gs-7">
            		<thead>
            			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
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
					<a href="{{ url('billing/invoices/addform') }}" class="btn btn-info">
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