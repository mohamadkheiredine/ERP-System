<?php
/***********************************************************
 * addform.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 3/15/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
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
    <script type="text/javascript" src="{{ url('js/modules/warehousefloors.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/inventory/savewarehousefloor.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add New Floor</h3>
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
            <form name="frm_save_floors" id="FORM_SAVE_FLOORS">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
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
                                <label class="control-label"> Floor Title <span class="required"> * </span></label>
                                <input type="text" name="wf_floor_title" id="WF_FLOOR_TITLE" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Warehouse </label>
                                <select  name="fk_warehouse_id" id="FK_WAREHOUSE_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Warehouse">
                                    <option value="">Select Warehouse</option>
                                    @foreach ( $lst_warehouses as $key => $warehouse_info )
                                        <option value="{{  $warehouse_info->w_id }}">{{  $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Zone </label>
                                <div class="DefaultZone">
                                    <select  name="fk_zone_id" id="FK_ZONE_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Zone">
                                        <option value="">Select Zones</option>
                                        @foreach ( $lst_zones as $key => $zone_info )
                                            <option value="{{  $zone_info->wz_id }}">{{  $zone_info->wz_zone_label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_floor" id="BTN_SAVE_FLOOR"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

