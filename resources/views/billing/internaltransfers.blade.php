<?php
/***********************************************************
creditnotes.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 31, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/




?>

@extends('layouts.layout',['page_title' => "Billing Management"])

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
<script type="text/javascript" src="{{ url('js/modules/internaltransfers.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/internaltransfers.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Internal Transfers</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
                  <li><a class="dropdown-item" data-action_type="PRINT" href="#">Print</a></li>
                  <li><a class="dropdown-item" data-action_type="EXPORT_AS_CSV" href="#">Export As CSV</a></li>
                  <li><a class="dropdown-item" data-action_type="IMPORT" href="#">Import</a></li>
                  <li><a class="dropdown-item" data-action_type="DOWNLOAD_TEMPLATE" href="#">Download Import Template</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    <input type="hidden" name="page_number" value="1" />
		<!--begin: Search Form -->
		<div class="col-md-12">
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
                            <div class="form-control">
                            		<select class="bs-select form-control" id="IN_ACCOUNT_PAYABLE" name="in_account_payable">
                            			<option value="0">-- Select Account --</option>
                                        @foreach($lst_chart_accounts as $index => $account_info)
                                          <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref }} - {{ $account_info->aa_account_label }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
						 <div class="col-md-4">
							 <div class="form-control">
                            		<select class="bs-select form-control" id="IN_ACCOUNT_RECEIVABLE" name="in_account_receivable">
                            				<option value="0">-- Select Account --</option>
                                        @foreach($lst_chart_accounts as $index => $account_info)
                                          <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref }} - {{ $account_info->aa_account_label }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
						<div class="col-md-12">&nbsp;</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <input type="text" placeholder=" From Date" name="in_start_date" id="IN_START_DATE" value="" class="form-control" />
                            </div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <input type="text"  placeholder="To Date" name="in_end_date" id="IN_END_DATE" value="" class="form-control" />
                            </div>
						</div>
						<div class="col-md-4"></div>
					</div>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
          <div class="row">
			<div class="table-responsive col-md-12">
				<table class="table">
            		<thead>
            			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            				<th style="width:2%" title="#">#</th>
            				<th style="width:2%" title="Id"> ID </th>
            				<th  style="width:15%" title="Account Payable"> Account Payable </th>
            				<th  style="width:15%" title="Account Receivable"> Account Receivable </th>
            				<th  style="width:20%" title="Label"> Label </th> 
            				<th  style="width:12%" title="Amount"> Credit Amount </th> 
            				<th  style="width:12%" title="Amount"> Debit Amount </th> 
            				<th style="width:2px;" nowrap title="edit"> edit </th>
            				<th style="width:2px;" nowrap title="delete"> Delete </th>
            			</tr>
            		</thead>
            		<tbody id="LstInternalNotes">
            		</tbody>
            </table>
		</div>
		</div>
    	 <div class="row">
             <div class="col-md-10" align="left">
                <ul id="InternalTransfersPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-2" align="right"></div>
         </div>
		<div class="row">
			<div class="col-md-12 order-1 order-xl-2 m--align-right">
					<a href="{{ url('billing/internaltransfers/addform') }}" class="btn btn-info">
						<span>
							<i class="flaticon-tabs"></i>
							<span>
								New <b>Internal Note</b>
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
		</div>
    </div>
</div>

@endsection