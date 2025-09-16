<?php
/***********************************************************
 * editstore.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/14/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



?>
@extends('layouts.layout',['page_title' => "Stores Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/stores.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/stores/savestore.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Edit Existing Store</h3>
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
            <form name="frm_save_store" id="FORM_SAVE_STORE">
                <div class="form-body">
                     <span id="hidden_fields">
                         <input type="hidden" name="ps_id" value="{{ $store_info->ps_id }}" />
                        {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Store Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Store Name <span class="required"> * </span></label>
                                <input type="text" name="ps_store_name" id="PS_STORE_NAME" class="form-control" required="required" maxlength="255"  value="{{ $store_info->ps_store_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-control" data-control="select2" id="PS_COMPANY_ID" name="ps_company_id">
                                <option value="0">-- Select Company --</option>
                                @foreach($lst_companies as $index => $company_info)
                                    <option {{ $store_info->ps_company_id == $company_info->cd_id ? "selected" : "" }} value="{{ $company_info->cd_id }}">{{ $company_info->cd_company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-control" data-control="select2" id="PS_MANAGER_ID" name="ps_manager_id">
                                <option value="0">-- Select Manager --</option>
                                @foreach($lst_managers as $index => $manager_info)
                                    <option {{ $store_info->ps_company_id == $manager_info->id ? "selected" : "" }} value="{{ $manager_info->id }}">{{ $manager_info->u_fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Location <span class="required"> * </span></label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="PS_LOCATION"  class="form-control" name="ps_location"  cols="">{{ $store_info->ps_location }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_store" id="BTN_SAVE_STORE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
