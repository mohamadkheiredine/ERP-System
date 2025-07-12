<?php
/***********************************************************
 * employees.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 7/7/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Payroll Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/employees.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/payrolls/employees.js') }}"></script>
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
                        <li><a class="dropdown-item" href="#">Export as CSV</a></li>
                        <li><a class="dropdown-item" href="#">Download Import Template</a></li>
                        <li><a class="dropdown-item" href="#">Import</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
        <span id="hidden_fields">
            <input type="hidden" name="page_number" value="1" />
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
                                        <input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <select class="form-select form-control" data-control="select2" id="COMPANY_ID" name="company_id" name="lead_category">
                                    <option value="0">-- Select Company --</option>
                                    @foreach($lst_companies as $index => $company_info)
                                        <option value="{{ $company_info->cd_id }}">{{ $company_info->cd_company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="d-md-none m--margin-bottom-10"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 align-right" style="text-align: right">
                        <a href="{{ url('payrolls/employees/addform') }}" class="btn btn-info">
                        <span>
                            <i class="fas fa-user"></i>
                            <span>
                                New Employee
                            </span>
                        </span>
                        </a>
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">&nbsp;</div>
            <!--end: Search Form -->
            <div class="col-md-12">

                <table class="table table-bordered table-hover" width="100%">
                    <thead>
                    <tr>
                        <th style="width:4px;white-space: nowrap;"  title="Id">ID</th>
                        <th title="Full Name">Full Name</th>
                        <th title="Email">email</th>
                        <th title="Phone">Phone</th>
                        <th title="Basic Salary">Basic Salary</th>
                        <th style="width:4px;white-space: nowrap;"  title="Online/Offline">Online/Offline</th>
                        <th style="width:4px;white-space: nowrap;"  title="#">edit</th>
                        <th style="width:4px;white-space: nowrap;"  title="#">Delete</th>
                    </tr>
                    </thead>
                    <tbody id="LstEmployees">
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-10" align="left">
                    <ul id="EmployeesPagination" class="pagination-sm"></ul>
                </div>
                <div class="col-md-2" align="right"></div>
            </div>
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4" align="right">
                    <a href="{{ url('payrolls/employees/addform') }}" class="btn btn-info">
                    <span>
                        <i class="fas fa-user"></i>
                        <span>
                            New Employee
                        </span>
                    </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-footer">

        </div>
    </div>
@endsection
