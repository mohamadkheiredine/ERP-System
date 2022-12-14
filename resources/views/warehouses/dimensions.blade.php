<?php
/***********************************************************
dimensions.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 25, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Dimension of wwarehouse 
***********************************************************/

?>
<div class="row">
		<div class="col-md-8">
             <div class="form-group">
                <label class="control-label">Warehouse length <span class="required"> * </span></label>
                <input type="text" name="w_warehouse_length" id="W_WAREHOUSE_LENGTH" class="form-control" required="required" maxlength="100"  value="{{ $warehouse_info->w_warehouse_length }}" />
            </div>
        </div>
		<div class="col-md-4">
             <div class="form-group">
                <label class="control-label">Unit <span class="required"> * </span></label>
                 <select class="bs-select form-control" name="w_warehouse_length_unit" id="W_WAREHOUSE_LENGTH_UNIT" data-actions-box="true">
                        <option {{ $warehouse_info->w_warehouse_length_unit == 1 ? "selected" : "" }} value="1">Meter</option>
                        <option {{ $warehouse_info->w_warehouse_length_unit == 2 ? "selected" : "" }} value="2">Kilometer</option> 
                        <option {{ $warehouse_info->w_warehouse_length_unit == 3 ? "selected" : "" }} value="3">Inch</option>
                </select>
            </div>
        </div>
		<div class="col-md-8">
             <div class="form-group">
                <label class="control-label">Warehouse Width <span class="required"> * </span></label>
                <input type="text" name="w_warehouse_width" id="W_WAREHOUSE_WIDTH" class="form-control" required="required" maxlength="10"  value="{{ $warehouse_info->w_warehouse_width }}" />
            </div>
        </div>
		<div class="col-md-4">
             <div class="form-group">
                <label class="control-label">Unit <span class="required"> * </span></label>
                 <select class="bs-select form-control" name="w_warehouse_width_unit" id="W_WAREHOUSE_WIDTH_UNIT" data-actions-box="true">
                        <option {{ $warehouse_info->w_warehouse_width_unit == 1 ? "selected" : "" }} value="1">Meter</option>
                        <option {{ $warehouse_info->w_warehouse_width_unit == 2 ? "selected" : "" }} value="2">Kilometer</option> 
                        <option {{ $warehouse_info->w_warehouse_width_unit == 3 ? "selected" : "" }} value="3">Inch</option>
                </select>
            </div>
        </div>
		<div class="col-md-8">
             <div class="form-group">
                <label class="control-label">Warehouse Height <span class="required"> * </span></label>
                <input type="text" name="w_warehouse_height" id="W_WAREHOUSE_HEIGHT" class="form-control" required="required" maxlength="10"  value="{{ $warehouse_info->w_warehouse_height }}" />
            </div>
        </div>
		<div class="col-md-4">
             <div class="form-group">
                <label class="control-label">Unit <span class="required"> * </span></label>
                 <select class="bs-select form-control" name="w_warehouse_height_unit" id="W_WAREHOUSE_HEIGHT_UNIT" data-actions-box="true">
                        <option {{ $warehouse_info->w_warehouse_height_unit == 1 ? "selected" : "" }} value="1">Meter</option>
                        <option {{ $warehouse_info->w_warehouse_height_unit == 2 ? "selected" : "" }} value="2">Kilometer</option> 
                        <option {{ $warehouse_info->w_warehouse_height_unit == 3 ? "selected" : "" }} value="3">Inch</option>
                </select>
            </div>
        </div>
		<div class="col-md-12">
              <button type="button" name="btn_draw_image" id="BTN_DRAW_IMAGE" class="btn btn-info" > Draw Image </button>
        </div>
</div>