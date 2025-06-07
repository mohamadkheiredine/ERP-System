<?php
/***********************************************************
 * addlocation.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/1/2025
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
    <script type="text/javascript" src="{{ url('js/modules/alocations.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/assets/savelocation.js') }}"></script>
@endsection
@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add Location</h3>
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
            <form name="frm_save_location" id="FORM_SAVE_LOCATION">
                <div class="form-body">
                    <span id="hidden_fields">
                        {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display: none">
                        <strong>Success!</strong> Asset Location Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display: none">
                        <strong>Error!</strong> You have some form errors. Please check
                        below.
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Location <span class="required"> * </span></label>
                                <input type="text" name="il_location_name" id="IL_LOCATION_NAME" class="form-control" required="required" maxlength="255" value="" />
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Address <span class="required"> * </span></label>
                                <input type="text" name="il_address" id="IL_ADDRESS" class="form-control" required="required" maxlength="500" value="" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label">Description</label><br/>
                            <textarea style="width:100%;height:250px;" name="il_description" id="IL_DESCRIPTION" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row" style="height: 5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_location" id="BTN_SAVE_LOCATION" class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
