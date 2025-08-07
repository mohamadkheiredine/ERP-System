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
<script type="text/javascript" src="{{ url('js/libraries/inventory/transferstock.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Stock Movement</h3>
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

               <div class="row">
                   <div class="col-md-12">
                       <div class="card shadow-sm">
                            <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible">
                                <h3 class="card-title">Add New Item</h3>
                                <div class="card-toolbar rotate-180">
                                    <i class="ki-duotone ki-down fs-1"></i>
                                </div>
                            </div>
                            <div id="kt_docs_card_collapsible" class="collapse show">
                                <div class="card-body">
                                    <form name="frm_transfer_items" id="FORM_TRANSFER_ITEMS">
                   {!! csrf_field() !!}
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> Product</label>
                                                    <select   name="mp_product_id" id="MP_PRODUCT_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Product">
                                                            @foreach( $lst_products as $key => $product_info )
                                                                    <option value="{{ $product_info->p_id }}">{{ $product_info->p_product_name }}</option>
                                                             @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                 <div class="form-group">
                                                       <label class="control-label"> Stock Quantity <span class="required"> * </span></label>
                                                       <input type="text" maxlength="50" name="mp_movement_quantity" id="MP_MOVEMENT_QUANTITY" class="form-control" required="required"   value="" />
                                                   </div>
                                           </div>
                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                       <label class="control-label"> Description</label>
                                                       <textarea name="mp_item_notes" id="MP_ITEM_NOTES" class="form-control" style="width:100%;Height:150px;resize:none"></textarea>
                                                   </div>
                                           </div>
                                        </div>
                                         <div class="row" style="height:5px;"></div>
                                        <div class="row">
                                            <div class="col-md-9"></div>
                                            <div class="col-md-3" align="right">
                                                 <button type="submit" name="btn_add_item" id="BTN_ADD_ITEM"  class="btn btn-success">Add Item</button>
                                                <button type="button" id="BACK_FORM" name="back_form" class="btn btn-warning">Back</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                   </div>
               </div>
               <div class="row" style="height:15px;"></div>

    <form name="frm_transfer_socket" id="FORM_TRANSFER_SOCKET">
            <div class="form-body">
                 <span id="hidden_fields">
                   {!! csrf_field() !!}
                    <input type="hidden" name="main_transfer" id="MAIN_TRANSFER" value="1" />
                    <input type="hidden" name="list_transfer_items" id="LST_TRANSFER_ITEMS" value="" />
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
                            <label> Warehouse Source</label>
                            <select   name="warehouse_source" id="WAREHOUSE_SOURCE" class="form-control form-select" data-control="select2" data-placeholder="Select Source Warehouse">
                                    <?php foreach ( $lst_warehouse as $key => $warehouse_info ) { ?>
                                            <option value="<?php echo $warehouse_info->w_id;  ?>"><?php echo $warehouse_info->w_warehouse_name;  ?></option>
                                    <?php  } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label> Warehouse Destination</label>
                            <select  name="warehouse_destination" id="WAREHOUSE_DESTINATION"  class="form-control form-select" data-control="select2" data-placeholder="Select Destination Warehouse">
                                    <?php foreach ( $lst_warehouse as $key => $warehouse_info ) { ?>
                                            <option value="<?php echo $warehouse_info->w_id;  ?>"><?php echo $warehouse_info->w_warehouse_name;  ?></option>
                                    <?php  } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label> Transfer Label </label>
                            <input type="text" name="sm_movement_label" id="SM_TRANSFER_LABEL" class="form-control" maxlength="255"  value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                          <br/>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                              <input class="form-check-input" type="checkbox" name="sm_movement_active" id="SM_MOVEMENT_ACTIVE"  value="1"  />
                              <span class="form-check-label fw-semibold text-muted">
                                Transfer Approved
                              </span>
                          </label>
                    </div>
                </div>
               <div class="row" style="height:50px;"></div>
               <div class="row">
                   <div class="col-md-12">
                       <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_products">Products</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="kt_tab_products" role="tabpanel">
                                <div class="table-responsive">
                                        <table class="table table-rounded table-striped border gy-7 gs-7">
                                                <thead>
                                                        <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                            <th>Code</th>
                                                            <th>Item</th>
                                                            <th>Quantity</th>
                                                            <th>Notes</th>
                                                        </tr>
                                                </thead>
                                                <tbody id="LstTransferItems">
                                                </tbody>
                                        </table>
                                </div>
                            </div>
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
