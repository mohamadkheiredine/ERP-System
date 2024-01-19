<?php
/***********************************************************
journalvouchers.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 22, 2021
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
<script type="text/javascript" src="{{ url('js/modules/journalvouchers.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/journalvouchers.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Journal Vouchers Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu"> </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    <span id="hidden_fields">
			<input type="hidden" name="page_number" value="1" />
		<input type="hidden" name="fisical_year" value="{{ date('Y') }}" />
		</span>
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
							 <div class="form-group">
                                <label class="form-label"> Account Debit : </label>
                                <select class="bs-select form-control" name="jv_account_id" id="JV_ACCOUNT_ID" data-actions-box="true">
                                        <option value="0"> --Select Account--</option>
                                        @foreach ( $lst_chart_accounts as $key => $account_info )
                                                <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref . " - " . $account_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <label class="form-label"> Account Credit : </label>
                                <select class="bs-select form-control" name="jv_account_id" id="JV_ACCOUNT_ID" data-actions-box="true">
                                        <option value="0"> --Select Account--</option>
                                        @foreach ( $lst_chart_accounts as $key => $account_info )
                                                <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref . " - " . $account_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
						</div>  
						<div class="col-md-4">
							 <div class="form-group">
                                <label class="form-label"> From Date </label><br/>
                                <input type="text" name="jv_start_date" id="JV_START_DATE" value="" class="form-control" />
                            </div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <label class="form-label"> To Date </label><br/>
                                <input type="text" name="jv_end_date" id="JV_END_DATE" value="" class="form-control" />
                            </div>
						</div>
					</div>
				</div>
				<div class="col-xl-4">
					
				</div>
			</div>
		</div>
		<!--end: Search Form -->
        <div class="row">
    		<div class="col-md-12 table-responsive">
    			<table class="table table-striped gy-7 gs-7">
            		<thead>
            			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            				<th style="width:2px;white-space: nowrap;" title="#">#</th>
            				<th style="width:2px;white-space: nowrap;" title="Id"> ID </th>
            				<th title="Voucher Date"> Voucher Date </th>
            				<th title="Voucher Ref"> Voucher Ref </th>
            				<th title="Account Sender"> Account </th> 
            				<th title="Total Price"> Total Price </th>
            				<th title="currency"> Currency </th>
            				<th style="width:2px;" nowrap title="#"> edit </th>
            				<th style="width:2px;" nowrap title="#"> Delete </th>
            			</tr>
            		</thead>
            		<tbody id="LstJournalVouchers">
            		</tbody>
            	</table>
    		</div>
		</div>
    	 <div class="row">
             <div class="col-md-10" align="left">
                <ul id="VouchersPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-2" align="right"></div>
         </div>
		<!--end: Datatable --> 
		<div class="row">
			<div class="col-md-12" align="right">
				<a href="{{ url('billing/journalvouchers/addform') }}" class="btn btn-info">
						<span>
							<i class="fa fa-money"></i>
							<span>
								New Voucher
							</span>
						</span>
					</a>
			</div>
		</div>
    </div>
</div>
@endsection