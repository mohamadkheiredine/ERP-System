<?php
/***********************************************************
editbom.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 31, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Bill of Materials Management"])

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
<script type="text/javascript" src="{{ url('js/modules/bom.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/mrp/savebom.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Existing BOM</h3>
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
    <form name="frm_save_bom" id="FORM_SAVE_BOM">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                       <input type="hidden" name="bm_id" value="{{ $bom_info->bm_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Bill of Materials Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> BOM Code <span class="required"> * </span></label>
                                    <input type="text" name="bm_code" id="BM_CODE" class="form-control" required="required" maxlength="50" readonly="readonly"  value="{{ $bom_info->bm_code }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> BOM Label <span class="required"> * </span></label>
                                    <input type="text" name="bm_label" id="BM_LABEL" class="form-control" required="required" maxlength="255"  value="{{ $bom_info->bm_label }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Product</label><br/>
                                 <select class="bs-select form-control" name="bm_product_id" id="BM_PRODUCT_ID" data-actions-box="true">
                                        <option value=""> -- Products --</option>
                                        @foreach ( $lst_products as $key => $product_info )
                                                <option {{ $bom_info->bm_product_id == $product_info->p_id ? "selected" : "" }} value="{{ $product_info->p_id }}">{{ $product_info->p_product_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Product Barecode </label><br/>
                                <span style="font-weight: bold;" class="BareCode"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Product Label </label><br/>
                                <span  style="font-weight: bold;" class="ProductLabel"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Product Fixed Cost </label><br/>
                                <input type="number" name="bm_fixed_cost" id="BM_FIXED_COST" class="form-control" min="0.1" max="9999999999" step="0.1" required="required" maxlength="255"  value="{{ $bom_info->bm_fixed_cost }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Final Quantity </label><br/>
                                <input type="number" name="bm_final_quantity" id="BM_FINAL_QUANTITY" class="form-control" min="0.1" max="9999999999" step="0.1" required="required" maxlength="255"  value="{{ $bom_info->bm_final_quantity }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Expected Percentage Waste </label><br/>
                                <input type="number" name="bm_expected_waste_percentage" id="BM_EXPECTED_WASTE_PERCENTAGE" class="form-control" min="0.1" max="100" step="0.1" required="required" maxlength="5"  value="{{ $bom_info->bm_expected_waste_percentage }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Manufacturing Efficiency </label><br/>
                                <input type="number" name="bm_manufacturing_efficiency" id="BM_MANUFACTURING_EFFICIENCY" class="form-control" min="0.1" max="100" step="0.1" required="required" maxlength="5"  value="{{ $bom_info->bm_manufacturing_efficiency }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Product Variable Cost </label><br/>
                                <input type="number" name="bm_variable_cost" id="BM_VARIABLE_COST" class="form-control" min="0.1" max="9999999999" step="0.1" required="required" maxlength="255"  value="{{ $bom_info->bm_variable_cost }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Unit</label><br/>
                                <select class="form-select" name="bm_unit_id" id="BM_UNIT_ID"  data-control="select2" data-placeholder="Select a Unit"  tabindex="4">
                                    <option value=""> -- Unit --</option>
                                    @foreach ( $lst_sys_units as $key => $unit_info )
                                        <option {{ $bom_info->bm_unit_id ==  $unit_info->su_id ? "selected" : "" }} value="{{ $unit_info->su_id }}">{{ $unit_info->su_unit_code }}&nbsp;-&nbsp;{{ $unit_info->su_unit_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Company</label><br/>
                                <select class="form-select" name="bm_company_id" id="BM_COMPANY" >
                                    <option value="0"> -- Company --</option>
                                    @foreach ( $lst_companies as $key => $company )
                                        <option  {{ $bom_info->bm_company_id ==  $company->cs_id ? "selected" : "" }}  value="{{ $company->cs_id }}">{{ $company->cd_company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Warehouse</label><br/>
                                <div class="col-md-12 WarehouseDropdown">
                                    <select class="form-select" name="bm_target_warehouse" id="BM_TARGET_WAREHOUSE"  data-control="select2" data-placeholder="Select a Warehouse"  tabindex="4">
                                        <option value=""> -- Warehouse --</option>
                                        @foreach ( $lst_warehouses as $key => $warehouse_info )
                                            <option {{ $bom_info->bm_target_warehouse ==  $warehouse_info->w_id ? "selected" : "" }} value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Currency</label><br/>
                                 <select class="bs-select form-control" name="bm_currency_id" id="BM_CURRENCY_ID" data-actions-box="true">
                                        <option value=""> -- Currency --</option>
                                        @foreach ( $lst_currency as $key => $curr_info )
                                                <option {{ $bom_info->bm_currency_id ==  $curr_info->cc_id ? "selected" : "" }} value="{{ $curr_info->cc_id }}">{{ $curr_info->cc_currency_code }}&nbsp;-&nbsp;{{ $curr_info->cc_currency_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">BOM Type</label><br/>
                                 <select class="bs-select form-control" name="bm_bom_type" id="BM_BOM_TYPE" data-actions-box="true">
                                        <option value="">BOM Type</option>
                                        <option {{ $bom_info->bm_bom_type == 1 ? "selected" : "" }} value="1">EBOM - Engineering</option>
                                        <option {{ $bom_info->bm_bom_type == 2 ? "selected" : "" }} value="2">MBOM - Manufacturing</option>
                                        <option {{ $bom_info->bm_bom_type == 3 ? "selected" : "" }} value="3">SBOM - Sales</option>
                                </select>
                            </div>
                        </div>
                         <div class="col-md-12">
                         	<ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_notes">Notes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_products">Products</a>
                                </li>
                            </ul>
                         	<div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tab_notes" role="tabpanel">
                                   	<div class="form-group">
                                        <label class="control-label">BOM  Note</label>
        								<textarea name="bm_bom_notes" id="BM_BOM_NOTES" class="form-control" style="width:100%;height:250px;">{{ $bom_info->bm_bom_notes }}</textarea>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab_products" role="tabpanel">
                                   <div class="row">
										<div id="LstBOMProducts" class="col-md-12">
											<table class="table m-table m-table--head-bg-brand">
											<thead>
												<tr>
													<th> # </th>
													<th>Product Label</th>
													<th>Quantity</th>
													<th>Price </th>
													<th> </th>
												</tr>
											</thead>
											<tbody id="TableBOMProducts">
											</tbody>
										</table>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12" align="right">
											<button type="button" name="btn_add_product" id="BTN_ADD_PRODUCT" class="btn btn-primary" >Add Product</button>
										</div>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_bom" id="BTN_SAVE_BOM"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
<div class="modal fade" id="BOMItemsModel" tabindex="-1" role="dialog" aria-labelledby="BOMItemsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="BOMItemsModalLabel">BOM Items</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<form name="frm_bom_item" id="FRM_BOM_ITEMS">
      		<div class="row">
          		<div class="col-md-12">
          			<div class="form-group">
          				<label> Product </label><br/>
          				<select name="bm_item_product" id="BM_ITEM_PRODUCT" style="width:100%;" class="form-control">
          				    <option value="">-- Select product --</option>
          					@foreach($lst_raw_materials as $index => $rm_info)
          						<option value="{{ $rm_info->p_id }}" data-weight="{{ $rm_info->p_product_weight }}" data-unit="{{ $rm_info->p_product_weight_unit }}" >{{ $rm_info->p_product_name }}</option>
          					@endforeach
          				</select>
          			</div>
          		</div>
          		<div class="col-md-12">
          			<div class="form-group">
          				<label> Quantity types </label><br/>
          				<select name="bm_quantity_type" id="BM_QUANTITY_TYPE" style="width:100%;" class="form-control">
          					<option value="">-- Select type --</option>
          					<option value="qty">Quantity</option>
          					<option value="weight">Weight</option>
          					<option value="volume">Volume</option>
          				</select>
          			</div>
          		</div>
          		<div class="col-md-12">
          			<div class="form-group">
          				<label> Quanity </label><br/>
          				<input type="number" class="form-control" name="bm_item_quanity" id="BM_ITEM_QUANTITY" required="required" min="0.0000" max="999999999999.0000" />
          			</div>
          		</div>
          		<div class="col-md-12">
          			<div class="form-group">
          				<label> Unit </label><br/>
          				<div style="width:100%" class="UnitsDropDown"></div>
          			</div>
          		</div>
          		<div class="col-md-12">
          			<div class="form-group">
          				<label> Products Price </label><br/>
          				<input type="text" name="bm_item_price" id="BM_ITEM_PRICE" class="form-control" required="required" maxlength="255" readonly="readonly"  value="0" />
          				<input type="hidden" name="ini_item_price" value="" />
          			</div>
          		</div>
          		<div class="col-md-12" align="right">
      	  			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        			<button type="submit" name="btn_insert_item" id="BTN_INSERT_ITEM" class="btn btn-primary">Save Item</button>
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
