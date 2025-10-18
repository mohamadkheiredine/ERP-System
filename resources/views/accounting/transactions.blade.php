<?php
/***********************************************************
transactions.blade.php.php
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
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Ledger Management</h3>
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
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-12 order-2 order-xl-1">
                        <div class="form-group m-form__group row align-items-center">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                <div class="d-flex align-items-center">
                                    <div class="position-relative w-md-400px me-md-2">
                                        <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" tabindex="1" />
                                    </div>
                                    <!--end::Input group-->
                                </div>
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
            <div class="col-md-12 table-responsive">
			<span id="hidden_fields">
				<input type="hidden" name="page_number" id="PAGE_NUMBER" value="1" />
			</span>
                <table class="table table-row-dashed table-row-gray-300 gy-7">
                    <thead>
                    <tr class="fw-bold fs-6 text-gray-800">
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
                    <a href="{{ url('accounting/transaction/addform') }}" class="btn btn-info">
						<span>
							<i class="fa fa-money"></i>
							<span>
								New Transaction
							</span>
						</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
