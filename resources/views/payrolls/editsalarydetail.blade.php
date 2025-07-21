<?php
/***********************************************************
editsalarydetail
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




@extends('layouts.layout',['page_title' => "PayRoll Management"])

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
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/salarydetails.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/payrolls/savesalarydetail.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Edit Salary Details</h3>
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
            <form name="frm_save_saldetails" id="FORM_SAVE_SALDETAILS">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                          <input type="hidden" name="pd_id" value="{{ $details_info->pd_id }}" />
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Salary Details  Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Company </label>
                                <select  name="pd_company_id" id="PD_COMPANY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Company">
                                    <option value="">Select Company</option>
                                    @foreach ( $lst_companies as $key => $company_info )
                                        <option {{ $details_info->pd_company_id == $company_info->cd_id ? "selected" : ""}} value="{{  $company_info->cd_id }}">{{  $company_info->cd_company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Employee </label>
                                <select  name="pd_user_id" id="PD_USER_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Employee">
                                    <option value="">Select Employee</option>
                                    @foreach ( $lst_employees as $key => $employee_info )
                                        <option {{ $details_info->pd_user_id == $employee_info->id ? "selected" : ""}} value="{{  $employee_info->id }}">{{  $employee_info->u_fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <div class="form-group">
                                <label class="control-label"> Basic Salary <span class="required"> * </span></label>
                                <input type="text" name="pd_basic_salary" id="PD_BASIC_SALARY" class="form-control" maxlength="25"  value="{{ $details_info->pd_basic_salary }}" />
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <div class="form-group">
                                <label class="control-label"> Allowances <span class="required"> * </span></label>
                                <input type="text" name="pd_allowances" id="PD_ALLOWANCES" class="form-control" maxlength="25"  value="{{ $details_info->pd_allowances }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Deduction <span class="required"> * </span></label>
                                <input type="text" name="pd_deductions" id="PD_DEDUCTION" class="form-control" required="required" maxlength="25"  value="{{ $details_info->pd_deductions }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Total Comission <span class="required"> * </span></label>
                                <input type="text" name="pd_total_comissions" id="PD_TOTAL_COMISSION" class="form-control" required="required" maxlength="25"  value="{{ $details_info->pd_total_comissions }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency </label>
                                <select  name="pd_currency_id" id="PD_CURRENCY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                    <option value="">Select Currency</option>
                                    @foreach ( $lst_currencies as $key => $currency_info )
                                        <option {{ $details_info->pd_currency_id == $currency_info->cc_id ? "selected" : "" }} value="{{  $currency_info->cc_id }}">{{  $currency_info->cc_currency_name }} - {{  $currency_info->cc_currency_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Effective Date <span class="required"> * </span></label>
                                <input type="text" name="pd_effective_date" id="PD_EFFECTIVE_DATE" class="form-control" required="required" maxlength="20"  value="{{ $details_info->pd_effective_date }}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="PD_DESCRIPTION"  class="form-control" name="pd_description"  cols="">{{ $details_info->pd_description }}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="row" style="height:50px;"></div>
                    <div class="row">
                        <div class="col-md-12 LstComissions">

                        </div>
                    </div>
                    <div class="row" style="height:50px;"></div>
                    <div class="row">
                        <div class="col-md-12 SalaryInfo">

                        </div>
                    </div>
                    <div class="row" style="height:15px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_saldetails" id="BTN_SAVE_SALDETAILS"  class="btn btn-info">Save</button>
                            <button type="button" id="BTN_GENERATE_TRANSACTION" style="{{ $details_info->pd_salary_paid == 1 ? "display:none" : ""  }}" name="btn_generate_transaction" class="btn btn-danger">Generate Transaction</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection
