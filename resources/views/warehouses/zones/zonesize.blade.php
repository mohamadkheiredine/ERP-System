<?php
/***********************************************************
zonesize.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 25, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/
{
    $wz_zone_length = isset( $warehouseZoneInfo ) ? $warehouseZoneInfo->wz_zone_length : ""; 
    $wz_zone_width  = isset( $warehouseZoneInfo ) ? $warehouseZoneInfo->wz_zone_width : ""; 
    $wz_zone_height = isset( $warehouseZoneInfo ) ? $warehouseZoneInfo->wz_zone_height : ""; 
    $wz_zone_length_unit    = isset( $warehouseZoneInfo ) ? $warehouseZoneInfo->wz_zone_length_unit : 0; 
    $wz_zone_width_unit     = isset( $warehouseZoneInfo ) ? $warehouseZoneInfo->wz_zone_width_unit  : 0; 
    $wz_zone_height_unit    = isset( $warehouseZoneInfo ) ? $warehouseZoneInfo->wz_zone_height_unit : 0; 
}
?>
<div class="col-md-4">
     <div class="form-group">
        <label class="control-label">Zone length <span class="required"> * </span></label>
        <input type="text" name="wz_zone_length" id="WZ_ZONE_LENGTH" class="form-control" required="required" maxlength="100"  value="{{ $wz_zone_length }}" />
    </div>
</div>
<div class="col-md-2">
     <div class="form-group">
        <label class="control-label">Unit <span class="required"> * </span></label>
         <select class="bs-select form-control" name="wz_zone_length_unit" id="W_ZONE_LENGTH_UNIT" data-actions-box="true">
         		@if($warehouseInfo->w_warehouse_length_unit == 1)
                <option {{ $wz_zone_length_unit == 1 ? "selected" : "" }} value="1">Meter</option>
                @elseif($warehouseInfo->w_warehouse_length_unit == 2)
                <option {{ $wz_zone_length_unit == 2 ? "selected" : "" }} value="2">Kilometer</option>
                @elseif($warehouseInfo->w_warehouse_length_unit == 3)
                <option {{ $wz_zone_length_unit == 3 ? "selected" : "" }} value="3">Inch</option>
                @endif
        </select>
    </div>
</div>
<div class="col-md-4">
     <div class="form-group">
        <label class="control-label">Zone Width <span class="required"> * </span></label>
        <input type="text" name="wz_zone_width" id="WZ_ZONE_WIDTH" class="form-control" required="required" maxlength="10"  value="{{ $wz_zone_width }}" />
    </div>
</div>
<div class="col-md-2">
     <div class="form-group">
        <label class="control-label">Unit <span class="required"> * </span></label>
         <select class="bs-select form-control" name="wz_zone_width_unit" id="WZ_ZONE_WIDTH_UNIT" data-actions-box="true">
     		@if($warehouseInfo->w_warehouse_width_unit == 1)
            <option {{ $wz_zone_width_unit == 1 ? "selected" : "" }}  value="1">Meter</option>
            @elseif($warehouseInfo->w_warehouse_width_unit == 2)
            <option {{ $wz_zone_width_unit == 2 ? "selected" : "" }}   value="2">Kilometer</option>
            @elseif($warehouseInfo->w_warehouse_width_unit == 3)
            <option {{ $wz_zone_width_unit == 3 ? "selected" : "" }}   value="3">Inch</option>
            @endif
        </select>
    </div>
</div>
<div class="col-md-4">
     <div class="form-group">
        <label class="control-label">Zone Height <span class="required"> * </span></label>
        <input type="text" name="wz_zone_height" id="WZ_ZONE_HEIGHT" class="form-control" required="required" maxlength="10"  value="{{ $wz_zone_height }}" />
    </div>
</div>
<div class="col-md-2">
     <div class="form-group">
        <label class="control-label">Unit <span class="required"> * </span></label>
         <select class="bs-select form-control" name="wz_zone_height_unit" id="WZ_ZONE_HEIGHT_UNIT" data-actions-box="true">
     		@if($warehouseInfo->w_warehouse_height_unit == 1)
            <option {{ $wz_zone_height_unit == 1 ? "selected" : "" }} value="1">Meter</option>
            @elseif($warehouseInfo->w_warehouse_height_unit == 2)
            <option {{ $wz_zone_height_unit == 2 ? "selected" : "" }}  value="2">Kilometer</option>
            @elseif($warehouseInfo->w_warehouse_height_unit == 3)
            <option {{ $wz_zone_height_unit == 3 ? "selected" : "" }}  value="3">Inch</option>
             @endif
        </select>
    </div>
</div>