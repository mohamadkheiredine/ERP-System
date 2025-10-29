<?php
/***********************************************************
accountstatment.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 28, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
Report Page for Account Statment
***********************************************************/

?>
@extends('layouts.layout',['page_title' => "Accounting Reports"])

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
<script type="text/javascript" src="{{ url('js/libraries/accounting/accountstatment.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Users</h3>
        <div class="card-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Action
                </button>
                <ul class="dropdown-menu">
                	<li><a data-action="DOWNLOAD_TEMPLATE" class="dropdown-item" href="#">Download Import Template</a></li>
              </ul>
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
                <div class="col-xl-8 order-2 order-xl-1">
                    <div class="form-group m-form__group row align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="position-relative w-md-400px me-md-2">
                                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid ps-10" name="search_query" id="generalSearch" value="" placeholder="Search" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Account </label>
                                <select class="bs-select form-control" name="acc_account" id="ACC_ACCOUNT" data-actions-box="true">
                                    <option value="0"> --Select Account--</option>
                                    @foreach ( $lst_accounts as $key => $account_info )
                                        <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref . " - " . $account_info->aa_account_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> From Date </label><br/>
                                <input type="text" name="start_date" id="START_DATE" value="" class="form-control" />
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> To Date </label><br/>
                                <input type="text" name="end_date" id="END_DATE" value="" class="form-control" />
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">&nbsp;</div>
        <!--end: Search Form -->
        <div class="col-md-12" id="LstAccountStatment">
        </div>
        <div class="row">
            <div class="col-md-10" align="left">
                <ul id="AccountsPagination" class="pagination-sm"></ul>
            </div>
        </div>
        <div class="row">
        </div>
    </div>
</div>
@endsection
