<?php
/***********************************************************
salarydetails
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


@extends('layouts.layout',['page_title' => "Salary Details Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/salarydetails.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/payrolls/salarydetails.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Salary Details Management</h3>
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
            <div class="col-md-12">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <!--begin::Input group-->
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
                            <div class="col-md-4">
                                <select class="form-control form-select" name="pd_company_id" id="DB_COMPANY_ID"  data-control="select2" data-placeholder="Select a Branch" tabindex="2">
                                <option value="0"> -- Company -- </option>
                                @foreach ( $lst_companies as $key => $company_info )
                                    <option value="{{ $company_info->cd_id }}">{{ $company_info->cd_company_name }}</option>
                                    @endforeach
                                    </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control form-select" name="pd_user_id" id="PD_USER_ID"  data-control="select2" data-placeholder="Select Employee" tabindex="2">
                                <option value="0"> -- Employee -- </option>
                                @foreach ( $lst_employees as $key => $employee_info )
                                    <option value="{{ $employee_info->u_id }}">{{ $employee_info->u_fullname }}</option>
                                    @endforeach
                                    </select>
                            </div>
                            <div class="col-md-4">

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 align-right">
                        <a href="{{ url('payroll/salarydetails/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-user"></i>
							<span>
								Add New
							</span>
						</span>
                        </a>
                    </div>
                </div>
            </div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <div id="LstDedBen" class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 gy-7">
                    <thead>
                    <tr class="fw-bold fs-6 text-gray-800">
                        <th title="#">#</th>
                        <th title="Id"> ID </th>
                        <th title="Company"> Company </th>
                        <th title="Employee"> Employee </th>
                        <th title="Base Salary"> Base Salary </th>
                        <th title="Total Comission"> Total Comission </th>
                        <th title="Deduction"> Deduction </th>
                        <th title="Benefit"> Benefit </th>
                        <th title="Date"> Date </th>
                        <th title="edit"> edit </th>
                        <th title="delete"> Delete </th>
                    </tr>
                    </thead>
                    <tbody class="LstSallaryDetails">
                    </tbody>
                </table>


            </div>
            <!--end: Datatable -->
            <div class="row">
                <div class="col-md-7 col-lg-7 col-xs-7" align="left">
                    <ul id="SalDetailsPagination" class="pagination-sm"></ul>
                </div>
                <div class="col-md-5 col-lg-5 col-xs-5" align="right">
                    <a href="{{ url('payroll/salarydetails/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-user"></i>
							<span>
								Add New
							</span>
						</span>
                    </a>
                    <button type="button" name="btn_generate_payrolls" class="btn btn-success">Generate Details</button>
                </div>
            </div>
        </div>
    </div>
@endsection

