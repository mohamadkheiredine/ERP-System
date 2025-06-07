<?php
/***********************************************************
 * editdepreciation.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/3/2025
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
    <script type="text/javascript" src="{{ url('js/modules/adepreciation.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/assets/savedepreciation.js') }}"></script>
@endsection
@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Edit Asset Depreciation</h3>
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
            <form name="frm_save_depreciation" id="FORM_SAVE_DEPRECIATION">
                <div class="form-body">
                    <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="ad_id" value="{{  $asset_depreciation->ad_id }}" />
                    </span>
                    <div class="alert alert-success" style="display: none">
                        <strong>Success!</strong> Asset Depreciation Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display: none">
                        <strong>Error!</strong> You have some form errors. Please check
                        below.
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Year <span class="required"> * </span></label>
                                <input type="text" name="ad_year" id="AD_YEAR" class="form-control" required="required" maxlength="12" value="{{ $asset_depreciation->ad_year }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Asset <span class="required"> * </span></label>
                                <select  name="ad_asset_id" id="AD_ASSET_ID" class="form-select" data-control="select2" data-placeholder="Select Asset">
                                    <option value="">No Asset</option>
                                    @foreach ( $lst_assets as $key => $asset_info )
                                        <option {{ $asset_depreciation->ad_asset_id == $asset_info->aa_id ? "selected" : "" }} value="{{ $asset_info->aa_id }}">{{ $asset_info->aa_asset_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Depreciation Amount <span class="required"> * </span></label>
                                <input type="text" name="ad_depreciation_amount" id="AD_DEPRECIATION_AMOUNT" class="form-control" required="required" maxlength="12" value="{{ $asset_depreciation->ad_depreciation_amount }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">New Value <span class="required"> * </span></label>
                                <input type="text" name="ad_new_value" id="AD_NEW_VALUE" class="form-control" required="required" maxlength="12" value="{{ $asset_depreciation->ad_new_value }}" />
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height: 5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_depreciation" id="BTN_SAVE_DEPRECIATION" class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
