<?php
/***********************************************************
 * addform.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/2/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



?>


@extends('layouts.layout',['page_title' => "Assets Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/assets.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/assets/saveasset.js') }}"></script>
@endsection
@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add Asset</h3>
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
            <form name="frm_save_asset" id="FORM_SAVE_ASSET">
                <div class="form-body">
                    <span id="hidden_fields">
                        {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display: none">
                        <strong>Success!</strong> Asset Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display: none">
                        <strong>Error!</strong> You have some form errors. Please check
                        below.
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Asset Name <span class="required"> * </span></label>
                                <input type="text" name="aa_asset_name" id="AA_ASSET_NAME" class="form-control" required="required" maxlength="255" value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Asset Category</label>
                                <select  name="aa_category_id" id="AA_CATEGORY_ID" class="form-select" data-control="select2" data-placeholder="Select Category">
                                    <option value="">No Category</option>
                                    @foreach ( $lst_categories as $key => $category_info )
                                        <option value="{{ $category_info->ac_id }}">{{ $category_info->ac_category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Purchase Date <span class="required"> * </span></label>
                                <input type="text" name="aa_purchase_date" id="AA_PURCHASE_DATE" class="form-control" required="required" maxlength="10" readonly value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Purchase Price <span class="required"> * </span></label>
                                <input type="text" name="aa_purchase_price" id="AA_PURCHASE_PRICE" class="form-control" required="required" maxlength="10" value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Currency</label>
                                <select  name="aa_currency_id" id="AA_CURRENCY_ID" class="form-select" data-control="select2" data-placeholder="Select Currency">
                                    <option value="">No Currency</option>
                                    @foreach ( $lst_currencies as $key => $currency_info )
                                        <option value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_name }}  ( {{ $currency_info->cc_currency_code }} )</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Depreciation Rate <span class="required"> * </span></label>
                                <input type="text" name="aa_depreciation_rate" id="AA_DEPRECIATION_RATE" class="form-control" required="required" maxlength="10" value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Current Value <span class="required"> * </span></label>
                                <input type="text" name="aa_current_value" id="AA_CURRENT_VALUE" class="form-control" required="required" maxlength="12" value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Location</label>
                                <select  name="aa_location" id="AA_LOCATION" class="form-select" data-control="select2" data-placeholder="Select Location">
                                    <option value="">Select Location</option>
                                    @foreach ( $lst_locations as $key => $location_info )
                                        <option value="{{ $location_info->il_id }}">{{ $location_info->il_location_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Account</label>
                                <select  name="aa_account_id" id="AA_ACCOUNT_ID" class="form-select" data-control="select2" data-placeholder="Select Account">
                                    <option value="">Select Account</option>
                                    @foreach ( $lst_accounts as $key => $account_info )
                                        <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_label }} ({{ $account_info->aa_account_ref }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Status</label>
                                <select  name="aa_status" id="AA_STATUS" class="form-select" data-control="select2" data-placeholder="Select Status">
                                    <option value="">Select Status</option>
                                    <option value="1">In-active</option>
                                    <option value="2">Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label">Description</label><br/>
                            <textarea style="width:100%;height:250px;" name="aa_asset_description" id="AA_ASSET_DESCRIPTION" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row" style="height: 5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_asset" id="BTN_SAVE_ASSET" class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
