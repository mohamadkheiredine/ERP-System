<?php
/***********************************************************
editcontractform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Edit Contract Info Saved in the database
***********************************************************/



?>


@extends('layouts.layout',['page_title' => "Supplier Contract Management"])

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
<script type="text/javascript" src="{{ url('js/modules/suppliercontracts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/srm/savesuppliercontracts.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					 Edit Existing Contract
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
             <form name="frm_save_contract" id="FORM_SAVE_CONTRACT">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                        	<input type="hidden" name="sc_id" id="SC_ID" value="{{ $contract_info->sc_id }}" />
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Supplier Contract Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Contract Code<span class="required"> * </span></label>
                                    <input type="text" name="sc_code" id="SS_CODE" class="form-control" required="required" maxlength="20"  value="{{ $contract_info->sc_code }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                          	<div class="form-group">
                                <label class="control-label"> Contract Title <span class="required"> * </span></label>
                                <input type="text" name="sc_contract_title" id="SS_CONTRACT_TITLE" class="form-control" required="required" maxlength="255"  value="{{ $contract_info->sc_contract_title }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                          	<div class="form-group">
                                <label class="control-label"> Contract Date <span class="required"> * </span></label>
                                <input type="text" name="sc_contract_date" id="SS_CONTRACT_DATE" class="form-control" readonly="readonly" required="required" maxlength="15"  value="{{ $contract_info->sc_contract_date }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                          	<div class="form-group">
                                <label class="control-label"> Delivery Date <span class="required"> * </span></label>
                                <input type="text" name="sc_contract_delivery_date" id="SS_CONTRACT_DATE" class="form-control" readonly="readonly" required="required" maxlength="15"  value="{{ $contract_info->sc_contract_delivery_date }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Contract Responsible <span class="required"> * </span> </label>
                                <select class="bs-select form-control" name="fk_user_owner" id="FK_USER_OWNER" required="required" data-actions-box="true">
                                        <option value="">-- Select Owner --</option>
                                        @foreach( $lst_users as $key => $user_info )
                                          <option {{ $contract_info->fk_user_owner == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                        @endforeach
                                    
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Supplier <span class="required"> * </span> </label>
                                <select class="bs-select form-control" name="fk_supplier_id" id="FK_SUPPLIER_ID" required="required" data-actions-box="true">
                                        <option value="">-- Select Supplier --</option>
                                        @foreach( $lst_suppliers as $key => $sup_info )
                                          <option {{ $contract_info->fk_supplier_id == $sup_info->ss_id ? "selected" : "" }} value="{{ $sup_info->ss_id }}">{{ $sup_info->ss_supplier_name }}</option>
                                        @endforeach
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Currency <span class="required"> * </span> </label>
                                <select class="bs-select form-control" name="sc_currency_id" id="SC_CURRENCY_ID" required="required" data-actions-box="true">
                                        <option value="">-- Select Currency --</option>
                                        @foreach( $lst_currencies as $key => $currency_info )
                                          <option {{ $contract_info->sc_currency_id ==  $currency_info->cc_id ? "selected" : "" }}  value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . "-" .$currency_info->cc_currency_name }}</option>
                                        @endforeach
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Payment Type <span class="required"> * </span> </label>
                                <select class="bs-select form-control" name="sc_payment_type" id="SC_PAYMENT_TYPE" required="required" data-actions-box="true">
                                        <option value="">&nbsp;</option>
                                          <option {{ $contract_info->fk_supplier_id == 1 ? "selected" : "" }} value="1">Bank transfer</option>
                                          <option {{ $contract_info->fk_supplier_id == 2 ? "selected" : "" }} value="2">Cash</option>
                                          <option {{ $contract_info->fk_supplier_id == 3 ? "selected" : "" }} value="3">Check</option>
                                          <option {{ $contract_info->fk_supplier_id == 4 ? "selected" : "" }} value="4">Credit card</option>
                                          <option {{ $contract_info->fk_supplier_id == 5 ? "selected" : "" }} value="5">Debit payment order</option>
                                </select>
                            </div>
                        </div> 
                    </div>
                     <div class="row">
                                         	<div class="col-md-12" align="left">
                			<label>Contract Description </label>
                        </div>
                    	<div class="col-md-12" align="left">
                		 	<textarea class="form-control" id="SC_CONTRACT_DESCRIPTION" name="sc_contract_description" style="width:100%;height:250px;resize:none" >{{ $contract_info->sc_contract_description }}</textarea>
                        </div>
                    </div>
                    <div class="row"><div class="col-md-12 col-xl-12" style="height: 50px;">&nbsp;</div></div>
                    <div class="row">
                    		<div class="col-md-12 col-xl-12">
                    			<ul class="nav nav-tabs" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#ProductTab">
											Products
										</a>
									</li> 
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#InvoicesTab">
											Invoices
										</a>
									</li> 
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="ProductTab" role="tabpanel">
										<div class="row">
											<div class="col-md-12 col-xl-12">
												<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                									<div class="row align-items-center">
                										<div class="col-xl-8 order-2 order-xl-1">
                											<div class="form-group m-form__group row align-items-center">
                												<div class="col-md-4">
                												<div class="m-input-icon m-input-icon--right">
                														<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
                														<span class="m-input-icon__icon m-input-icon__icon--right">
                															<span>
                																<i class="la la-search"></i>
                															</span>
                														</span>
                													</div>
                
                												</div>
                												<div class="col-md-4">
                                                                    <div class="m-separator m-separator--dashed d-xl-none"></div>
                												</div>
                												<div class="col-md-4">
                                                                     <div class="m-separator m-separator--dashed d-xl-none"></div>
                												</div>
                											</div>
                										</div>
                										<div class="col-xl-4 order-1 order-xl-2 m--align-right">
                											<div class="m-separator m-separator--dashed d-xl-none"></div>
                										</div>
                									</div>
                								</div>
                								<!--end: Search Form -->
                		                          <!--begin: Datatable -->
                								<div class="m_datatable" id="LstProducts">
                
                								</div>
											</div> 
										</div>
										<div class="row">
											<div class="col-md-12" align="right">
												<button type="button" name="btn_popup_product" data-toggle="modal" data-target="#ContractProductsModel" id="BTN_POPUP_PRODUCT" class="btn btn-info" > Add Product </button>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="InvoicesTab" role="tabpanel">
										<div class="row">
											<div class="col-md-12 col-xl-12">
										 
											</div> 
										</div>
									</div>
								</div>
                    		</div>
                    </div>
					<div class="row"><div class="col-md-12 col-xl-12" style="height: 50px;">&nbsp;</div></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_contract" id="BTN_SAVE_CONTRACT"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>
<div class="modal fade" id="ContractProductsModel" tabindex="-1" role="dialog" aria-labelledby="ContractProductsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ContractProductsModalLabel">Contract Products</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-md-12">
      			<div class="form-group">
      				<label>Product :</label><br/>
      				<select data-actions-box="true" style="width:100%" name="fk_product_id" id="FK_PRODUCT_ID" class="bs-select form-control">
      					<option value="">-- Select Product --</option>
      					@foreach($lst_srm_products as $index => $product_info)
      						<option value="{{ $product_info->sp_id }}">{{ $product_info->sp_product_name }}</option>
      					@endforeach
      				</select>
      			</div>
      		</div>
      		<div class="col-md-12">
      			<div class="form-group">
      				<label>Quantity :</label>
      				<input type="number" name="sr_product_quantity" id="SR_PRODUCT_QUANITY" class="form-control" min="0" max="99999999" step="1" value="0" />
      			</div>
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" name="btn_add_product" id="BTN_ADD_PRODUCT" class="btn btn-primary">Add Product</button>
      </div>
    </div>
  </div>
</div>
@endsection