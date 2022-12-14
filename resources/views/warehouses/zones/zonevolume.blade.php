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
    $wz_zone_volume = isset( $warehouseZoneInfo ) ? $warehouseZoneInfo->wz_zone_volume: "";
    $wz_zone_volume_unit = isset( $warehouseZoneInfo ) ? $warehouseZoneInfo->wz_zone_volume_unit : 0; 
}
?>

<div class="col-md-4">
     <div class="form-group">
        <label class="control-label">Zone Volume <span class="required"> * </span></label>
        <input type="text" name="wz_zone_volume" id="WZ_ZONE_VOLUME" class="form-control" required="required" maxlength="100"  value="{{ $wz_zone_volume }}" />
    </div>
</div>
<div class="col-md-2">
     <div class="form-group">
        <label class="control-label">Unit <span class="required"> * </span></label>
         <select class="bs-select form-control" name="wz_zone_volume_unit" id="W_ZONE_VOLUME_UNIT" required="required" data-actions-box="true">
         	@if($warehouseInfo->w_warehouse_volume_unit == 1)
			<option {{ $wz_zone_volume_unit == 1 ? "selected" : "" }}  value="1">Meter Cube (m3) </option>
			@elseif($warehouseInfo->w_warehouse_volume_unit == 2)
            <option {{ $wz_zone_volume_unit == 2 ? "selected" : "" }} value="2">Litre</option> 
            @elseif($warehouseInfo->w_warehouse_volume_unit == 3)
            <option {{ $wz_zone_volume_unit == 3 ? "selected" : "" }} value="3">Ton</option>
            @endif
        </select>
    </div>
</div>