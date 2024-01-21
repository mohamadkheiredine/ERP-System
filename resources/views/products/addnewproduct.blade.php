<?php
/***********************************************************
addnewproduct.blade.php
Product :
Version : 1.0
Release : 2
Date Created :Oct 8, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/
?>


@extends('layouts.layout',['page_title' => "Product Management"])

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
<script type="text/javascript" src="{{ url('js/modules/products.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/products/saveproducts.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Product</h3>
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
    <form name="frm_save_product" id="FORM_SAVE_PRODUCT">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                      <input type="hidden" name="p_barecode" value="{{ $rand_barcode }}" />
                      <input type="hidden" name="p_barecode_img" value="{{ $bar_code_png }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
        				<strong>Success!</strong> Product  Information is saved successfully!
        			</div>
        			<div class="alert alert-danger" style="display:none">
        				<strong>Error!</strong> You have some form errors. Please check below.
        			</div>
        			<div class="row">
                	<div class="col-md-12" align="left">
                		<label>Product Picture </label>
                	</div>
                     <div class="col-md-3">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                <img id="AVATAR_PIC" height="120" src="{{ url('images/NoImageAvailable.jpg') }}" alt="" /> </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>

                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="clearfix margin-top-10">
                            <div>
                                <span class="btn default btn-file" style="text-align: left;">
                                    <span class="fileinput-new"> Select image </span><br/>
                                    <input type="file" name="pp_avatar_pic" id="PP_AVATAR_PIC" /> </span>
                            </div>
                            <br>
                            <span class="label label-danger"> NOTE! </span><br><br>
                            <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                        </div>
                    </div>
                </div>
                	<div class="col-md-12">
                		<div class="card card-bordered">
                            <div class="card-header bg-light">
                                <h3 class="card-title">Product Information</h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-sm btn-light">
                                        Action
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                            <div class="row">
    								<div class="col-md-4">
                                      	<div class="form-group">
                                             <img id="BARCODE_IMG" src="data:image/png;base64,{{ $bar_code_png }}" alt="barcode" height="50" width="150"   /><br/>
                                             <label class='lblbarcode'>{{ $rand_barcode }}</label>
                                        </div>
                                    </div>
                                 <div class="col-md-4">
                                  <div class="form-group">
                                        <label class="control-label"> Product Barcode <span class="required"> * </span></label>
                                        <input type="text"  name="p_bar_code" id="P_BAR_CODE" class="form-control" required="required" maxlength="50" value="{{ $rand_barcode }}" />
                                    </div>
                           		 </div>
                           		 <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Product Category</label>
                                        <select class="bs-select form-control" name="fk_pc_id" id="FK_PC_ID" data-actions-box="true">
                                                <option value="">No Parent</option>
                                                <?php foreach ( $lst_product_categories_array as $key => $category_info ) { ?>
                                                        <option value="<?php echo $category_info->pc_id;  ?>"><?php echo $category_info->pc_category;  ?></option>
                                                <?php  } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                  	<div class="form-group">
                                        <label class="control-label"> Product Ref <span class="required"> * </span></label>
                                        <input type="text" name="p_product_ref" id="P_PRODUCT_REF" class="form-control" maxlength="15" value="{{ $rand_barcode }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                  	<div class="form-group">
                                        <label class="control-label"> Product Name <span class="required"> * </span></label>
                                        <input type="text" maxlength="255" name="p_product_name" id="P_PRODUCT_NAME" class="form-control"  maxlength="255" required="required"   value="" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                  	<div class="form-group">
                                        <label class="control-label"> Product Color </label>
                                        <input type="text" maxlength="255" name="p_product_color" id="P_PRODUCT_COLOR" class="form-control"  value="" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                  	<div class="form-group">
                                        <label class="control-label"> Product Quantity</label>
                                        <input type="text"  name="p_product_quantity" id="P_PRODUCT_QUANTITY" class="form-control" maxlength="15" value="0" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                  	<div class="form-group">
                                        <label class="control-label"> Product Stock Alert <span class="required"> * </span></label>
                                        <input type="text" maxlength="255" name="p_product_stock_alert" id="P_STOCK_ALERT" class="form-control" required="required" maxlength="15"  value="" />
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Product Supplier</label>
                                        <select class="bs-select form-control" name="fk_psupplier_id" id="FK_PSUPPLIER_ID" data-actions-box="true">
                                                <option value="">-- Select Supplier --</option>
                                                <?php foreach ( $lst_suppliers as $key => $supplier_info ) { ?>
                                                        <option value="{{ $supplier_info->ss_id }}">{{  $supplier_info->ss_supplier_name }}</option>
                                                <?php  } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                  	<div class="form-group">
                                        <label class="control-label"> Production Date</label>
                                        <input type="text" name="p_product_production_date" id="P_PRODUCT_PRODUCTION_DATE" class="form-control"  maxlength="15" value="" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                  	<div class="form-group">
                                        <label class="control-label"> Expiry Date</label>
                                        <input type="text" name="p_product_expiry_date" id="P_PRODUCT_EXPIRY_DATE" class="form-control"  maxlength="15" value="" />
                                    </div>
                                </div>
                                                                    
                                 @if($license_array->PRODUCTION_MODULE == 1)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Product Lot</label>
                                        <select class="bs-select form-control" name="fk_lot_id" id="FK_LOT_ID" data-actions-box="true">
                                                <option value="">No Lot</option>
                                                @foreach($lst_lot as $index => $lo_info)
                                                	<option value="{{ $lo_info->l_id }}">{{ $lo_info->l_lot_label  }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Product Type</label>
                                        <select class="bs-select form-control" name="p_product_type" id="P_PRODUCT_TYPE" data-actions-box="true">
                                                <option value="">Product Type</option>
                                                <option value="1">Manufactured Product</option>
                                                <option value="2">Raw Material</option>
                                            	<option  value="3" selected>Regular Products</option>
                                        </select>
                                    </div>
                                </div>
                          		 @endif
                                <div class="col-md-4">
                          			<div class="form-group">
                          				<label> UNit type </label><br/>
                          				<select name="p_product_unit_type" id="P_PRODUCT_UNIT_TYPE" style="width:100%;" class="form-control">
                          					<option value="">-- Select type --</option>       
                          					<option value="size">size</option>
                          					<option value="volume">Volume</option> 
                          					<option value="weight">Weight</option> 
                          				</select>
                          			</div>
                          		</div>
								</div>
								<div class="row" style="height:25px">&nbsp;</div>
				        <div class="row">
                            <div class="col-md-9"></div>
                            <div class="col-md-3" align="right">
                                 <button type="submit" name="btn_save_product" id="BTN_SAVE_PRODUCT_TOP"  class="btn btn-info">Save</button>
                                <button type="button" id="BACK_FORM_TOP" name="back_form" class="btn default">Back</button>
                            </div>
                        </div>
                            </div>
                          </div>
                	   
						<div class="row" style="height:25px">&nbsp;</div>
						@if(config('appconfig.price_by_supplier') == 0)
						<div class="card card-bordered">
                            <div class="card-header bg-light">
                                <h3 class="card-title">Product Financial Information</h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-sm btn-light">
                                        Action
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                            <div class="row">
									<div class="col-md-4">
                                      	<div class="form-group">
                                            <label class="control-label"> Product Selling Price&nbsp;<b class="CurrencyCode">{{ session('currency_symbol') }}</b> <span class="required"> * </span></label>
                                            <input type="text" maxlength="15" name="p_product_selling_price" id="P_PRODUCT_SELLING_PRICE" class="form-control" required="required"   value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                      	<div class="form-group">
                                            <label class="control-label"> Product Min Selling Price&nbsp;<b class="CurrencyCode">{{ session('currency_symbol') }}</b> <span class="required"> * </span></label>
                                            <input type="text" maxlength="15" name="p_product_min_selling_price" id="P_PRODUCT_MIN_SELLING_PRICE" class="form-control" required="required"   value="" />
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="form-group">
                                            <label> Stock Currency </label>
                                            <select class="bs-select form-control" name="p_product_currency" id="P_PRODUCT_CURRENCY" data-actions-box="true">
                                                    @foreach( $lst_currencies as $key => $curr_info )
                                                            <option {{ session('company_currency') == $curr_info->cc_id ? "selected" : "" }} value="{{ $curr_info->cc_id }}">{{ $curr_info->cc_currency_code . " - " . $curr_info->cc_currency_name }}</option>
                                                     @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="display: none">
                                        <div class="form-group">
                                            <label> Sales Accounting</label>
                                            <select class="bs-select form-control" name="p_sale_accounting_code" id="P_SALE_ACCOUNTING_CODE" data-actions-box="true">
                                                    <option value="">-- Select Account --</option>
                                                    @foreach ( $lst_accounts as $key => $acc_info )
                                                            <option value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="display: none">
                                                <div class="form-group">
                                                    <label> Purchase Accounting</label>
                                                    <select class="bs-select form-control" name="p_purchase_accounting_code" id="P_PURCHASE_ACCOUNTING_CODE" data-actions-box="true">
                                                            <option value="">-- Select Account --</option>
                                                            @foreach ( $lst_accounts as $key => $acc_info )
                                                                    <option value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                              	<div class="form-group">
                                                    <label class="control-label"> Product Tax Rate</label>
                                                     <select class="bs-select form-control" name="p_product_tax_rate" id="P_PRODUCT_TAX_RATE" data-actions-box="true">
                                                            <option value="">&nbsp;&nbsp;</option>
                                                            @foreach ( $lst_taxes as $key => $tax_info )
                                                                    <option value="{{ $tax_info->av_id }}">{{ $tax_info->av_vat_code . " - " .  $tax_info->av_vat_label }}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                            </div>
        								</div>
                            </div>
                         </div> 
        						@endif
        						<div class="row" style="height:25px">&nbsp;</div>	
        						<div class="row">
        							<div class="col-md-12">
                                         <div class="form-group">
                                            <label class="control-label"> Product Description</label><br/>
                                            <textarea style="width:100%;height:250px;resize:none" id="P_PRODUCT_DESCRIPTION"  class="form-control" name="p_product_description"  cols=""></textarea>
                                         </div>
                                    </div>
        						</div>	
        						<div class="row" style="height:25px">&nbsp;</div>
        						<div class="row">
                                    <div class="col-md-9"></div>
                                    <div class="col-md-3" align="right">
                                         <button type="submit" name="btn_save_product" id="BTN_SAVE_PRODUCT_MIDDLE"  class="btn btn-info">Save</button>
                                        <button type="button" id="BACK_FORM_MIDDLE" name="back_form" class="btn default">Back</button>
                                    </div>
                                </div>    	
        						<div class="row" style="height:25px">&nbsp;</div>	
        						<div class="row">&nbsp;</div>
        						<div class="card card-bordered">
                                    <div class="card-header bg-light">
                                        <h3 class="card-title">Product Size & Weight Information
													<small>
														&nbsp;&nbsp;We use this information in order to validate the warehouse size and 
													</small></h3>
                                        <div class="card-toolbar">
                                            <button type="button" class="btn btn-sm btn-light">
                                                Action
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                    <div class="row">
                                    		<div class="col-md-12"  id="ProductSizeInfo">
                                    			
                                    		</div>
                                    	</div>
                                    </div>
                                   </div>  
                   			</div>
					        <div class="row" style="height:5px;"></div> 
					        <div class="row">
					        	<div class="col-md-12">
					        	<div class="card card-bordered">
                            <div class="card-header bg-light">
                                <h3 class="card-title">Default Storage</h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-sm btn-light">
                                        Action
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                            	<div class="row">
                            		 <div class="col-md-4">
                                          	<div class="form-group">
                                                <label class="control-label">Warehouse</label>
                                                 <select   data-control="select2" data-placeholder="Select a warehouse" class="form-select" name="fk_warehouse_id" id="FK_WWAREHOUSE_ID" data-actions-box="true">
                                                        <option value="">&nbsp;&nbsp;</option>
                                                        @foreach ( $lst_warehouses as $key => $warehouse_info )
                                                                <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                          	<div class="form-group">
                                                <label class="control-label">Zone</label>
                                                <div class="DefaultZone form-group">
                                                 <select data-control="select2" data-placeholder="Select a zone" class="form-select"  name="fk_zone_id" id="FK_ZONE_ID" data-actions-box="true">
                                                        <option value="">&nbsp;&nbsp;</option> 
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="col-md-4">
                                          	<div class="form-group">
                                                <label class="control-label">Floor</label>
                                                <div class="DefaultFloor form-group">
                                                 <select data-control="select2" data-placeholder="Select a Floor" class="form-select" name="fk_floor_id" id="FK_FLOOR_ID" data-actions-box="true">
                                                        <option value="">&nbsp;&nbsp;</option> 
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                            	</div>
                            </div>
                            </div>
					        	</div>
					        </div>
					        
					        <div class="row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3" align="right">
                                     <button type="submit" name="btn_save_product" id="BTN_SAVE_PRODUCT_BOTTOM"  class="btn btn-info">Save</button>
                                    <button type="button" id="BACK_FORM_BOTTOM" name="back_form" class="btn default">Back</button>
                                </div>
                            </div>            
                </div>
            </form>
    </div>
 </div>

@endsection