<?php
/* * *********************************************************
  packing.blade.php
  Product :
  Version : 1.0
  Release : 1
  Date Created : Dec 8, 2019
  Developed By  : Mohamad Mantach   PHP Department itm Solutions
  All Rights Reserved ,   itm Solutions COPYRIGHT 2019

  Page Description :
  Manage packing Prices Saved in database
 * ********************************************************* */
?>


@extends('layouts.layout',['page_title' => "Packing Management"])

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
<script type="text/javascript" src="{{ url('js/modules/packing.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/shipment/packingprices.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Packing Prices Management</h3>
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
        <div class="col-md-12">
            <span id="hidden_fields">
                <input type="hidden" name="page_number" value="1" />
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
                                    <label> Product Categories : </label>
                                     <select name="product_category" id="PRODUCT_CATEGORY"  class="form-control form-select" data-control="select2" data-placeholder="Select Category">
                                        @foreach ( $lst_product_categories as $key => $category_info )
                                        <option value="{{ $category_info->pc_id }}">{{ $category_info->pc_category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br/>
                            </div>
                            <div class="col-md-4"> 
                                <br/>
                            </div>
                            <div class="col-md-4">
                                
                            </div>
                            <div class="col-md-4"> 
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 align-right">

                    </div>
                </div>
            </div>
            <!--end: Search Form -->
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-rounded table-striped border gy-7 gs-7" id="html_table" width="100%">
                        <thead>
                            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                <th style="width:2px;white-space: nowrap;" title="#">#</th>
                                <th style="width:2px;white-space: nowrap;" title="Id"> ID </th>
                                <th title="Voucher Date"> Categoty Name </th>
                                <th title="Account Receiver"> From </th>
                                <th title="Account Receiver"> To </th>
                                <th title="Account Receiver"> Packing Amount </th>
                                <th style="width:2px;" nowrap title="#"> edit </th>
                                <th style="width:2px;" nowrap title="#"> Delete </th>
                            </tr>
                        </thead>
                        <tbody id="LstPackingAmounts">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10" align="left">
                    <ul id="PackingPagination" class="pagination-sm"></ul>
                </div>
                <div class="col-md-2" align="right"></div>
            </div>
            <!--end: Datatable --> 
            <div class="row">
                <div class="col-md-12" align="right">
                    <a href="{{ url('shipment/packingprices/addform') }}" class="btn btn-info">
                        <span>
                            <i class="fa fa-money"></i>
                            <span>
                                New Packing
                            </span>
                        </span>
                    </a>
                    <br/>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection