<?php
/***********************************************************
addwarehouse.blade.php
Product :
Version : 1.0
Release : 2
Date Created :Sep 22, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
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
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/warehouses.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/inventory/savewarehosue.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Warehouse</h3>
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
			<form name="frm_save_warehouse" id="FORM_SAVE_WAREHOUSE">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Warehouse Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Warehouse Ref <span class="required"> * </span></label>
                                    <input type="text" name="w_warehouse_ref" id="W_WAREHOUSE_REF" class="form-control" required="required" maxlength="15"  value="{{ $warehouse_code }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Warehouse Name <span class="required"> * </span></label>
                                <input type="text" name="w_warehouse_name" id="W_WAREHOUSE_NAME" class="form-control" required="required" maxlength="100"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Warehouse zipcode </label>
                                <input type="text" maxlength="5" name="w_warehouse_zipcode" id="W_WAREHOUSE_ZIPCODE" class="form-control"   value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Warehouse Parent</label>
                                <select class="bs-select form-control" name="fk_w_id" id="FK_W_ID" data-actions-box="true">
                                        <option value="">No Parent</option>
                                        <?php foreach ( $lstWarehouses as $key => $warehouse_info ) { ?>
                                                <option value="<?php echo $warehouse_info->w_id;  ?>"><?php echo $warehouse_info->w_warehouse_name;  ?></option>
                                        <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Warehouse City </label>
                                <input type="text" name="w_warehouse_city" id="W_WAREHOUSE_CITY" class="form-control" maxlength="200"  value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> Warehouse Type</label>
                                <select class="bs-select form-control" name="w_warehouse_size_type" id="W_WAREHOUSE_SIZE_TYPE" required="required" data-actions-box="true">
                                        <option value="">--- Warehouse Type ---</option>
                                        <option value="1"> Volume </option>
                                        <option value="2"> Size </option> 
                                        <option value="3"> Liquid </option> 
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                            <br/>
                              <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" name="w_warehouse_status" type="checkbox" value="1" checked="checked" />
                                    <span class="form-check-label fw-semibold text-muted">
                                       Active
                                    </span>
                                </label>  
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group"> 
                        	   <br/>
                              <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="w_material_warehouse" id="W_MATERIAL_WAREHOUSE" value="1" checked="checked" />
                                    <span class="form-check-label fw-semibold text-muted">
                                       Raw Material Warehouse
                                    </span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Warehouse Description <span class="required"> * </span></label><br/>
                                <textarea style="width:100%;height:250px;resize:none"  class="form-control" name="w_warehouse_description" id="W_WAREHOUSE_DESCRIPTION" cols=""></textarea>
                             </div>
                        </div>
                        <div class="col-md-12">
                        	<label>Vehicules :</label>
                            <div class="row">
                            	<div class="col-md-1"></div>
                            	<div class="col-md-4">
                            		 <div class="form-group"> 
                           					 <select multiple="multiple" class="multi-select form-control" style="height:220px;" id="VEHICULES" name="vehicules[]">
                                                @foreach($lst_vehicules as $index => $veh_info)
                                                  <option value="{{ $veh_info->lv_id }}">{{ $veh_info->lv_vehicule_name }}</option>
                                                @endforeach
                                            </select>
                        			</div>
                            	</div>
                            	<div class="col-md-2">
                            		<button type="button" name="btn_add" id="BTN_ALLOW_VEHICULE" class="btn btn-info" > >> </button><br/>
                            		<div style="width:100%;height:20px">&nbsp;</div>
                            		<button type="button" name="btn_remove" id="BTN_REMOVE_VEHICULE" class="btn btn-info" > << </button>
                            	</div>
                            	<div class="col-md-4">
                            		<div class="form-group">
                           					 <select multiple="multiple" class="multi-select form-control"  style="height:220px;" id="ALLOWED_VEHICULES" name="allowed_vehicules[]">
                                            </select>
                        			</div>
                            	</div>
                            	<div class="col-md-1"></div>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_add_warehouse" id="BTN_ADD_WAREHOUSE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn btn-dark">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>

@endsection