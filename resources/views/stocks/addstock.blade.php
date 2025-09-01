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
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Stock Data</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              @if($product_info->Category->pc_use_serial_number == 1)
               <li><a href="#" id="AddUnit" class="dropdown-item"> <i class="fa-solid fa-plus fa-lg"></i> Add Unit Ids Stock </a></li>
              @endif
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">

    <form name="frm_save_socket" id="FORM_SAVE_SOCKET">
                                        <div class="form-body">
                                             <span id="hidden_fields">
                                               {!! csrf_field() !!}
                                                <input type="hidden" name="p_id" id="P_ID" value="{{ $p_id }}" />
                                                <input type="hidden" name="serial_ids" value="" />
                                                <input type="hidden" name="stock_barecode" value="{{ $rand_barcode }}" />
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
                                            				 <img id="BARECODE_IMAGE" src="data:image/png;base64,{{ $bar_code_png }}" alt="barcode" height="50" width="150"   /><br/>
                                                 			<label class="BareCodeLabel">{{ $rand_barcode }}</label>
                                            			</div>
                                            			<div class="col-md-4">

                                            			</div>
                                            			<div class="col-md-4">
                                            				 <label>Initial Currency : </label><br/>
                                            				 <span class="IntialCurrency">{{ $currency_array[ $company_currency ]['cc_currency_code'] . " - " . $currency_array[ $company_currency ]['cc_currency_name'] }}</span>
                                            			</div>
                                            		</div>
                                            	</div>
                                            	 <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Stock barecode </label>
                                                            <input type="text" maxlength="50" name="is_stock_barcode" id="IS_STOCK_BARCODE" class="form-control" required="required"   value="{{ $rand_barcode }}" />
                                                    </div>
                                                </div>
                                            	 <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Stock Supplier </label>
                                                        <select name="is_supplier_id" id="IS_SUPPLIER_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Supplier">
                                                                @foreach( $lst_suppliers as $key => $sup_info )
                                                                        <option value="{{ $sup_info->ss_id }}">{{ $sup_info->ss_supplier_name }}</option>
                                                                 @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Warehouse</label>
                                                        <select name="fk_warehouse_id" id="FK_WAREHOUSE_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Warehouse">
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
                                                        <select  name="fk_zone_id" id="FK_ZONE_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Zones">
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
                                                        <label class="control-label"> Total Pruchase Stock</label><br/>
                                                        <span id="TOTAL_PURCHASE_STOCK" class="text-primary fs-2"></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> Selling Item </label>
                                                            <input type="text" maxlength="255" name="is_selling_price" id="IS_SELLING_STOCK" class="form-control" required="required"   value="" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label"> Total Selling Stock</label><br/>
                                                        <span id="TOTAL_SELLING_STOCK" class="text-primary fs-2"></span>
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
                                                        <select name="is_stock_currency" id="IS_STOCK_CURRENCY" class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
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
