<?php
/***********************************************************
addoperation.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 16, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Fleet Management"])

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
<script type="text/javascript" src="{{ url('js/modules/operations.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/shipment/saveoperations.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Operation</h3>
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
        <form name="frm_save_operation" id="FORM_SAVE_OPERATION">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong>Shipment Operation Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
            			<div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Operation Reference <span class="required"> * </span></label>
                                    <input type="text" maxlength="10" name="so_operation_reference" id="SO_OPERATION_REFERENCE" class="form-control" required="required"  value="{{ $operation_code }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Operation Label <span class="required"> * </span></label>
                                    <input type="text" maxlength="255" name="so_operation_label" id="SO_OPERATION_LABEL" class="form-control" required="required"  value="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Operation Type <span class="required"> * </span></label>
                                    <select  name="so_operation_type" id="SO_OPERATION_TYPE"  class="form-control form-select" required="required" data-control="select2" data-placeholder="Select Operation Type">
                                        <option value="0">--Select One--</option>
                                        <option value="1">Internal Operation</option>
                                        <option value="2">External Operation</option>
                                </select>
                                </div>
                            </div> 
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Operation Transportation Type <span class="required"> * </span></label>
                                    <select  name="so_transportation_mode" id="SO_TRANSPORTATION_MODE"  class="form-control form-select" required="required" data-control="select2" data-placeholder="Select Transportation Mode">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_modes as $index => $mode_info)
                                        <option value="{{ $mode_info->tm_id  }}">{{ $mode_info->tm_mode  }}</option> 
                                        @endforeach
                                </select>
                                </div>
                            </div>
                                     <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Country From <span class="required"> * </span></label>
                                    <select  name="so_country_source" id="SO_COUNTRY_SOURCE"  class="form-control form-select" required="required" data-control="select2" data-placeholder="Select Country Source">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_countries as $index => $country_info)
                                        <option value="{{ $country_info->id  }}">{{ $country_info->name  }}</option> 
                                        @endforeach
                                </select>
                                </div>
                            </div>
                                    
                                          <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Country Destination <span class="required"> * </span></label>
                                    <select  name="so_country_destination" id="SO_COUNTRY_DESTINATION"  class="form-control form-select" required="required" data-control="select2" data-placeholder="Select Country Destination">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_countries as $index => $country_info)
                                        <option value="{{ $country_info->id  }}">{{ $country_info->name  }}</option> 
                                        @endforeach
                                </select>
                                </div>
                            </div>
                                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Warehouse Source <span class="required"> * </span></label>
                                    <select  name="so_warehouse_source" id="SO_WAREHOUSE_SOURCE"  class="form-control form-select" required="required" data-control="select2" data-placeholder="Select Warehouse source">
                                        <option value="0">--Select One--</option>
                                        @foreach($lst_warehouses as $index => $warehouse_info)
                                        <option value="{{ $warehouse_info->w_id  }}">{{ $warehouse_info->w_warehouse_name }}</option> 
                                        @endforeach
                                </select>
                                </div>
                            </div>  
                             <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Warehouse Destination <span class="required"> * </span></label>
                                    <select  name="so_warehouse_destination" id="SO_WAREHOUSE_DESTINATION"  class="form-control form-select" required="required" data-control="select2" data-placeholder="Select Warehouse Destination">
                                         <option value="0">--Select One--</option>
                                        @foreach($lst_warehouses as $index => $warehouse_info)
                                        <option value="{{ $warehouse_info->w_id  }}">{{ $warehouse_info->w_warehouse_name }}</option> 
                                        @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label> Status : </label>
                                    <select  name="so_operation_status" id="SO_OPERATION_STATUS"  class="form-control form-select" data-control="select2" data-placeholder="Select Operation Status">
                                            <option value="">-- select one --</option>
                                            @foreach ( $operation_status as $key => $os_info )
                                                    <option value="{{ $os_info->os_id }}">{{ $os_info->os_status_title }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label> Operation Date : </label>
                                      <input type="text" name='so_operation_date' class="form-control" id="SO_OPERATION_DATE" />
                                </div>
                            </div>
                            <div class="col-md-4">
                            	<label> Operation Time : </label>
                                 <div class='input-group timepicker' id='OPERATION_TIMEPICKER'>
									<input type='text' name="so_operation_time" class="form-control" id="SO_OPERATION_TIME" value="" readonly placeholder="Select time" type="text"/>
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="la la-clock-o"></i>
										</span>
									</div>
								</div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label> Delivery Date : </label>
                                      <input type="text" name='so_delivery_date' class="form-control" id="SO_DELIVERY_DATE" value="" />
                                </div>
                            </div>
                        </div>
                   <div class="row" style="height:5px;"></div>
                   <div class="row">
                   		<div class="col-md-12" id="OPERATION_INFO">
                   		
                   		</div>
                   </div>
                   	<div class="row">
                   		<div class="col-md-12">
                            	<div class="form-group">
                            	<label> Operation Description : </label>
                            	<textarea class="form-control" id="SO_OPERATION_DESCRIPTION" name="so_operation_description" style="width:100%;height:250px;resize:none" ></textarea>
                                </div>
                            </div>  
                   	</div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_operation" id="BTN_SAVE_OPERATION"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>   
@endsection