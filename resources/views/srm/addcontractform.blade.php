<?php
/***********************************************************
addcontractform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Add New Contract Form
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
					Add New Contract
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
                                    <input type="text" name="sc_code" id="SS_CODE" class="form-control" required="required" maxlength="20"  value="" />
                                </div>
                        </div>
                        <div class="col-md-4">
                          	<div class="form-group">
                                <label class="control-label"> Contract Title <span class="required"> * </span></label>
                                <input type="text" name="sc_contract_title" id="SS_CONTRACT_TITLE" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                          	<div class="form-group">
                                <label class="control-label"> Contract Date <span class="required"> * </span></label>
                                <input type="text" name="sc_contract_date" id="SS_CONTRACT_DATE" class="form-control" readonly="readonly" required="required" maxlength="15"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                          	<div class="form-group">
                                <label class="control-label"> Delivery Date <span class="required"> * </span></label>
                                <input type="text" name="sc_contract_delivery_date" id="SS_CONTRACT_DATE" class="form-control" readonly="readonly" required="required" maxlength="15"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Contract Responsible <span class="required"> * </span> </label>
                                <select class="bs-select form-control" name="fk_user_owner" id="FK_USER_OWNER" required="required" data-actions-box="true">
                                        <option value="">-- Select Owner --</option>
                                        @foreach( $lst_users as $key => $user_info )
                                          <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
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
                                          <option value="{{ $sup_info->ss_id }}">{{ $sup_info->ss_supplier_name }}</option>
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
                                          <option value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . "-" .$currency_info->cc_currency_name }}</option>
                                        @endforeach
                                    
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Payment Type <span class="required"> * </span> </label>
                                <select class="bs-select form-control" name="sc_payment_type" id="SC_PAYMENT_TYPE" required="required" data-actions-box="true">
                                        <option value="">&nbsp;</option>
                                          <option value="2">Bank transfer</option>
                                          <option value="4">Cash</option>
                                          <option value="7">Check</option>
                                          <option value="6">Credit card</option>
                                          <option value="3">Debit payment order</option>
                                </select>
                            </div>
                        </div> 
                    </div>
                     <div class="row">
                                         	<div class="col-md-12" align="left">
                			<label>Contract Description </label>
                        </div>
                    	<div class="col-md-12" align="left">
                		 	<textarea class="form-control" id="SC_CONTRACT_DESCRIPTION" name="sc_contract_description" style="width:100%;height:250px;resize:none" ></textarea>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
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

@endsection