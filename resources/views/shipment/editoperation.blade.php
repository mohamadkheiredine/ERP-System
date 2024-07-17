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
                                    <select  name="so_operation_type" id="SO_OPERATION_TYPE"  class="form-control form-select" required="required" data-control="select2" data-placeholder="Select Operation Type">
                                        <option value="0">--Select One--</option>
                                        <option value="1" {{ $operation_shipment->so_operation_type == 1 ? "selected" : "" }} >Internal Operation</option>
                                        <option value="2" {{ $operation_shipment->so_operation_type == 2 ? "selected" : "" }}>External Operation</option>
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
                                        <option {{ $operation_shipment->so_country_source == $country_info->id ? "selected" : "" }} value="{{ $country_info->id  }}">{{ $country_info->name  }}</option> 
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
                                        <option {{ $operation_shipment->so_country_destination == $country_info->id ? "selected" : "" }} value="{{ $country_info->id  }}">{{ $country_info->name  }}</option> 
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
                                        <option {{ $operation_shipment->so_warehouse_source == $warehouse_info->w_id ? "selected" : "" }} value="{{ $warehouse_info->w_id  }}">{{ $warehouse_info->w_warehouse_name }}</option> 
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
                                        <option {{ $operation_shipment->so_warehouse_destination == $warehouse_info->w_id ? "selected" : "" }} value="{{ $warehouse_info->w_id  }}">{{ $warehouse_info->w_warehouse_name }}</option> 
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
                                                    <option {{ $operation_shipment->so_operation_status == $os_info->os_id ? "selected" : "" }} value="{{ $os_info->os_id }}">{{ $os_info->os_status_title }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                    <label> Operation Date : </label>
                                      <input type="text" name='so_operation_date' class="form-control" id="SO_OPERATION_DATE" value="{{ date('m/d/Y',strtotime($operation_shipment->so_operation_date)) }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                            	<label> Operation Time : </label>
                                 <div class='input-group timepicker' id='OPERATION_TIMEPICKER'>
									<input type='text' class="form-control" readonly placeholder="Select time" id="SO_OPERATION_TIME"  name="so_operation_time" value="{{ $operation_shipment->so_operation_time }}"/>
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="la la-clock-o"></i>
										</span>
									</div>
								</div>
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
                        <div class="col-md-12">
                            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#ShippingOrdertab">Shipping Orders</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="ShippingOrdertab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-row-dashed table-row-gray-300 gy-7">
                                                <thead>
                                                  <tr class="fw-bold fs-6 text-gray-800">
                                                                  <th title="#">#</th>
                                                                  <th title="Id"> ID </th>
                                                                  <th title="Order Code"> Order Code </th>
                                                                  <th title="Order Name"> Order Name </th>
                                                                  <th title="Order total"> Order total </th>
                                                                  <th title="delete"> Delete </th>
                                                          </tr>
                                                </thead>
                                                <tbody class="LstOrdersBody">
                                                </tbody>
                                           </table>
                                        </div>
                                    </div>
                                     <div class="row">
                                         <div class="col-md-12" align="right">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#orders_modal">
                                                   Add Orders
                                                </button> 
                                         </div>
                                     </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row" style="height:20px;"><div class="col-md-12">&nbsp;</div></div>
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
    <div class="modal fade" tabindex="-1" id="orders_modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Add New Order</h3>

                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">
                                            <form name="frm_add_orders" id="FRM_ADD_ORDERS">
                                                <span id="hidden_fields">
                                                    {!! csrf_field() !!}
                                                    <input type="hidden" name="so_id" id="SO_ID" value="{{ $operation_shipment->so_id }}" />
                                                </span>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label> Orders : </label>
                                                            <select  name="fk_oo_order_id" id="FK_OO_ORDER_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Order">
                                                                    <option value="">-- select one --</option>
                                                                    @foreach ( $lst_drpdown_orders as $key => $order_info )
                                                                            <option value="{{ $order_info->so_id }}">{{ $order_info->so_order_code }}&nbsp;-&nbsp;{{ $order_info->so_order_label }}</option>
                                                                    @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" align="right">&nbsp;</div>
                                                    <div class="col-md-12" align="right">
                                                        <button type="submit" name="btn_add_order" id="BTN_ADD_ORDER" class="btn btn-primary">Add</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="modal-footer"> 
                                        </div>
                                    </div>
                                </div>
                            </div>     

@endsection