<?php
/***********************************************************
transactions.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page to display Transactions 
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Ledger Management"])

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
<script type="text/javascript" src="{{ url('js/modules/transactions.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/transactionsmanagement.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Ledger Management
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
												<a href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-share"></i>
													<span class="m-nav__link-text">
														Print
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-chat-1"></i>
													<span class="m-nav__link-text">
														Export As CSV
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-multimedia-2"></i>
													<span class="m-nav__link-text">
														Import
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="#" class="m-nav__link quickactions">
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
		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
			<div class="row align-items-center">
				<div class="col-xl-12 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-3">
						<div class="m-input-icon m-input-icon--left">
								<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
								<span class="m-input-icon__icon m-input-icon__icon--right">
									<span>
										<i class="la la-search"></i>
									</span>
								</span>
							</div>

						</div>
						<div class="col-md-3">
							 <div class="form-group">
                                <label> Journal </label>
                                <select class="bs-select form-control" name="fk_acc_journal_id" id="FK_ACC_JOURNAL_ID" data-actions-box="true">
                                        <option value="0">Accounting Journal</option>
                                        @foreach ( $lst_journals as $key => $journal_info )
                                                <option value="{{ $journal_info->aj_id }}">{{ $journal_info->aj_journal_code . " - " . $journal_info->aj_journal_label  }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-3">
							 <div class="form-group">
                                <label> Ledger Account </label>
                                <select class="bs-select form-control" name="tm_ledger_account" id="TM_LEDGER_ACCOUNT" data-actions-box="true">
                                        <option value="0"> --Select Account--</option>
                                        @foreach ( $lst_accounts as $key => $account_info )
                                                <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref . " - " . $account_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-3">
							 <div class="form-group">
                                <label> Sub Ledger Account </label>
                                <select class="bs-select form-control" name="tm_sub_ledger_account" id="TM_SUB_LEDGER_ACCOUNT" data-actions-box="true">
                                        <option value="0"> --Select Account--</option>
                                        @foreach ( $lst_accounts as $key => $account_info )
                                                <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref . " - " . $account_info->aa_account_label }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-3">
							 <div class="form-group">
                                <label> From Date </label><br/>
                                <input type="text" name="start_date" id="START_DATE" value="" class="form-control" />
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-3">
							 <div class="form-group">
                                <label> To Date </label><br/>
                                <input type="text" name="end_date" id="END_DATE" value="" class="form-control" />
                            </div>
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
		<div class="col-md-12">
			<span id="hidden_fields">
				<input type="hidden" name="page_number" id="PAGE_NUMBER" value="1" />
			</span>
			<table class="table m-table m-table--head-bg-brand"  width="100%">
            	<thead>
            		<tr>
            			<th title="#">#</th>
            			<th title="Date">Date</th>
            			<th title="Accounting Doc">Accounting Doc</th>
            			<th title="Account">Account</th> 
            			<th title="Label">Label</th>
            			<th title="Debit">Debit</th>
            			<th title="Credit">Credit</th>
            			<th title="Currenct">Currency</th> 
            			<th title="edit">Edit</th>
            			<th title="Delete">Delete</th>
            		</tr>
            	</thead>
            	<tbody  id="LstLedger">
            	      
            	</tbody>
            </table>
		</div>
		<!--end: Datatable -->
		<div class="row" style="height:25px"></div>
		<div class="row">
			<div class="col-md-12" align="right">
				<ul id="LedgerPagination" class="pagination-sm"></ul>
			</div>
		</div>
		<div class="row" style="height:25px"></div>
		<div class="row" style="height:25px">
			<div class="col-md-12">
				<label>Empty Transactions</label>
			</div>
		</div>
		<div class="row" style="height:25px"></div>
		<div class="m_datatable" id="LstEmptyTransactions">

			</div>
		<div class="row">
			<div class="col-md-12" align="right">
				<a href="{{ url('accounting/transaction/addform') }}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
						<span>
							<i class="fa fa-money"></i>
							<span>
								New Transaction
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
			</div>
		</div>
	</div>
</div>
@endsection