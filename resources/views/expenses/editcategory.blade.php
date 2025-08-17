<?php
/***********************************************************
editcategory.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

 ***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Expenses Categories - Edit Category"])

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
    <script type="text/javascript" src="{{ url('js/modules/expensecategories.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/expenses/savecategory.js') }}"></script>
@endsection
@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Edit Category</h3>
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
            <form name="frm_save_category" id="FORM_SAVE_CATEGORY">
                <div class="form-body">
                    <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="ec_id" value="{{ $category_info->ec_id }}" />
                    </span>
                    <div class="alert alert-success" style="display: none">
                        <strong>Success!</strong> Category Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display: none">
                        <strong>Error!</strong> You have some form errors. Please check
                        below.
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Category <span class="required"> * </span></label>
                                <input type="text" name="ec_name" id="EC_NAME" class="form-control" required="required" maxlength="255" value="{{ $category_info->ec_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Max Amount </label>
                                <input type="text" name="ec_max_amount" id="EC_MAX_AMOUNT" class="form-control"  maxlength="15" value="{{ $category_info->ec_max_amount }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Currency</label>
                                <select  name="ec_currency_id" id="EC_CURRENCY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                    <option value="">Currency</option>
                                    @foreach ( $lst_currencies as $key => $currency_info )
                                        <option {{ $category_info->ec_currency_id == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_name }}&nbsp;(&nbsp;{{ $currency_info->cc_currency_code }}&nbsp;)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Account Linked To</label>
                                <select  name="ec_gl_account_id" id="EC_GL_ACCOUNT_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Account Linked To">
                                    <option value="">Currency</option>
                                    @foreach ( $lst_accounts as $key => $account_info )
                                        <option {{ $category_info->ec_gl_account_id == $account_info->aa_id ? "selected" : "" }} value="{{ $account_info->aa_id }}">{{ $account_info->aa_account }}&nbsp;(&nbsp;{{ $account_info->aa_account_label }}&nbsp;)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Parent Category</label>
                                <select  name="ec_parent_category" id="EC_PARENT_CATEGORY" class="form-control form-select" data-control="select2" data-placeholder="Select Category" >
                                    <option value="">No Parent</option>
                                    @foreach ( $lst_categories as $key => $category_info )
                                        <option {{ $category_info->ec_parent_category ==  $category_info->ec_id ? "selected" : "" }} value="{{ $category_info->ec_id }}">{{ $category_info->ec_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label">Description</label><br/>
                            <textarea style="width:100%;height:250px;" name="ec_description" id="EC_DESCRIPTION" class="form-control">{{ $category_info->ec_description }}</textarea>
                        </div>
                    </div>
                    <div class="row" style="height: 5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_category" id="BTN_SAVE_CATEGORY" class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
