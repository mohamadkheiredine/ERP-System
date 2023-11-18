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

@extends('layouts.layout',['page_title' => "Warehouse Management > Add Warehouse Zone"])

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
<script type="text/javascript" src="{{ url('js/modules/warehouses.module.js') }}"></script>
<script type="text/javascript">
$(function(){
	$('select').select2();
	$("#BTN_SAVE_ZONE").on('click',warehouses_module.SaveWarehouseZoneInfo);
})
</script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add Warehouse Zone</h3>
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
    <form name="frm_save_warehouse_zone" id="FORM_SAVE_WAREHOUSE_ZONE">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                        <input type="hidden" name="w_id" id="W_ID" value="{{ $warehouseInfo->w_id }}" > 
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Zone Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                         	<div class="col-md-6">
                                     <div class="form-group">
                                        <label class="control-label">Zone Label <span class="required"> * </span></label>
                                        <input type="text" name="wz_zone_label" id="WZ_ZONE_LABEL" class="form-control" required="required" maxlength="255"  value="" />
                                    </div>
                                </div>
                         	<div class="col-md-6">
                                     <div class="form-group">
                                        <label class="control-label">Zone Color <span class="required"> * </span></label>
                                        <input type="color" name="wz_zone_color" id="WZ_ZONE_COLOR" class="form-control" required="required" maxlength="10"  value="" />
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