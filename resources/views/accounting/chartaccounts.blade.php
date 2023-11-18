<?php
/***********************************************************
chartaccounts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 14, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page of Chart of accounts
***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Chart of Accounts"])

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
<script type="text/javascript" src="{{ url('js/modules/chartaccounts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/chartaccounts.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Chart Of Accounts</h3>
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
    <input type="hidden" name="page_number" value"1" />
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
        												<input type="text" class="form-control form-control-solid ps-10" name="search_query" id="generalSearch" value="" placeholder="Search" />
        											</div>
        											<!--end::Input group-->
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
										<div class="col-xl-12 order-1 order-xl-12 m--align-right">
											<a href="{{ url('accounting/chartofaccounts/addform') }}" class="btn btn-info">
												<span>
													<i class="flaticon-grid-menu-v2"></i>
													<span>
														Add Account
													</span>
												</span>
											</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
									</div>
								</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div  class="row">
									 <div class="col-md-12 table-responsive">
									 <table class="table table-striped gy-7 gs-7" width="100%">
                                		<thead>
                                			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                				<th title="#">#</th> 
                                				<th title="Account" style="width:50px;white-space: nowrap;">Account</th>
                                				<th title="Sub-Account" style="width:50px;white-space: nowrap;">Sub-Account</th>
                                				<th title="Label">Label</th>
                                				<th title="Information">Information</th>
                                				<th title="edit">edit</th>
                                				<th title="delete">Delete</th>
                                			</tr>
                                		</thead>
                                		<tbody id="LstChartAccounts">
                                		</tbody>
                                		</table>
									 </div>
								</div>
								 <div class="row">
                                     <div class="col-md-10" align="left">
                                        <ul id="AccountsPagination" class="pagination-sm"></ul>
                                     </div>
                                     <div class="col-md-2" align="right"></div>
                                 </div>
								<!--end: Datatable -->
								<div class="col-xl-12 order-1 order-xl-12 m--align-right" style="margin-top: 12px;">
											<a href="{{ url('accounting/chartofaccounts/addform') }}" class="btn btn-info">
												<span>
													<i class="flaticon-grid-menu-v2"></i>
													<span>
														Add Account
													</span>
												</span>
											</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
										
										<div class="modal fade" id="ImportModal" tabindex="-1" role="dialog" aria-labelledby="ImportsModallLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="ImportsModallLabel">
											Import Accounts
										</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
										</button>
									</div>
									<div class="modal-body">
										<form name="frm_import_accounts" id="FRM_IMPORT_ACCOUNTS">
											   {!! csrf_field() !!}
											 <div class="form-group">
                                                <label> Country </label>
                                                <select class="bs-select form-control" name="fk_country_id" id="FK_COUNTRY_ID" data-actions-box="true">
                                                        <option value="">Country</option>
                                                        @foreach ( $lst_countries as $key => $country_info )
                                                                <option  value="{{ $country_info->id }}">{{ $country_info->name }}</option>
                                                        @endforeach
                                                </select>
                                            </div>
											<div class="form-group">
												<label for="message-text" class="form-control-label">
													CSV File :
												</label>
												<input type="file" name="csv_file" class="form-control" />
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">
											Close
										</button>
										<button type="button" name="btn_import_accounts" id="BTN_IMPORT_ACCOUNTS" class="btn btn-primary">
											Import Accounts
										</button>
									</div>
								</div>
							</div>
						</div>
    </div>
</div>

@endsection