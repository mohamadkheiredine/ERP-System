<?php
/***********************************************************
recurringinvoices.blade.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 18, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
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
<script type="text/javascript" src="{{ url('js/modules/recurringinvoices.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/recurringinvoices.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Recurring Invoices Management</h3>
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
        <div class="col-md-12">
            <div class="row align-items-center">
                <div class="col-xl-12 order-2 order-xl-1">
                    <div class="row">
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
                            <br/>
                        </div>
                        <div class="col-md-4"> 
                                <select class="form-select form-select-solid" id="RI_TEMPLATE_ID" name="ri_template_id" data-control="select2" data-placeholder="Select a Invoice Template">
                                <option value="0">-- Select Invoice Template --</option>
                                @foreach($lst_templates as $index => $template_info)
                                <option value="{{ $template_info->it_id }}">{{ $template_info->it_template_code }}&nbsp;-&nbsp;{{ $template_info->it_template_label }}</option>
                                @endforeach
                            </select>
                            <br/>
                        </div>
                        <div class="col-md-4">
                          
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('billing/recurringinvoices/addform') }}" class="btn btn-info">
                                <span>
                                    <i class="flaticon-tabs"></i>
                                    <span>
                                        New Recurring Invoice
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-12" style="height:20px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--end: Search Form -->
        <!--begin: Datatable -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-rounded table-striped border gy-7 gs-7">
                        <thead>
                            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                <th style="width:2%" title="#">#</th>
                                <th style="width:2%" title="Id"> ID </th>
                                <th  style="width:5%" title="Customer"> Customer </th>
                                <th  style="width:5%" title="Account"> Account </th>
                                <th  style="width:20%" title="Label"> Label </th>
                                <th  style="width:12%" title="Next invoice Date"> Next invoice Date </th>
                                <th  style="width:12%" title="End Date"> End Date </th>
                                <th  style="width:12%" title="status"> status </th>
                                <th  style="width:10%" title="Created User">Created User</th>
                                <th  style="width:10%" title="Updated User">Updated User</th>
                                <th style="width:2px;" nowrap title="edit"> edit </th>
                                <th style="width:2px;" nowrap title="delete"> Delete </th>
                            </tr>
                        </thead>
                        <tbody id="LstRecurringInvoices">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10" align="left">
                <ul id="RecurringInvoicesPagination" class="pagination-sm"></ul>
            </div>
            <div class="col-md-2" align="right"></div>
        </div>
        <!--end: Datatable -->
        <div class="row">
            <div class="col-md-12 order-1 order-xl-2 m--align-right">
                <a href="{{ url('billing/recurringinvoices/addform') }}" class="btn btn-info">
                    <span>
                        <i class="flaticon-tabs"></i>
                        <span>
                            New Recurring Invoice
                        </span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection