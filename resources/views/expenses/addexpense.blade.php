<?php
/***********************************************************
addexpense.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

 ***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Expenses - Add New Expense Record"])

@section('themes')
    <style>
        th {
            cursor: pointer;
        }

        #ModelPopUp {
            width: 800px;
        }
    </style>
@endsection
@section('plugins')
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/expenses.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/expenses/saveexpenses.js') }}"></script>
@endsection
@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add Expense Record</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                    <ul class="dropdown-menu">
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form name="frm_save_expense" id="FORM_SAVE_EXPENSE">
                <div class="form-body">
                    <span id="hidden_fields"> {!! csrf_field() !!} </span>
                    <div class="alert alert-success" style="display: none">
                        <strong>Success!</strong> Expense Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display: none">
                        <strong>Error!</strong> You have some form errors. Please check
                        below.
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Category</label>
                                <select  name="ac_category_id" id="AC_CATEGORY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Category" >
                                    <option value="">Category</option>
                                    @foreach ( $lst_categories as $key => $category_info )
                                        <option value="{{ $category_info->ec_id }}">{{ $category_info->ec_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Expense Status</label>
                                <select  name="ac_status_id" id="AC_STATUS_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Status" >
                                    <option value="">Status</option>
                                    @foreach ( $lst_expenses_status as $key => $status_info )
                                        <option value="{{ $status_info->ss_id }}">{{ $status_info->ss_status_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Employee </label>
                                <select  name="ac_employee_id" id="AC_EMPLOYEE_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Employee" >
                                    <option value="">Employee</option>
                                    @foreach ( $lst_employees as $key => $employee_info )
                                        <option value="{{ $employee_info->id }}">{{ $employee_info->u_fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Expense Date <span class="required"> * </span></label>
                                <input type="text" name="ac_expense_date" id="AC_EXPENSE_DATE" class="form-control" required="required" maxlength="15" value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Amount <span class="required"> * </span></label>
                                <input type="text" name="ac_amount" id="AC_AMOUNT" class="form-control" required="required" maxlength="15" value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Currency</label>
                                <select  name="ac_currency_id" id="AC_CURRENCY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                    <option value="">Currency</option>
                                    @foreach ( $lst_currencies as $key => $currency_info )
                                        <option value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_name }}&nbsp;(&nbsp;{{ $currency_info->cc_currency_code }}&nbsp;)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Payment Type</label>
                                <select  name="ac_payment_type" id="AC_PAYMENT_TYPE" class="form-control form-select" data-control="select2" data-placeholder="Select Payment Type">
                                    <option value="">Payment Type</option>
                                    @foreach ( $lst_payment_types as $key => $type_info )
                                        <option value="{{ $type_info->pt_id }}">{{ $type_info->pt_payment_type }}&nbsp;)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label">Description</label><br/>
                            <textarea style="width:100%;height:250px;" name="ac_description" id="AC_DESCRIPTION" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row" style="height: 5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_expense" id="BTN_SAVE_EXPENSE" class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
