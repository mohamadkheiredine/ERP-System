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
                <label class="control-label">Warehouse Volume <span class="required"> * </span></label>
                <input type="text" name="w_warehouse_volume" id="W_WAREHOUSE_VOLUME" class="form-control" required="required" maxlength="10"  value="{{ $warehouse_info->w_warehouse_volume }}" />
            </div>
        </div>
		<div class="col-md-4">
             <div class="form-group">
                <label class="control-label">Unit <span class="required"> * </span></label>
                 <select class="bs-select form-control" name="w_warehouse_volume_unit" id="W_WAREHOUSE_VOLUME_UNIT" data-actions-box="true">
                        <option {{ $warehouse_info->w_warehouse_volume_unit == 1 ? "selected" : "" }} value="1">Meter Cube (m3) </option>
                        <option {{ $warehouse_info->w_warehouse_volume_unit == 2 ? "selected" : "" }} value="2">Litre</option> 
                        <option {{ $warehouse_info->w_warehouse_volume_unit == 3 ? "selected" : "" }} value="3">Ton</option>
                </select>
            </div>
        </div>
		<div class="col-md-12"> 
              <button type="button" name="btn_draw_image" id="BTN_DRAW_IMAGE" class="btn btn-info" > Draw Image </button> 
        </div>
</div>