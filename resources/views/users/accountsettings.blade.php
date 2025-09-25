<?php
/***********************************************************
 * accountsettings.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/22/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>



@extends('layouts.layout',['page_title' => "Users Management"])

@section('themes')
    <style>
        th{
            cursor: pointer;
        }
        #ModelPopUp{
            width:800px;
        }
        .card-body{
            min-height:600px;
        }
    </style>
@endsection
@section('plugins')
    <script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
    <script type="text/javascript" src="{{ url('js/modules/users.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/users/accsettings.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">User Account Settings</h3>
            <div class="card-toolbar">

            </div>
        </div>
        <div class="card-body">
            <form name="form_account_settings" autocomplete="off" id="FORM_ACCOUNT_SETTINGS">
                <div  class="form-body">
             <span id="hidden_fields">
                {!! csrf_field() !!}
            </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> User Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Default Company</label>
                                <select  name="default_company" id="DEFAULT_COMPANY" class="form-select" data-control="select2" data-placeholder="Select Default Company">
                                    @foreach( $lst_companies as $key => $cmp_info)
                                        <option  {{ session('default_company_id') == $cmp_info->cd_id ? "selected" : "" }} value="{{ $cmp_info->cd_id }}">{{ $cmp_info->cd_company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" id="BTN_ACC_SETTINGS" name="btn_acc_settings" class="btn btn-primary btn-wide">Save</button>&nbsp;&nbsp;
                            <button type="button" id="BACK_FORM" name="back_form" class="btn btn-dark btn-wide">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
