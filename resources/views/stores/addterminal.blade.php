<?php
/***********************************************************
 * addterminal.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/21/2025
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
    <script type="text/javascript" src="{{ url('js/modules/terminals.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/stores/saveterminal.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add New Terminal</h3>
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
            <form name="frm_save_terminal" id="FORM_SAVE_TERMINAL">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Terminal Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Terminal Name <span class="required"> * </span></label>
                                <input type="text" name="pt_terminal_name" id="PT_TERMINAL_NAME" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Store <span class="required"> * </span></label>
                            <select class="form-select form-control" data-control="select2" id="PT_STORE_ID" name="pt_store_id">
                                <option value="0">-- Select Store --</option>
                                @foreach($lst_stores as $index => $store_info)
                                    <option value="{{ $store_info->ps_id }}">{{ $store_info->ps_store_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Manager <span class="required"> * </span></label>
                            <select class="form-select form-control" data-control="select2" id="PT_MANAGER_ID" name="pt_manager_id">
                                <option value="0">-- Select Manager --</option>
                                @foreach($lst_managers as $index => $manager_info)
                                    <option value="{{ $manager_info->id }}">{{ $manager_info->u_fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Warehouse <span class="required"> * </span></label>
                                <select class="form-select form-control" data-control="select2"  id="PT_WAREHOUSES_ID" name="pt_warehouse_id">
                                    @foreach($lst_warehouses as $index => $warehouse_info)
                                        <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <br/>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="pt_is_active" id="PT_IS_ACTIVE"  value="1"  />
                                    <span class="form-check-label fw-semibold text-muted">
                                           is Activate
                                        </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="PT_DESCRIPTION"  class="form-control" name="pt_description"  cols=""></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_terminal" id="BTN_SAVE_TERMINAL"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
