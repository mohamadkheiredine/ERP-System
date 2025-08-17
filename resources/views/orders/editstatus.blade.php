<?php
/***********************************************************
editstatus.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 9, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Edit Existing Order Status
***********************************************************/


?>
@extends('layouts.layout',['page_title' => "Order Status Management"])

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
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/orderstatus.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/orders/savestatus.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Order Status Management</h3>
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
            <form name="frm_save_status" id="FORM_SAVE_STATUS">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                       <input type="hidden" name="os_id" id="OS_ID" value="{{ $status_info->os_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Order Status Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Status Title <span class="required"> * </span></label>
                                <input type="text" name="os_status_title" id="OS_STATUS_TITLE" class="form-control" required="required" maxlength="255"  value="{{ $status_info->os_status_title }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Status Color</label>
                                <input type="color" name="os_status_color" id="OS_STATUS_COLOR" class="form-control" required="required" maxlength="8"  value="{{ $status_info->os_status_color }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Status Order</label>
                                <input type="number" min="0" max="100" step="1"  name="os_status_order" id="OS_STATUS_ORDER" class="form-control" required="required" value="{{ $status_info->os_status_order }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Parent Status</label>
                                <select class="bs-select form-control" name="os_status_parent_id" id="OS_STATUS_PARENT_ID" data-actions-box="true">
                                    <option value="">No Parent</option>
                                    @foreach ( $lst_order_status as $key => $status_info )
                                        <option {{ $status_info->os_status_parent_id == $status_info->os_id ? "selected" : "" }} value="{{ $status_info->os_id }}">{{ $status_info->os_status_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_status" id="BTN_SAVE_STATUS"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
