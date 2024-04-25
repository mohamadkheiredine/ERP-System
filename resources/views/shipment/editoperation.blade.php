<?php
/***********************************************************
editoperation.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Shipment Operations > Edit Operation"])

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
        <h3 class="card-title">Edit Operation</h3>
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
                      <input type="hidden" name="so_id" id="SO_ID" value="{{ $operation_shipment->so_id }}" />
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
                                    <input type="text" maxlength="10" name="so_operation_reference" id="SO_OPERATION_REFERENCE" class="form-control" required="required"  value="{{ $operation_shipment->so_operation_reference }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Operation Label <span class="required"> * </span></label>
                                    <input type="text" maxlength="255" name="so_operation_label" id="SO_OPERATION_LABEL" class="form-control" required="required"  value="{{ $operation_shipment->so_operation_label }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label">Operation Type <span class="required"> * </span></label>
                                    <select class="bs-select form-control" name="so_operation_type" id="SO_OPERATION_TYPE" required="required" data-actions-box="true">
                                        <option value="0">--Select One--</option>
                                        <option value="1" {{ $operation_shipment->so_operation_type == 1 ? "selected" : "" }} >Internal Operation</option>
                                        <option value="2" {{ $operation_shipment->so_operation_type == 2 ? "selected" : "" }}>External Operation</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label> Status : </label>
                                    <select class="bs-select form-control" name="so_operation_status" id="SO_OPERATION_STATUS" data-actions-box="true">
                                            <option value="">-- select one --</option>
                                            @foreach ( $operation_status as $key => $os_info )
                                                    <option {{ $operation_shipment->so_operation_status == $os_info->os_id ? "selected" : "" }} value="{{ $os_info->os_id }}">{{ $os_info->os_status_title }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label> Operation Date : </label>
                                      <input type="text" name='so_operation_date' class="form-control" id="SO_OPERATION_DATE" value="{{ $operation_shipment->so_operation_date }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                            	<label> Operation Time : </label>
                                 <div class='input-group timepicker' id='OPERATION_TIMEPICKER'>
									<input type='text' class="form-control m-input" readonly placeholder="Select time"  name="so_operation_time" value="{{ $operation_shipment->so_operation_time }}"/>
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="la la-clock-o"></i>
										</span>
									</div>
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
                            	<textarea class="form-control" id="SO_OPERATION_DESCRIPTION" name="so_operation_description" style="width:100%;height:250px;resize:none" >{{ $operation_shipment->so_operation_description }}</textarea>
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