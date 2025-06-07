<?php
/***********************************************************
 * edittaxbracket.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 3/4/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Tax Brackets Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/taxbrackets.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/payrolls/savebrackets.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Edit Existing Tax Bracket</h3>
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
            <form name="frm_save_bracket" id="FORM_SAVE_BRACKET">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                          <input type="hidden" name="tb_bracket_id" value="{{ $taxes_info->tb_bracket_id  }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Tax Bracket Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Company </label>
                                <select  name="tb_company_id" id="TB_COMPANY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Company">
                                    <option value="">Select Company</option>
                                    @foreach ( $lst_companies as $key => $company_info )
                                        <option {{ $taxes_info->tb_company_id == $company_info->cd_id ? "selected" : "" }} value="{{  $company_info->cd_id }}">{{  $company_info->cd_company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <div class="form-group">
                                <label class="control-label"> Bracket Code <span class="required"> * </span></label>
                                <input type="text" name="tb_bracket_code" id="TB_BRACKET_CODE" class="form-control" maxlength="10"  value="{{ $taxes_info->tb_bracket_code  }}" />
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <div class="form-group">
                                <label class="control-label"> Bracket Label <span class="required"> * </span></label>
                                <input type="text" name="tb_bracket_label" id="TB_BRACKET_LABEL" class="form-control" maxlength="255"  value="{{ $taxes_info->tb_bracket_label  }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Min Salary <span class="required"> * </span></label>
                                <input type="text" name="tb_min_salary" id="TB_MIN_SALARY" class="form-control" required="required" maxlength="20"  value="{{ $taxes_info->tb_min_salary }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Max Salary <span class="required"> * </span></label>
                                <input type="text" name="tb_max_salary" id="TB_MAX_SALARY" class="form-control" required="required" maxlength="20"  value="{{ $taxes_info->tb_max_salary }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Tax Rate <span class="required"> * </span></label>
                                <input type="text" name="tb_tax_rate" id="TB_TAX_RATE" class="form-control" required="required" maxlength="20"  value="{{ $taxes_info->tb_tax_rate }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Region <span class="required"> * </span></label>
                                <input type="text" name="tb_region" id="TB_REGION" class="form-control" required="required" maxlength="255"  value="{{ $taxes_info->tb_region }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency </label>
                                <select  name="tb_currency_id" id="TB_CURRENCY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                    <option value="">Select Currency</option>
                                    @foreach ( $lst_currencies as $key => $currency_info )
                                        <option {{ $taxes_info->tb_currency_id == $currency_info->cc_id ? "selected" : "" }} value="{{  $currency_info->cc_id }}">{{  $currency_info->cc_currency_name }} - {{  $currency_info->cc_currency_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Charge Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="TB_BRACKET_DESCRIPTION"  class="form-control" name="tb_bracket_description"  cols="">{{ $taxes_info->tb_bracket_description }}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_bracket" id="BTN_SAVE_BRACKET"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection
