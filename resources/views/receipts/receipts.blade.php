<?php
/***********************************************************
receipts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 9, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/




?>

@extends('layouts.layout',['page_title' => "Receipts Management"])

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
<script type="text/javascript" src="{{ url('js/modules/receipts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/receiptsmanagement.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Receipts Management</h3>
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
		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
			<div class="row align-items-center">
				<div class="col-xl-12 order-2 order-xl-1">
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
                            <div class="m-input-icon m-input-icon--left">
                                <select id="RECEIPT_CUSTOMER" name="receipt_customer" class="form-control form-select" data-control="select2" data-placeholder="Select Receipt Customer">
                            			<option value="0">-- Select Customer --</option>
                                        @foreach($lst_customers as $index => $customer_info)
                                          <option value="{{ $customer_info->ic_id }}">( {{ $customer_info->ic_customer_code }} ) &nbsp;-&nbsp;{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
						 <div class="col-md-4">
							 <div class="m-input-icon m-input-icon--left">
                                                             <select id="RECEIPT_INVOICE" name="receipt_invoice" class="form-control form-select" data-control="select2" data-placeholder="Select Receipt Invoice">
                            			<option value="0">-- Select Invoice --</option>
                                        @foreach($lst_invoices as $index => $inv_info)
                                          <option value="{{ $inv_info->bi_id }}">{{ $inv_info->bi_invoice_code }}</option>
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
         <div class="row">
    		<div class="col-md-12">
    		 <div class="table-responsive">
        		 <table class="table table-rounded table-striped border gy-7 gs-7">
    				<thead>
    					<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
    						<th style="width:2px;">#</th>
    						<th>Invoice</th>
    						<th>Receipt Code</th>
    						<th>Receipt Label</th>
    						<th>Receipt Note</th>
    						<th>Receipt Date</th>
    						<th>Receipt Amount</th>
    						<th style="width:4px;white-space: nowrap;text-align: center">Download</th>
    						<th style="width:4px;white-space: nowrap;text-align: center">edit</th>
    						<th style="width:4px;white-space: nowrap;text-align: center">Delete</th>
    					</tr>
    				</thead>
    				<tbody  class="LstReceiptsGrid"></tbody>
    			</table>
    		 </div>

    		</div>
    	</div>
    	 <div class="row">
             <div class="col-md-10" align="left">
                <ul id="ReceiptsPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-2" align="right"></div>
         </div>
		<div class="row">
			<div class="col-md-12 order-1 order-xl-2 align-right">
					<a href="{{ url('billing/receipts/addform') }}" class="btn btn-info">
						<span>
							<i class="flaticon-tabs"></i>
							<span>
								New Receipt
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
		</div>
    </div>
</div>


@endsection
