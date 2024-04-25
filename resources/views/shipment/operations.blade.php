<?php
/***********************************************************
operations.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 16, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
View Page of display shipment operations
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Shipment Operations"])

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
<script type="text/javascript" src="{{ url('js/modules/operations.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/shipment/operations.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Shipment Operations</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
                    <li><a data-action="EXPORT_CSV" class="dropdown-item" href="#">Export as CSV</a></li>
                	<li><a data-action="DOWNLOAD_TEMPLATE" class="dropdown-item" href="#">Download Import Template</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
            <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center">
                                    <div class="col-md-4">
                                     <label>&nbsp;</label><br/>
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
                         <label> Accounts : </label>
                            <select class="bs-select form-control" name="fk_account_id" id="FK_ACCOUNT_ID" data-actions-box="true">
                                    <option value="">-- select one --</option>
                                    @foreach ( $lst_accounts as $key => $account_info )
                                            <option value="{{ $account_info->ca_id }}">{{ $account_info->ca_account_name }}</option>
                                    @endforeach
                            </select>
                                    </div>
                                    <div class="col-md-4">
                        <label> Warehouses : </label>
                            <select class="bs-select form-control" name="fk_warehouse_id" id="FK_WAREHOUSE_ID" data-actions-box="true">
                                    <option value="">-- select one --</option>
                                    @foreach ( $lst_warehouses as $key => $warehouse_info )
                                            <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                            </select>
                                    </div>
                            </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 align-right">
                            <a href="{{ url('shipments/shipmentoperations/addform') }}" class="btn btn-success">
                                    <span>
                                            <i class="fas fa-user"></i>
                                            <span>
                                                    New Operation
                                            </span>
                                    </span>
                            </a>
                            <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
            </div>
    </div>
    <!--end: Search Form -->
      <!--begin: Datatable -->
    <div  id="LstProductsMain" class="table-responsive">
            <table class="table table-striped gy-7 gs-7">
                    <thead>
                            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                    <th style="width:4px;">#</th>
                                    <th title="Id" style="width:50px;white-space: nowrap;"> ID </th>
                                    <th title="Reference" style="width:50px;white-space: nowrap;"> Operation Reference </th>
                                    <th title="Name" style="width:50px;white-space: nowrap;"> Operation Label  </th>
                                    <th title="Date" style="width:50px;white-space: nowrap;"> Operation Date </th>
                                    <th title="Time" style="width:50px;white-space: nowrap;"> Operation Time </th>
                                    <th style="width:4px !important;" nowrap title="#"> edit </th> 
                                    <th style="width:4px !important;" nowrap title="#"> Delete </th>
                            </tr>
                    </thead>
            <tbody  id="LstShipmentOperations" ></tbody>
        </table>

    </div>
    <div class="row">
        <div class="col-md-10" align="left">
           <ul id="OperationsPagination" class="pagination-sm"></ul>
        </div>
        <div class="col-md-2" align="right"></div>
    </div>
    <div class="row">
            <div class="col-md-12" style="margin:7px;text-align: right;padding-right:25px;">
                    <a href="{{ url('shipments/shipmentoperations/addform') }}" class="btn btn-success">
                                    <span>
                                            <i class="fas fa-user"></i>
                                            <span>
                                                    New Operation
                                            </span>
                                    </span>
                            </a>
            </div>
    </div>
</div>
@endsection