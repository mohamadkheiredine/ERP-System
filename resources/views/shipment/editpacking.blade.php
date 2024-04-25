<?php
/***********************************************************
editpacking.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Manage packing Prices Saved in database
***********************************************************/

?>
@extends('layouts.layout',['page_title' => "Packing Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/packing.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/shipment/savepacking.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Packing</h3>
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
       <form name="frm_save_packing" id="FORM_SAVE_PACKING">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                       <input type="hidden" name="cp_id" id="CP_ID" value="{{ $packing_info->cp_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Packing Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                  <label class="control-label"> Product Category <span class="required"> * </span></label>
                                    <select name="fk_category_id" id="FK_CATEGORY_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Category">
                                        @foreach ( $lst_product_categories as $key => $category_info )
                                        <option {{ $packing_info->fk_category_id == $category_info->pc_id ? "selected" : "" }} value="{{ $category_info->pc_id }}">{{ $category_info->pc_category }}</option>
                                        @endforeach
                                    </select>
                              </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Range Label</label>
                                <input type="text" name="cp_range_label" id="CP_RANGE_LABEL" class="form-control"  maxlength="255"  value="{{ $packing_info->cp_range_label }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Weight From</label>
                                <input type="number" min="0" max="5000" step="0.1"  name="cp_weight_from" id="CP_WEIGHT_FROM" class="form-control" required="required" value="{{ $packing_info->cp_weight_from }}" />
                            </div>
                        </div> 
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Weight to</label>
                                <input type="number" min="0" max="5000" step="0.1"  name="cp_weight_to" id="CP_WEIGHT_TO" class="form-control" required="required" value="{{ $packing_info->cp_weight_to }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                  <label class="control-label"> Unit</label>
                                    <select name="cp_weight_unit" id="CP_WEIGHT_UNIT"  class="form-control form-select" data-control="select2" data-placeholder="Select Unit">
                                        @foreach ( $lst_units as $key => $unit_info )
                                        <option {{ $packing_info->cp_weight_unit == $unit_info->su_id ? "selected" : "" }} value="{{ $unit_info->su_id }}">{{ $unit_info->su_unit_label }}</option>
                                        @endforeach
                                    </select>
                              </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Price Value</label>
                                <input type="text"  name="cp_price_range" id="CP_PRICE_RANGE" class="form-control"  value="{{ $packing_info->cp_price_range }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                  <label class="control-label"> Currency </label>
                                    <select name="cp_price_currency" id="CP_PRICE_CURRENCY"  class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                        @foreach ( $lst_currencies as $key => $currency_info )
                                        <option {{ $packing_info->cp_price_currency == $currency_info->cc_id? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code }}&nbsp;-&nbsp;{{ $currency_info->cc_currency_name }}</option>
                                        @endforeach
                                    </select>
                              </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_packing" id="BTN_SAVE_PACKING"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection