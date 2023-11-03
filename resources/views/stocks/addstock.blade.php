<?php
/***********************************************************
addstock.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 22, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/
 
?>

@extends('layouts.layout',['page_title' => "Product Managemet"])

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
	<script type="text/javascript" src="{{ url('js/modules/products.module.js') }}"></script>
	<script type="text/javascript" src="{{ url('default/assets/plugins/jquery-scanner-detection/jquery.scannerdetection.js') }}"></script>
	<script type="text/javascript" src="{{ url('js/libraries/inventory/addstock.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text"> Add Stock </h3>
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
																	@if($product_info->Category->pc_use_serial_number == 1)
																	<li class="m-nav__item">
                        												<a href="#" id="AddUnit" class="m-nav__link">
                        													<i class="m-nav__link-icon flaticon-chat-1"></i>
                        													<span class="m-nav__link-text">
                        														Add Unit Ids Stock
                        													</span>
                        												</a>
                        											</li>
                        											@endif
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
                                     <form name="frm_save_socket" id="FORM_SAVE_SOCKET">
                                        <div class="form-body">
                                             <span id="hidden_fields">
                                               {!! csrf_field() !!}
                                                <input type="hidden" name="p_id" id="P_ID" value="{{ $p_id }}" />
                                                <input type="hidden" name="serial_ids" value="" />
                                               <input type="hidden" name="company_currency" value="{{ session('company_currency') }}" />
                                            </span>
                                            <div class="alert alert-success" style="display:none">
                                    				<strong>Success!</strong> Stock Information is saved successfully!
                                    			</div>
                                    			<div class="alert alert-danger" style="display:none">
                                    				<strong>Error!</strong> You have some form errors. Please check below.
                                    			</div>
                                            <div class="row">
                                            	<div class="col-md-12">
                                            		<div class="row">
                                            			<div class="col-md-4">
                                            				 <img id="BARECODE_IMAGE" src="" alt="barcode" height="50" width="150"   /><br/>
                                                 			<label class="BareCodeLabel"></label>
                                            			</div>
                                            			<div class="col-md-4">
                                            				 <img id="PRODUCT_PROFILE" src=""  style="max-width: 200px; height: 150px;width:auto;"  /><br/>
                                            			</div>
                                            			<div class="col-md-4">
                                            				 <label>Initial Currency : </label><br/>
                                            				 <span class="IntialCurrency">{{ $currency_array[ $company_currency ]['cc_currency_code'] . " - " . $currency_array[ $company_currency ]['cc_currency_name'] }}</span>
                                            			</div>
                                            		</div>
                                            	</div>
                                            	@if($product_info->Category->pc_use_serial_number == 1)
                                            	 <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Stock barecode </label>
                                                            <input type="text" maxlength="50" name="is_stock_uid" id="IS_STOCK_UID" class="form-control" required="required"   value="{{ $product_info->p_barcode }}" />
                                                    </div>
                                                </div>
                                                @endif
                                            	 <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Stock Supplier </label>
                                                        <select class="bs-select form-control" name="is_supplier_id" id="IS_SUPPLIER_ID" data-actions-box="true">
                                                                @foreach( $lst_suppliers as $key => $sup_info )
                                                                        <option value="{{ $sup_info->ss_id }}">{{ $sup_info->ss_supplier_name }}</option>
                                                                 @endforeach 
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Warehouse</label>
                                                        <select class="bs-select form-control" name="fk_warehouse_id" id="FK_WAREHOUSE_ID" data-actions-box="true">
                                                                <?php foreach ( $lst_warehouse as $key => $warehouse_info ) { ?>
                                                                        <option value="<?php echo $warehouse_info->w_id;  ?>"><?php echo $warehouse_info->w_warehouse_name;  ?></option>
                                                                <?php  } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Warehoue Zones</label>
                                                        <div class="WarehouseZonesDropDown">
                                                        <select class="bs-select form-control" name="fk_zone_id" id="FK_ZONE_ID" data-actions-box="true">
                                                        <option value="-1">Select Zone</option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br/>
                                                        <label>Add Stock to {{ $product_info->p_product_name }}</label>
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> Stock Quantity <span class="required"> * </span></label>
                                                            <input type="text" maxlength="50" name="is_quanity" id="STOCK_QUANTITY" class="form-control" required="required"   value="" />
                                                        </div>
                                                </div>
                                                 <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> Pruchase Stock</label>
                                                            <input type="text" maxlength="255" name="is_price_stock" id="IS_PRICE_STOCK" class="form-control" required="required"   value="" />
                                                        </div>
                                                </div>
                                                 <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> Selling Stock </label>
                                                            <input type="text" maxlength="255" name="is_selling_price" id="IS_SELLING_STOCK" class="form-control" required="required"   value="" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> Discount </label>
                                                            <input type="text" maxlength="255" name="is_discount" id="IS_DISCOUNT" class="form-control" required="required"   value="" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> Wholesale Price </label>
                                                            <input type="text" maxlength="255" name="is_wholesale_price" id="IS_WHOLESALE_PRICE" class="form-control" required="required"   value="" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> Vendor Price </label>
                                                            <input type="text" maxlength="255" name="is_vendor_price" id="IS_VENDOR_PRICE" class="form-control" required="required"   value="" />
                                                        </div>
                                                </div>
                                                 <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Stock Currency </label>
                                                        <select class="bs-select form-control" name="is_stock_currency" id="IS_STOCK_CURRENCY" data-actions-box="true">
                                                                @foreach( $lst_currencies as $key => $curr_info )
                                                                        <option  value="{{ $curr_info->cc_id }}">{{ $curr_info->cc_currency_code . " - " . $curr_info->cc_currency_name }}</option>
                                                                 @endforeach 
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Exchange Rate </label>
                                                       	<input type="text" maxlength="255" name="is_stock_exchange_rate" id="IS_STOCK_EXCHANGE_RATE" class="form-control" required="required"   value="" />
                                                    </div>
                                                </div>
                                            </div>
                                           <div class="row" style="height:5px;"></div>
                                            <div class="row">
                                                <div class="col-md-9"></div>
                                                <div class="col-md-3" align="right">
                                                     <button type="submit" name="btn_create_stock" id="BTN_CREATE_STOCK"  class="btn btn-info">Save Stock</button>
                                                    <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
							</div>
					   </div>

@endsection