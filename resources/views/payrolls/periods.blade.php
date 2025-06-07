<?php
/***********************************************************
periods
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 21, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Order Status Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/payrollperiods.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/payroll/payrollperiods.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">PayRoll Periods</h3>
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
        <span id="hidden_fields">
            <input type="hidden" name="page_number" value="1" />
        </span>
            <!--begin: Search Form -->
            <div class="form">
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
                                        <input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" />
                                    </div>
                                    <!--end::Input group-->
                                </div>

                            </div>
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-4">


                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 align-right">
                        <a href="{{ url('/hr/payrollsperiods/addform') }}" class="btn btn-info">
                        <span>
                            <i class="flaticon-grid-menu-v2"></i>
                            <span>
                                New Period
                            </span>
                        </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="height:20px"></div>
            </div>
            <div class="table-responsive">
                <table class="table table-rounded table-striped border gy-7 gs-7">
                    <thead>
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th title="#">#</th>
                        <th title="Id"> ID </th>
                        <th title="Period Label"> Period Label </th>
                        <th title="Start Date"> Start Date </th>
                        <th title="End Date"> End Date </th>
                        <th title="Status"> Status </th>
                        <th style="width:2px;" nowrap title="#"> edit </th>
                        <th style="width:2px;" nowrap title="#"> Delete </th>
                    </tr>
                    </thead>
                    <tbody id="LstPayrollPeriods" class="LstPayrollPeriods">

                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-10" align="left">
                    <ul id="PeriodsPagination" class="pagination-sm"></ul>
                </div>
                <div class="col-md-2" align="right"></div>
            </div>
            <!--end: Datatable -->
            <div class="row">
                <div class="col-xl-8 order-1 order-xl-1 align-right"></div>
                <div class="col-xl-2 order-2 order-xl-2 align-right">

                </div>
                <div class="col-xl-2 order-3 order-xl-3 align-right">
                    <a href="{{ url('/hr/payrollsperiods/addform') }}" class="btn btn-info">
                    <span>
                        <i class="flaticon-grid-menu-v2"></i>
                        <span>
                            New Period
                        </span>
                    </span>
                    </a>
                </div>

            </div>


        </div>
    </div>
@endsection
