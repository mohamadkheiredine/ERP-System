<?php
/***********************************************************
external-operation.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 16, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
External Operation fields
***********************************************************/

?>
<div class="row">
    <div class="col-md-4">
      <div class="form-group">
            <label> Warehouse Source : </label>
            <select class="bs-select form-control" name="so_warehouse_source" id="SO_WAREHOUSE_SOURCE" data-actions-box="true">
                    <option value="">-- select one --</option>
                    @foreach ( $lst_warehouses as $key => $war_info )
                            <option value="{{ $war_info->w_id }}">{{ $war_info->w_warehouse_name }}</option>
                    @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
            <label> Vehicules : </label>
            <span class="VehiculeDropdown">
            	<select class="bs-select form-control" name="so_operation_vehicule" id="SO_OPERATION_VEHICULE" multiple="multiple" data-actions-box="true">
                </select>
            </span>
        </div>
    </div>
    <div id="ListProducts" class="col-md-12">
     
     </div>
     <div  class="col-md-12" style="height:20px;"></div>
 </div>