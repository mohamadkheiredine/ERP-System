<?php
/***********************************************************
stocktransfer.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 24, 2019
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
<script type="text/javascript">
$(function(){
	$("#BTN_SAVE_TRANSFER").on("click",products_module.ApplyTransferStock);
	$('select').select2();
});
</script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">Transfer Stock</h3>
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
                                     <form name="frm_transfer_socket" id="FORM_TRANSFER_SOCKET">
                                        <div class="form-body">
                                             <span id="hidden_fields">
                                               {!! csrf_field() !!}
                                                <input type="hidden" name="main_transfer" id="MAIN_TRANSFER" value="1" />
                                            </span>
                                            <div class="alert alert-success" style="display:none">
                                    				<strong>Success!</strong> Transfer Stock is saved successfully!
                                    			</div>
                                    			<div class="alert alert-danger" style="display:none">
                                    				<strong>Error!</strong> You have some form errors. Please check below.
                                    			</div>
                                            <div class="row">
                                                 <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Product</label>
                                                        <select class="bs-select form-control" name="p_id" id="P_ID" data-actions-box="true">
                                                                @foreach( $lst_products as $key => $product_info )
                                                                        <option value="{{ $product_info->p_id }}">{{ $product_info->p_product_name }}</option>
                                                                 @endforeach 
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Warehouse Source</label>
                                                        <select class="bs-select form-control" name="warehouse_source" id="WAREHOUSE_SOURCE" data-actions-box="true">
                                                                <?php foreach ( $lst_warehouse as $key => $warehouse_info ) { ?>
                                                                        <option value="<?php echo $warehouse_info->w_id;  ?>"><?php echo $warehouse_info->w_warehouse_name;  ?></option>
                                                                <?php  } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Warehouse Destination</label>
                                                        <select class="bs-select form-control" name="warehouse_destination" id="WAREHOUSE_DESTINATION" data-actions-box="true">
                                                                <?php foreach ( $lst_warehouse as $key => $warehouse_info ) { ?>
                                                                        <option value="<?php echo $warehouse_info->w_id;  ?>"><?php echo $warehouse_info->w_warehouse_name;  ?></option>
                                                                <?php  } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> Stock Quantity <span class="required"> * </span></label>
                                                            <input type="text" maxlength="50" name="stock_quanity" id="STOCK_QUANTITY" class="form-control" required="required"   value="" />
                                                        </div>
                                                </div>
                                            </div>
                                           <div class="row" style="height:5px;"></div>
                                            <div class="row">
                                                <div class="col-md-9"></div>
                                                <div class="col-md-3" align="right">
                                                     <button type="submit" name="btn_save_transfer" id="BTN_SAVE_TRANSFER"  class="btn btn-info">Save Stock</button>
                                                    <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
							</div>
					   </div>

@endsection