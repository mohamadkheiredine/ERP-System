<?php
/***********************************************************
addquotation.blade.php
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
        <h3 class="card-title">Add New Quotation</h3>
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
                     <span id="hidden_fields" style="display:none">
                     {!! csrf_field() !!} 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Supplier Quotation Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4" style="display:none">
                              <div class="form-group">
                                    <label class="control-label"> Bidding  <span class="required"> * </span></label>
                                     <select class="bs-select form-control" name="fk_bid_id" id="FK_BID_ID" data-actions-box="true">
                                       <option value=""> Select Bidding </option>
                                        @foreach ( $lst_supplier_bidding as $key => $bidding_info )
                                                <option value="{{ $bidding_info->sb_id }}">{{ $bidding_info->sb_bid_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Supplier  <span class="required"> * </span></label>
                                     <select class="bs-select form-control" name="fk_supplier_id" id="FK_SUPPLIER_ID" required="required" data-actions-box="true">
                                        <option value=""> Select Supplier </option>
                                        @foreach ( $lst_suppliers as $key => $supp_info )
                                                <option value="{{ $supp_info->ss_id }}">{{ $supp_info->ss_supplier_name }}</option>
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
                                                <option  <?php echo ( $user_info->id == session('user_id') ? "selected" : ""); ?> value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Due Date <span class="required"> * </span></label>
                                <input type="text" name="sq_due_date" id="SQ_DUE_DATE" class="form-control" required="required" maxlength="11" readonly="readonly"  value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Currency  <span class="required"> * </span></label><br/>
                                     <select class="bs-select form-control" name="sq_currency_id" id="SQ_CURRENCY_ID" required="required" data-actions-box="true">
                                        <option value=""> Select Currency </option>
                                        @foreach ( $lst_currency as $key => $currency_info )
                                                <option value="{{ $currency_info->cc_id }}" {{ ($company_currency == $currency_info->cc_id) ? "selected" : "" }} >{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Warehouse  <span class="required"> * </span></label>
                                     <select class="bs-select form-control" name="sq_warehouse_id" id="SQ_WAREHOUSE_ID" required="required" data-actions-box="true">
                                        <option value=""> Select Warehouse </option>
                                        @foreach ( $lst_warehouses as $key => $warehouse_info )
                                                <option value="{{ $warehouse_info->w_id }}" >{{ $warehouse_info->w_warehouse_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                         <div class="col-md-4">
                         	<br/>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input"  type="checkbox" id="SQ_APPROVE_QUOTATION" name="sq_approve_quotation"  value="1" />
                                <span class="form-check-label fw-semibold text-muted">
                                   Quotation Approved
                                </span>
                            </label>   
                        </div>
                        <div class="col-md-12">&nbsp;</div>
                        <div class="col-md-4">
                        	<button type="button" name="btnAddProduct" id="btnAddProduct" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#AddNewProduct">Add Product</button>
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
                                	<textarea style="width:100%;height:250px;resize:none" id="SQ_QUOTATION_NOTES"  class="form-control" name="sq_quotation_notes"  cols=""></textarea>
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
											<tr data-index="1" class="QuotationItems">
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
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
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
			</div>
		</div>
	</div>
</div>
@endsection