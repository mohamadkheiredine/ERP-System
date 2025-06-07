<?php
/***********************************************************
 * edittransfer.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/4/2025
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
    <script type="text/javascript" src="{{ url('js/modules/atransfer.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/assets/savetransfer.js') }}"></script>
@endsection
@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Transfer Asset</h3>
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
            <form name="frm_save_transfer" id="FORM_SAVE_TRANSFER">
                <div class="form-body">
                    <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="at_id" value="{{ $asset_transfer->at_id  }}" />
                    </span>
                    <div class="alert alert-success" style="display: none">
                        <strong>Success!</strong> Transfer Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display: none">
                        <strong>Error!</strong> You have some form errors. Please check
                        below.
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Asset</label>
                                <select  name="at_asset_id" id="AT_ASSET_ID" class="form-select" data-control="select2" data-placeholder="Select Asset">
                                    <option value="">No Asset</option>
                                    @foreach ($lst_assets as $key => $asset_info )
                                        <option {{ $asset_transfer->at_asset_id == $asset_info->aa_id ? "selected" : ""  }} value="{{ $asset_info->aa_id }}">{{ $asset_info->aa_asset_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">From Location</label>
                                <select  name="at_from_location_id" id="AT_FROM_LOCATION_ID" class="form-select" data-control="select2" data-placeholder="Select Location From">
                                    <option value="">Location</option>
                                    @foreach ($lst_asset_locations as $key => $loca_info )
                                        <option {{ $asset_transfer->at_from_location_id == $loca_info->il_id  ? "selected" : ""  }} value="{{ $loca_info->il_id }}">{{ $loca_info->il_location_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">To Location</label>
                                <select  name="at_to_location_id" id="AT_TO_LOCATION_ID" class="form-select" data-control="select2" data-placeholder="Select Location From">
                                    <option value="">Location</option>
                                    @foreach ($lst_asset_locations as $key => $loca_info )
                                        <option {{ $asset_transfer->at_to_location_id == $loca_info->il_id  ? "selected" : ""  }} value="{{ $loca_info->il_id }}">{{ $loca_info->il_location_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">From Department</label>
                                <select  name="at_from_department_id" id="AT_FROM_DEPARTMENT_ID" class="form-select" data-control="select2" data-placeholder="Select Department From">
                                    <option value="">Department</option>
                                    @foreach ($lst_departments as $key => $dep_info )
                                        <option {{ $asset_transfer->at_from_department_id == $dep_info->sd_id ? "selected" : ""  }} value="{{ $dep_info->sd_id }}">{{ $dep_info->sd_department_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">To Department</label>
                                <select  name="at_to_department_id" id="AT_TO_DEPARTMENT_ID" class="form-select" data-control="select2" data-placeholder="Select Department To">
                                    <option value="">Department</option>
                                    @foreach ($lst_departments as $key => $dep_info )
                                        <option {{ $asset_transfer->at_to_department_id == $dep_info->sd_id ? "selected" : ""  }} value="{{ $dep_info->sd_id }}">{{ $dep_info->sd_department_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Transfer Date <span class="required"> * </span></label>
                                <input type="text" name="at_transfer_date" id="AT_TRANSFER_DATE" class="form-control" required="required" maxlength="10" readonly value="{{ $asset_transfer->at_transfer_date  }}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label">Transfer Reason</label><br/>
                            <textarea style="width:100%;height:250px;" name="at_transfer_reason" id="AT_TRANSFER_REASON" class="form-control">{{ $asset_transfer->at_transfer_reason  }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label">Remarks</label><br/>
                            <textarea style="width:100%;height:250px;" name="at_remarks" id="AT_REMARKS" class="form-control">{{ $asset_transfer->at_remarks }}</textarea>
                        </div>
                    </div>
                    <div class="row" style="height: 5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_transfer" id="BTN_SAVE_TRANSFER" class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
