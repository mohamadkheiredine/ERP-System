<?php

?>


@extends('layouts.layout',['page_title' => "Deduction & Benefits Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/dedben.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/payrolls/savededben.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add New Charges</h3>
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
            <form name="frm_save_dedben" id="FORM_SAVE_DEDBEN">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Benefet Deduction  Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Company </label>
                                <select  name="db_company_id" id="DB_COMPANY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Company">
                                    <option value="">Select Company</option>
                                    @foreach ( $lst_companies as $key => $company_info )
                                    <option value="{{  $company_info->cd_id }}">{{  $company_info->cd_company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Employee </label>
                                <select  name="db_user_id" id="DB_USER_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Employee">
                                    <option value="">Select Employee</option>
                                    @foreach ( $lst_employees as $key => $employee_info )
                                        <option value="{{  $employee_info->id }}">{{  $employee_info->u_fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <div class="form-group">
                                <label class="control-label"> Charges Label <span class="required"> * </span></label>
                                <input type="text" name="db_ben_ded_label" id="DB_BEN_DED_LABEL" class="form-control" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Charge Amount <span class="required"> * </span></label>
                                <input type="text" name="db_amount" id="DB_AMOUNT" class="form-control" required="required" maxlength="100"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency </label>
                                <select  name="db_currency_id" id="DB_CURRENCY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                    <option value="">Select Currency</option>
                                    @foreach ( $lst_currencies as $key => $currency_info )
                                        <option value="{{  $currency_info->cc_id }}">{{  $currency_info->cc_currency_name }} - {{  $currency_info->cc_currency_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Type </label>
                                <select  name="db_type" id="DB_TYPE" class="form-control form-select" data-control="select2" data-placeholder="Select Charges Type">
                                    <option value="">Select Type</option>
                                    <option value="deduction">deduction</option>
                                    <option value="benefit">Bonuses</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Effective Date <span class="required"> * </span></label>
                                <input type="text" name="db_effective_date" id="DB_EFFECTIVE_DATE" class="form-control" required="required" maxlength="20"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">End Date <span class="required"> * </span></label>
                                <input type="text" name="db_end_date" id="DB_END_DATE" class="form-control" required="required" maxlength="20"  value="" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Charge Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="DB_DESCRIPTION"  class="form-control" name="db_description"  cols=""></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_debben" id="BTN_SAVE_DEDBEN"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection
