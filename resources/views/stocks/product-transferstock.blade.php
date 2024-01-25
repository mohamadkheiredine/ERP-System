<?php
/***********************************************************
product-transferstock.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 23, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Product Managemet > Add Stock"])

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
	//BTN_SAVE_TRANSFE
	$("#BTN_SAVE_TRANSFER").on("click",products_module.ApplyTransferStock);
});
</script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New TRansfer stock data</h3>
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
    <form name="frm_transfer_socket" id="FORM_TRANSFER_SOCKET">
                                        <div class="form-body">
                                             <span id="hidden_fields">
                                               {!! csrf_field() !!}
                                                <input type="hidden" name="p_id" value="{{ $p_id }}" />
                                            </span>
                                            <div class="alert alert-success" style="display:none">
                                    				<strong>Success!</strong> Stock Information is saved successfully!
                                    			</div>
                                    			<div class="alert alert-danger" style="display:none">
                                    				<strong>Error!</strong> You have some form errors. Please check below.
                                    			</div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br/>
                                                        <label>Transfer Stock of {{ $ProductInfo->p_product_name }}</label>
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