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
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/products.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/products/saveproducts.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Add New Product
				</h3>
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
                		<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
											Product Information
											<small></small>
										</h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
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
                                <div class="col-md-4" style="display:none">
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
                                        <label class="control-label"> Product Stock Alert <span class="required"> * </span></label>
                                        <input type="text" maxlength="255" name="p_product_stock_alert" id="P_STOCK_ALERT" class="form-control" required="required" maxlength="15"  value="" />
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
						<div class="row" style="height:25px">&nbsp;</div>
                		<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
											Product Financial Information
											<small></small>
										</h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
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
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
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
        						<div class="m-portlet m-portlet--mobile">
									<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text">
													Product Size & Weight Information
													<small>
														We use this information in order to validate the warehouse size and 
													</small>
												</h3>
											</div>
										</div>
									</div>
									<div class="m-portlet__body">
										<div class="row">
                                    		<div class="col-md-12"  id="ProductSizeInfo">
                                    			
                                    		</div>
                                    	</div>
									</div>
								</div>	 
                   			</div>
					        <div class="row" style="height:5px;"></div> 
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