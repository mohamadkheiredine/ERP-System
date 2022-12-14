<?php
/***********************************************************
editbidding.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Edit Bidding Information
***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Supplier Bidding Management"])

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
<script type="text/javascript" src="{{ url('js/modules/bidding.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/srm/savebidding.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
<div class="m-portlet__head">
	<div class="m-portlet__head-caption">
		<div class="m-portlet__head-title">
			<h3 class="m-portlet__head-text">
				Edit Bidding Information
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
         <form name="frm_save_bidding" id="FORM_SAVE_BIDDING">
            <div class="form-body">
                 <span id="hidden_fields">
                   {!! csrf_field() !!}
                   <input type="hidden" name="sb_id" value="{{ $bidding_info->sb_id }}" />
                </span>
                <div class="alert alert-success" style="display:none">
        				<strong>Success!</strong> Supplier Bidding Information is saved successfully!
        			</div>
        			<div class="alert alert-danger" style="display:none">
        				<strong>Error!</strong> You have some form errors. Please check below.
        			</div>
                <div class="row">
                    <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Bidding Ref</label>
                                <input type="text" name="sb_bidding_ref" id="SB_BIDDING_REF" class="form-control" maxlength="15"  value="{{ $bidding_info->sb_bidding_ref }}" />
                            </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                            <label class="control-label"> Bidding Title <span class="required"> * </span></label>
                            <input type="text" name="sb_bid_title" id="SB_BID_Title" class="form-control" required="required" maxlength="255"  value="{{ $bidding_info->sb_bid_title }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Bidding Owner  <span class="required"> * </span> </label>
                            <select class="bs-select form-control" name="sb_owner_id" id="SB_OWNER_ID" required="required" data-actions-box="true">
                                    <option value="">Select Owner </option>
                                    @foreach ( $lst_users_info as $key => $user_info )
                                            <option value="{{ $user_info->id }}" {{ $bidding_info->sb_owner_id == $user_info->id ? "selected" : "" }} >{{ $user_info->u_fullname }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Bidding Itm Types  <span class="required"> * </span> </label>
                            <select class="bs-select form-control" name="sb_item_types" id="SB_ITEM_TYPES" required="required" data-actions-box="true">
                                    <option value="">Select Item Types </option>
                                    <option {{ $bidding_info->sb_item_types == 1 ? "selected" : "" }} value="1"> Services </option>
                                    <option {{ $bidding_info->sb_item_types == 2 ? "selected" : "" }} value="2"> Goods </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                            <label class="control-label"> Start Date <span class="required"> * </span></label>
                            <input type="text" name="sb_start_date" id="SB_START_DATE" class="form-control" required="required" maxlength="11" readonly="readonly"  value="{{ $bidding_info->sb_start_date }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                            <label class="control-label"> End Date <span class="required"> * </span></label>
                            <input type="text" name="sb_end_date" id="SB_END_DATE" class="form-control" required="required" maxlength="11" readonly="readonly"  value="{{ $bidding_info->sb_end_date }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                            <label class="control-label"> RFQ Issue Date <span class="required"> * </span></label>
                            <input type="text" name="sb_rfq_issue_date" id="SB_RFQ_ISSUE_DATE" class="form-control" required="required" maxlength="11" readonly="readonly"  value="{{ $bidding_info->sb_rfq_issue_date }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                            <label class="control-label"> RFQ Due Date <span class="required"> * </span></label>
                            <input type="text" name="sb_due_rfq_date" id="SB_DUE_RFQ_DATE" class="form-control" required="required" maxlength="11" readonly="readonly"  value="{{ $bidding_info->sb_due_rfq_date }}" />
                        </div>
                    </div>
                    <div class="col-md-12">
                         <div class="form-group">
                            <label class="control-label"> Bidding Description <span class="required"> * </span></label><br/>
                            <textarea style="width:100%;height:450px;resize:none" id="SB_BID_DESCRIPTION"  class="form-control" name="sb_bid_description"  cols="">{{ $bidding_info->sb_bid_description }}</textarea>
                         </div>
                    </div>
                </div>
               <div class="row">
               		<div class="col-md-12">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#tabProducts">
									Products
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tabProducts" role="tabpanel">
								<div class="row">
									<div class="col-md-12" id="BiddingProducts">
									
									</div>
								</div>
								<div class="row">
									<div class="col-md-12" align="right">
										<button type="button" name="btn_add_product" id="BTN_ADD_PRODUCT"  class="btn btn-info">Add Product</button>		
									</div>
								</div>
							</div> 
						</div>
               		</div>
               </div>
               <div class="row" style="height:30px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_bidding" id="BTN_SAVE_BIDDING"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>
<!--begin:: Insert Items Modal-->
<div class="modal fade" id="InserItems" tabindex="-1" role="dialog" aria-labelledby="InserItemsModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InserItemsModalLabel">
					Insert Items
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_bid_insert_items" id="FRM_BID_INSERT_ITEMS" method="post"  enctype="multipart/form-data"> 
				    {!! csrf_field() !!}
				    <input type="hidden" name="sb_id" id="SB_ID" value="{{ $bidding_info->sb_id }}" />
				 	<div class="row">
				 		<div class="col-md-12">
    			 			  <div class="form-group">
                                  <label class="control-label"> Product Name </label><br/>
                                  <input type="text" name="bi_item_title" id="BI_ITEM_TITLE" class="form-control" required="required" maxlength="255"  value="" />
                               </div>
				 		</div>
				 		<div class="col-md-12">
				 			<div class="form-group">
                                <div class="form-group">
                                  <label class="control-label"> Product Quantity </label><br/>
                                  <input type="number" min="1" max="9999999" name="bi_item_quanity" id="BI_ITEM_QUANTITY" class="form-control" required="required"  value="0" />
                               </div>
                           </div>
				 		</div>
				 		<div class="col-md-12">
				 			<div class="form-group">
                                <div class="form-group">
                                  <label class="control-label"> Currency </label><br/>
                                   <select class="bs-select form-control" name="bi_currency_id" id="BI_CURRENCY_ID" style="width:100%;" required="required" data-actions-box="true">
                                            <option value="">Select Currency </option>
                                            @foreach ( $lst_currencies as $key => $currency_info )
                                                    <option value="{{ $currency_info->cc_id }}"  >{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name }}</option>
                                            @endforeach
                                    </select>
                               </div>
                           </div>
				 		</div>
				 	</div>
				 	<div class="row">
				 		<div class="col-md-12" align="right">
            				 <button type="submit" name="btn_insert_items" id="BTN_INSERT_ITEMS" class="btn btn-primary">
            					Insert Items
            				</button>
				 		</div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">
				<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>
<!--end:: Insert Items Modal-->
@endsection