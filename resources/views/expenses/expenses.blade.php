<?php
/***********************************************************
categories.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Product Categories Management
 ***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Expenses Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/expenses.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/expenses/expenses.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">	Expenses</h3>
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
                        <div class="form-group row align-items-center">
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
                                    <label class="control-label">Expense Category</label>
                                    <select  name="ac_category_id" id="EC_CATEGORY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Category" >
                                        <option value="">Select Category</option>
                                        @foreach ( $lst_categories as $key => $category_info )
                                            <option value="{{ $category_info->ec_id }}">{{ $category_info->ec_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Expense Employee</label>
                                    <select  name="ac_employee_id" id="EC_EMPLOYEE_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Employee" >
                                        <option value="">Select Employee</option>
                                        @foreach ( $lst_employees as $key => $employee_info )
                                            <option value="{{ $employee_info->id }}">{{ $employee_info->u_fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 m--align-right">

                    </div>
                </div>
            </div>
            <div class="row"><div class="col-md-12">&nbsp;</div></div>
            <div class="row">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <a href="{{ url('expenses/addform') }}" class="btn btn-info">
							<span>
								<i class="fas fa-user"></i>
								<span>
									New Expense
								</span>
							</span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 gy-7">
                            <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th style="width:2px;">#</th>
                                <th style="width:2px;">ID</th>
                                <th>Category</th>
                                <th>Employee</th>
                                <th>Expected Date</th>
                                <th>Amount</th>
                                <th style="width:4px;white-space: nowrap;text-align: center">edit</th>
                                <th style="width:4px;white-space: nowrap;text-align: center">Delete</th>
                            </tr>
                            </thead>
                            <tbody  class="LstExpenses"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10" align="left">
                    <ul id="ExpensesPagination" class="pagination-sm"></ul>
                </div>
                <div class="col-md-2" align="right"></div>
            </div>
            <!--end: Datatable -->
            <div class="row">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <a href="{{ url('expenses/addform') }}" class="btn btn-info">
							<span>
								<i class="fas fa-user"></i>
								<span>
									New Expense
								</span>
							</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
