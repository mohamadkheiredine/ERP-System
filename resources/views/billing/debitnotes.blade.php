<?php
/***********************************************************
debitnotes.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 2, 2021
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
<script type="text/javascript" src="{{ url('js/modules/debitnotes.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/dnmanagement.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Debit Notes</h3>
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
    <input type="hidden" name="page_number" value="1" />
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
                            		<select class="bs-select form-control" id="DN_ACCOUNT_PAYABLE" name="dn_account_payable">
                            			<option value="0">-- Select Account --</option>
                                        @foreach($lst_chart_accounts as $index => $account_info)
                                          <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref }} - {{ $account_info->aa_account_label }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
						 <div class="col-md-4">
							 <div class="m-input-icon m-input-icon--left">
                            		<select class="bs-select form-control" id="DN_ACCOUNT_RECEIVABLE" name="dn_account_receivable">
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
                                <input type="text" placeholder=" From Date" name="dn_start_date" id="DN_START_DATE" value="" class="form-control" />
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <input type="text"  placeholder="To Date" name="dn_end_date" id="DN_END_DATE" value="" class="form-control" />
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
		<div class="col-md-12 table-responsive">
            <table class="table table-rounded table-striped border gy-7 gs-7">
            		<thead>
            			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            				<th style="width:2%" title="#">#</th>
            				<th style="width:2%" title="Id"> ID </th>
            				<th  style="width:15%" title="Account Payable"> Account Payable </th>
            				<th  style="width:15%" title="Account Receivable"> Account Receivable </th>
            				<th  style="width:20%" title="Label"> Label </th> 
            				<th  style="width:12%" title="Amount"> Amount </th> 
            				<th style="width:2px;" nowrap title="edit"> edit </th>
            				<th style="width:2px;" nowrap title="delete"> Delete </th>
            			</tr>
            		</thead>
            		<tbody id="LstDebitNotes">
            		</tbody>
            </table>
		</div>
		</div>
    	 <div class="row">
             <div class="col-md-10" align="left">
                <ul id="DNPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-2" align="right"></div>
         </div>
		<div class="row">
			<div class="col-md-12 order-1 order-xl-2 m--align-right">
					<a href="{{ url('billing/debitnotes/addform') }}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
						<span>
							<i class="flaticon-tabs"></i>
							<span>
								New <b>Debit Note</b>
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
		</div>
    </div>
</div>
 
@endsection