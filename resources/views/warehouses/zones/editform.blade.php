<?php
/***********************************************************
editzone.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

 ***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Warehouse Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/warehousezones.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/inventory/savewarehousezones.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Edit Existing Zone</h3>
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
            <form name="frm_save_zones" id="FORM_SAVE_ZONES">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                         <input type="hidden" name="wz_id" value="{{ $zone_info->wz_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Zones Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Zone Label <span class="required"> * </span></label>
                                <input type="text" name="wz_zone_label" id="WZ_ZONE_LABEL" class="form-control" required="required" maxlength="255"  value="{{ $zone_info->wz_zone_label }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Zone Color</label>
                                <input type="color" name="wz_zone_color" id="WZ_ZONE_COLOR" class="form-control" required="required" maxlength="8"  value="{{ $zone_info->wz_zone_color }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Warehouse </label>
                                <select  name="fk_warehouse_id" id="FK_WAREHOUSE_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Warehouse">
                                    <option value="">Select Warehouse</option>
                                    @foreach ( $lst_warehouses as $key => $warehouse_info )
                                        <option {{ $zone_info->fk_warehouse_id == $warehouse_info->w_id  ? "selected" : "" }} value="{{  $warehouse_info->w_id }}">{{  $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Zone Width <span class="required"> * </span></label>
                                <input type="text" name="wz_zone_width" id="WZ_ZONE_WIDTH" class="form-control" required="required" maxlength="10"  value="{{ $zone_info->wz_zone_width  }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Unit <span class="required"> * </span></label>
                                <select  name="wz_zone_width_unit" id="WZ_ZONE_WIDTH_UNIT" class="form-control form-select" data-control="select2" data-placeholder="Select Unit">
                                    <option  {{ $zone_info->wz_zone_width_unit == "1"  ? "selected" : "" }} value="1">Meter</option>
                                    <option   {{ $zone_info->wz_zone_width_unit == "2"  ? "selected" : "" }} value="2">Kilometer</option>
                                    <option  {{ $zone_info->wz_zone_width_unit == "3"  ? "selected" : "" }}  value="3">Inch</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Zone Height <span class="required"> * </span></label>
                                <input type="text" name="wz_zone_height" id="WZ_ZONE_HEIGHT" class="form-control" required="required" maxlength="10"  value="{{ $zone_info->wz_zone_height  }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Unit <span class="required"> * </span></label>
                                <select name="wz_zone_height_unit" id="WZ_ZONE_HEIGHT_UNIT"  class="form-control form-select" data-control="select2" data-placeholder="Select Unit">
                                    <option  {{ $zone_info->wz_zone_height_unit == "1"  ? "selected" : "" }} value="1">Meter</option>
                                    <option  {{ $zone_info->wz_zone_height_unit == "2"  ? "selected" : "" }}  value="2">Kilometer</option>
                                    <option  {{ $zone_info->wz_zone_height_unit == "3"  ? "selected" : "" }}  value="3">Inch</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Zone Length</label>
                                <input type="text" name="wz_zone_length" id="WZ_ZONE_LENGTH" class="form-control"  maxlength="10"  value="{{ $zone_info->wz_zone_length  }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Unit </label>
                                <select name="wz_zone_length_unit" id="WZ_ZONE_LENGTH_UNIT"  class="form-control form-select" data-control="select2" data-placeholder="Select Unit">
                                    <option {{ $zone_info->wz_zone_length_unit == "1"  ? "selected" : "" }} value="1">Meter</option>
                                    <option {{ $zone_info->wz_zone_length_unit == "2"  ? "selected" : "" }}   value="2">Kilometer</option>
                                    <option {{ $zone_info->wz_zone_length_unit == "3"  ? "selected" : "" }}   value="3">Inch</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Zone Description <span class="required"> * </span></label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="WZ_ZONE_DESCRIPTION"  class="form-control" name="wz_zone_description"  cols="">{{ $zone_info->wz_zone_description  }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_zone" id="BTN_SAVE_ZONE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
