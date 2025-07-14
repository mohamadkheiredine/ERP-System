<?php
/***********************************************************
editquotation.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 31, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Supplier Relation Management"])

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
<script src="{{ url('default/assets/plugins/bootstrap-suggest/src/bootstrap-suggest.js') }}"></script>
<script type="text/javascript" src="{{ url('default/assets/plugins/jquery-scanner-detection/jquery.scannerdetection.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/quotations.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/srm/savequotation.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Pruchase Quotation</h3>
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
    <form name="frm_save_quotation" id="FORM_SAVE_QUOTATION">
                <div class="form-body">
                     <span id="hidden_fields">
                     {!! csrf_field() !!}
                     <input type="hidden" name="sq_id" id="SQ_ID" value="{{ $supplier_quotation->sq_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Supplier Quotation Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4" style="display:none">

                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Supplier  <span class="required"> * </span></label>
                                     <select class="bs-select form-control" name="fk_supplier_id" id="FK_SUPPLIER_ID" required="required" data-actions-box="true">
                                        <option value=""> Select Supplier </option>
                                        @foreach ( $lst_suppliers as $key => $supp_info )
                                                <option {{ $supplier_quotation->fk_supplier_id == $supp_info->ss_id ? "selected" : "" }} value="{{ $supp_info->ss_id }}">{{ $supp_info->ss_supplier_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> User Applied  <span class="required"> * </span></label>
                                     <select class="bs-select form-control" name="sq_user_id" id="SQ_USER_ID" required="required" data-actions-box="true">
                                        <option value=""> Select User </option>
                                        @foreach ( $lst_users as $key => $user_info )
                                                <option {{ $supplier_quotation->sq_user_id == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Due Date <span class="required"> * </span></label>
                                <input type="text" name="sq_due_date" id="SQ_DUE_DATE" class="form-control" required="required" maxlength="11" readonly="readonly"  value="{{ date('d/m/Y',strtotime($supplier_quotation->sq_due_date)) }}" />
                            </div>
                        </div>
                            <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Container Number</label>
                                <input type="text" name="sq_container_number" id="SQ_CONTAINER_NUMBER" class="form-control"  maxlength="255"  value="{{ $supplier_quotation->sq_container_number }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Currency  <span class="required"> * </span></label><br/>
                                     <select  name="sq_currency_id" id="SQ_CURRENCY_ID"  class="form-control form-select" required="required" data-control="select2" data-placeholder="Select Currency">
                                        <option value=""> Select Currency </option>
                                        @foreach ( $lst_currency as $key => $currency_info )
                                                <option {{ $supplier_quotation->sq_currency_id == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Warehouse  <span class="required"> * </span></label>
                                     <select  name="sq_warehouse_id" id="SQ_WAREHOUSE_ID" class="form-control form-select" required="required" data-control="select2" data-placeholder="Select Warehouse">
                                        <option value=""> Select Warehouse </option>
                                        @foreach ( $lst_warehouses as $key => $warehouse_info )
                                                <option {{ $supplier_quotation->sq_warehouse_id == $warehouse_info->w_id ? "selected" : "" }} value="{{ $warehouse_info->w_id }}" >{{ $warehouse_info->w_warehouse_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Shipping Type  <span class="required"> * </span></label>
                                <select  name="sq_shipping_type" id="SQ_SHIPPING_TYPE" class="form-control form-select" required="required" data-control="select2" data-placeholder="Select Shipping Type">
                                    <option value=""> Select Shipping Type </option>
                                    <option {{ $supplier_quotation->sq_shipping_type == 1 ? "selected" : "" }} value="1"> CIF </option>
                                    <option {{ $supplier_quotation->sq_shipping_type == 2 ? "selected" : "" }}  value="2"> FOB </option>
                                    <option {{ $supplier_quotation->sq_shipping_type == 3 ? "selected" : "" }}  value="3"> Local Supplier </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">&nbsp;</div>
                        <div class="col-md-4">
                            <br/>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input"  type="checkbox" {{ $supplier_quotation->sq_enable_tva == 1 ? "checked" : ""  }} id="SQ_ENABLE_TVA" name="sq_enable_tva"  value="1" />
                                <span class="form-check-label fw-semibold text-muted">
                                   Enable TVA
                                </span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Tax  <span class="required"> * </span></label>
                                <select  name="sq_tva_id" id="SQ_TVA_ID" class="form-control form-select" required="required" data-control="select2" data-placeholder="Select Tax">
                                    <option value=""> Select TVA </option>
                                    @foreach ( $lst_vat as $key => $vat_info )
                                        <option {{ $supplier_quotation->sq_tva_id == $vat_info->av_id ? "selected" : "" }} value="{{ $vat_info->av_id }}" >{{ $vat_info->av_vat_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4">
                            <br/>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" {{ $supplier_quotation->sq_enable_freight == 1 ? "checked" : ""  }}  type="checkbox" id="SQ_ENABLE_FREIGHT" name="sq_enable_freight"  value="1" />
                                <span class="form-check-label fw-semibold text-muted">
                                   Enable Freight
                                </span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Freight Amount</label>
                                <input type="text" name="sq_freight_amount" id="SQ_FREIGHT_AMOUNT"  class="form-control"  maxlength="10"  value="{{ $supplier_quotation->sq_freight_amount  }}" />
                            </div>
                        </div>
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4">
                            <br/>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input"  type="checkbox" id="SQ_ENABLE_FORWARDING" {{ $supplier_quotation->sq_enable_forwarding == 1 ? "checked" : ""  }} name="sq_enable_forwarding"  value="1" />
                                <span class="form-check-label fw-semibold text-muted">
                                   Enable Customs
                                </span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Customs Amount</label>
                                <input type="text" name="sq_forwarding_amount" id="SQ_FORWARDING_AMOUNT" class="form-control"  maxlength="10"  value="{{ $supplier_quotation->sq_forwarding_amount  }}" />
                            </div>
                        </div>
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4">
                            <br/>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input"  type="checkbox" id="SQ_ENABLE_INSURANCE" {{ $supplier_quotation->sq_enable_insurance == 1 ? "checked" : ""  }} name="sq_enable_insurance"  value="1" />
                                <span class="form-check-label fw-semibold text-muted">
                                   Enable Insurance
                                </span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Insurance Amount</label>
                                <input type="text" name="sq_insurance_amount" id="SQ_INSURANCE_AMOUNT" class="form-control"  maxlength="10"  value="{{ $supplier_quotation->sq_insurance_amount   }}" />
                            </div>
                        </div>
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4">
                            <br/>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input"  type="checkbox" id="SQ_ENABLE_BROKER" name="sq_enable_broker" {{ $supplier_quotation->sq_enable_broker == 1 ? "checked" : ""   }}  value="1" />
                                <span class="form-check-label fw-semibold text-muted">
                                   Enable Clearing Expenses
                                </span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Clearing Expenses</label>
                                <input type="text" name="sq_broker_amount" id="SQ_BROKER_AMOUNT" class="form-control"  maxlength="10"  value="{{ $supplier_quotation->sq_broker_amount }}" />
                            </div>
                        </div>
                        <div class="col-md-4">&nbsp;</div>
                        @if( $supplier_quotation->sq_quotation_approve == 0 )
                         <div class="col-md-4"><br/>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input"  type="checkbox" id="SQ_APPROVE_QUOTATION" name="sq_approve_quotation" {{ $supplier_quotation->sq_quotation_approve == 1 ? 'checked="checked"' : "" }}  value="1" />
                                <span class="form-check-label fw-semibold text-muted">
                                   Quotation Approved
                                </span>
                            </label>
                        </div>
                        @else
                        	<input type="hidden" name="sq_approve_quotation" value="1" />
                        @endif
                        <div class="col-md-12">&nbsp;</div>
                        <div class="col-md-4">
                        	<button type="button" name="btnAddProduct" id="btnAddProduct" class="btn btn-info"  data-bs-toggle="modal" data-bs-target="#AddNewProduct">Add Product</button>
                        </div>

                         <div class="col-md-12">
                         		<ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#m_tab_notes">Notes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#m_tab_products">Products</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                            	 <div class="tab-pane fade show active" id="m_tab_notes" role="tabpanel">
                            	  <label class="control-label"> Quotation Notes <span class="required"> * </span></label><br/>
                                    <textarea style="width:100%;height:250px;resize:none" id="SQ_QUOTATION_NOTES"  class="form-control" name="sq_quotation_notes"  cols="">{{ $supplier_quotation->sq_quotation_notes }}</textarea>
                            	 </div>
                            	 <div class="tab-pane fade" id="m_tab_products" role="tabpanel">
                            	 <table class="table m-table m-table--head-separator-primary">
										<thead>
											<tr>
												<th>#</th>
												<th>Product Code</th>
												<th>Product Name</th>
												<th>Description</th>
												<th>Pruchase Price</th>
												<th>Discount</th>
												<th>Selling Price</th>
												<th>Wholesale Price</th>
												<th>Vendor Price</th>
												<th class="TitleQuantity">Quantity</th>
												<th class="TitleSerials">Serials</th>
												<th>Delete</th>
											</tr>
										</thead>
										<tbody id="ListProducts">
											@foreach($quotation_products as $index => $qp_info )
											<tr data-index="{{ $index + 1 }}" class="QuotationItems">
												<th>
												{{ $index + 1 }}
												<input type="hidden" name="sp_id[]" class="QProductId" value="{{ $qp_info->sp_id }}" />
												<input type="hidden" name="product_id[]" class="ProductId" value="{{ $qp_info->products->p_id }}" />
												<input type="hidden" name="currency_id[]" class="CurrencyId" value="{{ $qp_info->sp_product_currency }}" />
												<input type="hidden" name="serial_numbers[]" class="SerialNumbers" value="{{ $qp_info->sp_product_serial }}" />
											</th>
												<td><input type="text" name="pr_product_code[]" class="form-control ProductCode" value="{{ $qp_info->products->p_barcode }}" /></td>
												<td>
													<input type="text" name="pr_product_name[]" id="" class="form-control ProductNameField" value="{{ $qp_info->products->p_product_name }}" />
        												 <div class="input-group-btn">
                                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    </ul>
                                                    </div>
												</td>
												<td><input type="text" name="pr_description[]" class="form-control ProductDescription" value="{{ $qp_info->sp_product_description }}" /></td>
												<td><input type="text" name="pr_pruchase_price[]" class="form-control PurchasePrice" value="{{ $qp_info->sp_product_pruchase_price }}" /></td>
												<td><input type="text" name="pr_discount[]" class="form-control ProductDiscount" value="{{ $qp_info->sp_product_discount }}" /></td>
												<td><input type="text" name="pr_selling_price[]" class="form-control SellingPrice" value="{{ $qp_info->sp_product_selling_price }}" /></td>
												<td><input type="text" name="pr_wholesale_price[]" class="form-control WholeSalePrice" value="{{ $qp_info->sp_product_wholesale_price }}" /></td>
												<td><input type="text" name="pr_vendor_price[]" class="form-control VendorPrice" value="{{ $qp_info->sp_product_vendor_price }}" /></td>
												<td><input type="text" name="pr_quantity[]" class="form-control StockQuantity" value="{{ $qp_info->sp_product_quantity }}" /></td>
												<td><button class="btn ListSerialNumbers" style="{{ $qp_info->products->Category->pc_use_serial_number == 0 ? 'display:none' :'' }}" type="button" data-ids="{{ $qp_info->sp_product_serial }}" name="btn_list_serials[]" >...</button></td>
												<td><a href="#" class="DeleteCode"><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
											</tr>
											@endforeach
											<tr data-index="{{ count($quotation_products) + 1 }}" class="QuotationItems">
												<th>
												#
												<input type="hidden" name="serial_numbers[]" class="SerialNumbers" value="" />
												<input type="hidden" name="product_id[]" class="ProductId" value="" />
												<input type="hidden" name="currency_id[]" class="CurrencyId" value="" />
											</th>
												<td><input type="text" name="pr_product_code[]" class="form-control ProductCode" value="" /></td>
												<td>
													<input type="text" name="pr_product_name[]" id="" class="form-control ProductNameField" value="" />
        												 <div class="input-group-btn">
                                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    </ul>
                                                    </div>
												</td>
												<td><input type="text" name="pr_description[]" class="form-control ProductDescription" value="" /></td>
												<td><input type="text" name="pr_pruchase_price[]" class="form-control PurchasePrice" value="0" /></td>
												<td><input type="text" name="pr_discount[]" class="form-control ProductDiscount" value="0" /></td>
												<td><input type="text" name="pr_selling_price[]" class="form-control SellingPrice" value="0" /></td>
												<td><input type="text" name="pr_wholesale_price[]" class="form-control WholeSalePrice" value="0" /></td>
												<td><input type="text" name="pr_vendor_price[]" class="form-control VendorPrice" value="0" /></td>
												<td>
													<input type="text" name="pr_quantity[]" class="form-control StockQuantity" value="0" />
												</td>
												<td>
													<button class="btn ListSerialNumbers" type="button" data-ids="" name="btn_list_serials[]" >...</button>
												</td>
												<td><a href="#" class="DeleteCode" style="display: none"><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
											</tr>
										</tbody>
									</table>
									<div class="row">
										<div class="col-md-12" align="right">
											<button name="add_product" type="button" id="ADD_PRODUCT" class="btn btn-info" >Add Stock</button>
										</div>
									</div>
                            	 </div>
                            </div>

                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-7"></div>
                        <div class="col-md-5" align="right">
                        	@if( $supplier_quotation->sq_quotation_approve == 0 )
                             <button type="button" name="btn_approve_quotation" id="BTN_APPROVE_QUOTATION" class="btn btn-focus">Approve Quotation</button>
                             @endif
                             <button type="submit" name="btn_save_quotation" id="BTN_SAVE_QUOTATION"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
<div class="modal fade" id="AddNewProduct" tabindex="-1" role="dialog" aria-labelledby="AddNewProductModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InserItemsModalLabel">
					Add New Product
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_save_quotation" id="FRM_SAVE_QUOTATION">
					<div class="row">
						<div class="col-md-6">
							<label class="control-label"> Product Barcode  <span class="required"> * </span></label><br/>
							<input type="text" class="form-control" name="p_barcode" id="P_BARCODE" value="" />
						</div>
						<div class="col-md-4">
							<label class="control-label"> Product Name  <span class="required"> * </span></label><br/>
							<input type="text" class="form-control" name="p_product_name" id="P_PRODUCT_NAME" value="" />
						</div>
						<div class="col-md-4"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
