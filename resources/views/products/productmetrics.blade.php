<?php
/***********************************************************
productmetrics.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 6, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
weight
***********************************************************/
?>

<div class="row">
	<div class="col-md-4">
         <div class="form-group">
            <label class="control-label">Product Weight <span class="required"> * </span></label>
            <input type="text" name="p_product_weight" id="P_PRODUCT_WEIGHT" class="form-control" required="required" maxlength="100"  value="{{ $product_info->p_product_weight }}" />
        </div>
    </div>
	<div class="col-md-2">
         <div class="form-group">
            <label class="control-label">Unit <span class="required"> * </span></label><br/>
             <select class="bs-select form-control" name="p_product_weight_unit" id="P_PRODUCT_WEIGHT_UNIT" data-actions-box="true">
             		@foreach($lst_units_weight as $index => $units_info)
             			<option {{ $product_info->p_product_weight_unit == $units_info->su_id ? "selected" : "" }} value="{{ $units_info->su_id }}">{{ $units_info->su_unit_label }}</option>
             		@endforeach
            </select>
        </div>
    </div>
    @if( $p_product_unit_type != 'weight' ) 
	<div class="col-md-4">
         <div class="form-group">
            <label class="control-label">Product length <span class="required"> * </span></label>
            <input type="text" name="p_product_length" id="P_PRODUCT_LENGTH" class="form-control" required="required" maxlength="100"  value="{{ $product_info->p_product_length }}" />
        </div>
    </div>
	<div class="col-md-2">
         <div class="form-group">
            <label class="control-label">Unit <span class="required"> * </span></label><br/>
             <select class="bs-select form-control" name="p_product_length_unit" id="P_PRODUCT_LENGTH_UNIT" data-actions-box="true">
             		@foreach($lst_units_size as $index => $units_info)
             			<option {{ $product_info->p_product_length_unit == $units_info->su_id ? "selected" : "" }} value="{{ $units_info->su_id }}">{{ $units_info->su_unit_label }}</option>
             		@endforeach 
            </select>
        </div>
    </div>
	<div class="col-md-4">
         <div class="form-group">
            <label class="control-label">Product Width <span class="required"> * </span></label>
            <input type="text" name="p_product_width" id="P_PRODUCT_WIDTH" class="form-control" required="required" maxlength="10"  value="{{ $product_info->p_product_width }}" />
        </div>
    </div>
	<div class="col-md-2">
         <div class="form-group">
            <label class="control-label">Unit <span class="required"> * </span></label><br/>
             <select class="bs-select form-control" name="p_product_width_unit" id="P_PRODUCT_WIDTH_UNIT" data-actions-box="true">
                   @foreach($lst_units_size as $index => $units_info)
             			<option {{ $product_info->p_product_width_unit == $units_info->su_id ? "selected" : "" }} value="{{ $units_info->su_id }}">{{ $units_info->su_unit_label }}</option>
             		@endforeach 
            </select>
        </div>
    </div>
	<div class="col-md-4">
         <div class="form-group">
            <label class="control-label">Product Height <span class="required"> * </span></label>
            <input type="text" name="p_product_height" id="P_PRODUCT_HEIGHT" class="form-control" required="required" maxlength="10"  value="{{ $product_info->p_product_height }}" />
        </div>
    </div>
	<div class="col-md-2">
         <div class="form-group">
            <label class="control-label">Unit <span class="required"> * </span></label><br/>
             <select class="bs-select form-control" name="p_product_height_unit" id="P_PRODUCT_HEIGHT_UNIT" data-actions-box="true">
                    @foreach($lst_units_size as $index => $units_info)
             			<option {{ $product_info->p_product_height_unit  == $units_info->su_id ? "selected" : "" }} value="{{ $units_info->su_id }}">{{ $units_info->su_unit_label }}</option>
             		@endforeach 
            </select>
        </div>
    </div>
    @endif
</div>