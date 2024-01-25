<?php
/***********************************************************
addbom.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 30, 2019
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
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/bom.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/mrp/savebom.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New BOM</h3>
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
                       <input type="hidden" name="lst_items" value="" />  
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
                                    <input type="text" name="bm_code" id="BM_CODE" class="form-control" required="required" maxlength="50" readonly="readonly"  value="{{ $bom_code }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> BOM Label <span class="required"> * </span></label>
                                    <input type="text" name="bm_label" id="BM_LABEL" class="form-control" required="required" maxlength="255"  value="" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Product</label><br/>
                                 <select class="bs-select form-control" name="bm_product_id" id="BM_PRODUCT_ID" data-actions-box="true">
                                        <option value=""> -- Products --</option>
                                        @foreach ( $lst_products as $key => $product_info )
                                                <option value="{{ $product_info->p_id }}">{{ $product_info->p_product_name }}</option>
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
                                <input type="number" name="bm_fixed_cost" id="BM_FIXED_COST" class="form-control" min="0.1" max="9999999999" step="0.1" required="required" maxlength="255"  value="0" />
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Product Variable Cost </label><br/>
                                <input type="number" name="bm_variable_cost" id="BM_VARIABLE_COST" class="form-control" min="0.1" max="9999999999" step="0.1" required="required" maxlength="255"  value="0" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Currency</label><br/>
                                 <select class="bs-select form-control" name="bm_currency_id" id="BM_CURRENCY_ID" data-actions-box="true">
                                        <option value=""> -- Currency --</option>
                                        @foreach ( $lst_currency as $key => $curr_info )
                                                <option value="{{ $curr_info->cc_id }}">{{ $curr_info->cc_currency_code }}&nbsp;-&nbsp;{{ $curr_info->cc_currency_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">BOM Type</label><br/>
                                 <select class="bs-select form-control" name="bm_bom_type" id="BM_BOM_TYPE" data-actions-box="true">
                                        <option value="">BOM Type</option> 
                                        <option value="1">EBOM - Engineering</option> 
                                        <option value="2">MBOM - Manufacturing</option> 
                                        <option value="3">SBOM - Sales</option> 
                                </select>
                            </div>
                        </div>
                         <div class="col-md-12">
             				<ul class="nav nav-tabs  m-tabs-line" role="tablist">
								<li class="nav-item m-tabs__item">
									<a class="nav-link m-tabs__link active" data-toggle="tab" href="#mNotes" role="tab">
										Notes
									</a>
								</li> 
								<li class="nav-item m-tabs__item" style="display:none">
									<a class="nav-link m-tabs__link" data-toggle="tab" href="#mProducts" role="tab">
										Products
									</a>
								</li> 
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="mNotes" role="tabpanel">
									<div class="form-group">
                                        <label class="control-label">BOM  Note</label>
        								<textarea name="bm_bom_notes" id="BM_BOM_NOTES" class="form-control" style="width:100%;height:250px;"></textarea>
                                    </div>
								</div> 
								<div class="tab-pane" id="mProducts" role="tabpanel">
									<div class="row">
										<div id="LstBOMProducts" class="col-md-12">
											<table class="table m-table m-table--head-bg-brand">
											<thead>
												<tr>
													<th> # </th>
													<th>Product Label</th>
													<th>Quantity</th>
													<th>Price </th>
												</tr>
											</thead>
											<tbody id="TableBOMProducts">
												<!-- <tr>
													<th scope="row">1</th>
													<td>Product Label</td>
													<td>Quantity</td>
													<td>Price</td>
												</tr> --> 
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
@endsection