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
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/warehouses.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/inventory/savewarehosue.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">Edit Warehouse</h3>
									</div>
								</div>
								<div class="m-portlet__head-tools">
									<ul class="m-portlet__nav">
										<li class="m-portlet__nav-item">
											<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
												<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
													<i class="la la-ellipsis-h m--font-brand"></i>
												</a>
												<div class="m-dropdown__wrapper">
													<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
													<div class="m-dropdown__inner">
														<div class="m-dropdown__body">
															<div class="m-dropdown__content">
																<ul class="m-nav">
																	<li class="m-nav__section m-nav__section--first">
																		<span class="m-nav__section-text">
																			Quick Actions
																		</span>
																	</li>
																</ul>
															</div>
														</div>
													</div>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<div class="m-portlet__body">
                                     <form name="frm_save_warehouse" id="FORM_SAVE_WAREHOUSE">
                                        <div class="form-body">
                                             <span id="hidden_fields">
                                              <div class="form-group">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="w_id" value="{{ $wareHouseInfo->w_id }}" />
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
                                                            <input type="text" name="w_warehouse_ref" id="W_WAREHOUSE_REF" class="form-control" required="required" maxlength="15"  value="{{ $wareHouseInfo->w_warehouse_ref }}" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Warehouse Name <span class="required"> * </span></label>
                                                        <input type="text" name="w_warehouse_name" id="W_WAREHOUSE_NAME" class="form-control" required="required" maxlength="100"  value="{{ $wareHouseInfo->w_warehouse_name }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Warehouse zipcode </label>
                                                        <input type="text" maxlength="5" name="w_warehouse_zipcode" id="W_WAREHOUSE_ZIPCODE" class="form-control"   value="{{ $wareHouseInfo->w_warehouse_zipcode }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Warehouse Parent</label>
                                                        <select class="bs-select form-control" name="fk_w_id" id="FK_W_ID" data-actions-box="true">
                                                                <option value="">No Parent</option>
                                                                <?php foreach ( $lstWarehouses as $key => $warehouse_info ) { ?>
                                                                        <option  {{ $wareHouseInfo->fk_w_id == 1 ? "selected" : "" }} value="<?php echo $warehouse_info->w_id;  ?>"><?php echo $warehouse_info->w_warehouse_name;  ?></option>
                                                                <?php  } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Warehouse City </label>
                                                        <input type="text" name="w_warehouse_city" id="W_WAREHOUSE_CITY" class="form-control" maxlength="200"  value="{{ $wareHouseInfo->w_warehouse_city }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Warehouse Type</label>
                                                        <select class="bs-select form-control" name="w_warehouse_size_type" id="W_WAREHOUSE_SIZE_TYPE" required="required"  data-actions-box="true">
                                                                <option value="">--- Warehouse Type ---</option>
                                                                <option {{ $wareHouseInfo->w_warehouse_size_type == 1 ? "selected" : "" }} value="1"> Volume </option>
                                                                <option {{ $wareHouseInfo->w_warehouse_size_type == 2 ? "selected" : "" }} value="2"> Size </option> 
                                                                <option {{ $wareHouseInfo->w_warehouse_size_type == 3 ? "selected" : "" }}  value="3"> Liquid </option> 
                                                        </select>
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Active </label><br/>
                                                       <span class="m-switch">
    														<label>
    															<input name="w_warehouse_status" {{ $wareHouseInfo->w_warehouse_status == 1 ? "checked" : "" }}  type="checkbox" value="1" />
    															<span></span>
    														</label>
    													</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Raw Material Warehouse </label><br/>
                                                       <input data-switch="true" type="checkbox" {{ $wareHouseInfo->w_material_warehouse == 1 ? " checked='checked'" : "" }} name="w_material_warehouse" value="1" id="W_MATERIAL_WAREHOUSE" data-on-text="Yes" data-handle-width="50" data-off-text="No" data-on-color="success" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                     <div class="form-group">
                                                        <label class="control-label"> Warehouse Description <span class="required"> * </span></label><br/>
                                                        <textarea style="width:100%;height:250px;resize:none"  class="form-control" name="w_warehouse_description" id="W_WAREHOUSE_DESCRIPTION"   cols="">{{ $wareHouseInfo->w_warehouse_description }}</textarea>
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
                                                   					  @foreach($lst_allowed_vehicules as $index => $veh_info)
                                                                          <option value="{{ $veh_info->lv_id }}">{{ $veh_info->lv_vehicule_name }}</option>
                                                                        @endforeach
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
                                                    <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
							</div>
					   </div>

@endsection