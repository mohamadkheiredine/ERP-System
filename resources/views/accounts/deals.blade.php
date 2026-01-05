<?php
/***********************************************************
deals.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Acount Deals Management
***********************************************************/



?>

@extends('layouts.layout',['page_title' => "Contract Management"])

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
<script type="text/javascript" src="{{ url('js/modules/deals.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/deals.js') }}"></script>
@endsection

@section('content')
<span id="hidden_fields">
    <input type='hidden' name="page_number"  value="1" />
</span>
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Contract Management</h3>
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
        <div class="col-md-12">
             <div class="row align-items-center">
                <div class="col-xl-12 order-2 order-xl-1">
                        <div class="form-group row align-items-center">
                                <div class="col-md-4">
                                    <div class="form-group">
                                         <label>&nbsp;</label>
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

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                         <label>&nbsp;</label>
                                        <select id="AD_ACCOUNT" name="ad_account" class="form-control form-select" data-control="select2" data-placeholder="Select Account">
                                            <option value="0">-- Select Account --</option>
                                            @foreach($lst_accounts as $index => $acc_info)
                                              <option value="{{ $acc_info->ca_id }}">{{ $acc_info->ca_account_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" style="text-align: right">
                                    <div class="form-group">
                                          <label>&nbsp;</label><br/>
                                            <a href="{{ url('crm/accounts/deals/addform') }}" class="btn btn-info">
                                                      <span>
                                                              <i class="fas fa-user"></i>
                                                              <span>
                                                                      New Contract
                                                              </span>
                                                      </span>
                                              </a>
                                    </div>

                                </div>
                </div>
        </div>
        </div>
            <div class="row">
                <div class="col-md-12" style="height:50px"></div>
            </div>
            <div class="row">
                <div class="col-md-12" style="height:50px"></div>
            </div>
          <!--begin: Datatable -->
		<div class="table-responsive">
									<table class="table table-rounded table-striped border gy-7 gs-7">
                            		<thead>
                				<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th title="ref">REF </th>
                                        <th title="Deal Code">Deal Code </th>
                                        <th title="Account Name"> Client Name </th>
                                        <th title="Contract Name"> Contract Amount </th>
                                        <th style="width:4px !important;" nowrap title="#">edit</th>
                                        <th style="width:4px !important;" nowrap title="#">view</th>
                                        <th style="width:4px !important;" nowrap title="#">Delete</th>
                                </tr>
                        </thead>
                        <tbody id="LstAccountDeals">

                        </tbody>
                    </table>
		</div>
          <div class="row">
            <div class="col-md-10 col-lg-10 col-xs-10" align="left">
                <ul id="DealsPagination" class="pagination-sm"></ul>
            </div>
            <div class="col-md-2 col-lg-2 col-xs-2" align="right">
                <a href="{{ url('crm/accounts/deals/addform') }}" class="btn btn-info">
                    <span>
                        <i class="fas fa-user"></i>
                        <span>
                            New Contract
                        </span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>


@endsection
