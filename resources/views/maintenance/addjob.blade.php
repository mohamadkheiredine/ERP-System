<?php
/***********************************************************
addjob.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 5, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
 "rand_barcode" => $rand_barcode,
            "bar_code_png" => $bar_code_png,
***********************************************************/


?>
@extends('layouts.layout',['page_title' => "Jobs Management"])

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
    <script type="text/javascript" src="{{ url('default/assets/plugins/jquery-scanner-detection/jquery.scannerdetection.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/jobs.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/maintenance/savejobs.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Add New Job</h3>
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
             <form name="frm_save_jobs" id="FORM_SAVE_JOBS">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                       <input type="hidden" name="lst_job_items" id="LST_JOB_ITEMS" value="" />
                       <input type="hidden" name="bare_code_png" id="bare_code_png" value="{{ $bar_code_png }}" />
                       <input type="hidden" name="bare_code" id="BARE_CODE" value="{{ $rand_barcode }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Job Management Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                    	<div class="col-md-4">
                          	<div class="form-group">
                                 <img id="BARCODE_IMG" src="data:image/png;base64,{{ $bar_code_png }}" alt="barcode" height="50" width="150"   /><br/>
                                 <label class='lblbarcode'>{{ $rand_barcode }}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Job Code <span class="required"> * </span></label>
                                <input type="text" tabindex="1" name="j_job_code" id="J_JOB_CODE" class="form-control" required="required" maxlength="15" readonly="readonly" value="{{ $job_code }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Due Date <span class="required"> * </span></label>
                                <input type="text" tabindex="2"  name="j_due_date" id="J_DUE_DATE" class="form-control" required="required" maxlength="10" readonly="readonly"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Job Title <span class="required"> * </span></label>
                                <input type="text" tabindex="3"  name="j_job_title" id="J_JOB_TITLE" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Job Status</label>
                                <select class="bs-select form-control" tabindex="4" id="J_JOB_STATUS_ID" name="j_job_status_id">
                        			<option value="0">-- Select Job Status --</option>
                                    @foreach($lst_job_status as $index => $js_info)
                                      <option value="{{ $js_info->js_id }}">{{ $js_info->js_status_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Cost Currency</label>
                                <select class="bs-select form-control" id="J_CURRENCY_ID"  tabindex="5" name="j_currency_id">
                        			<option value="0">-- Select Currency --</option>
                                    @foreach($lst_currencies as $index => $currency_info)
                                      <option value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code }}&nbsp;-&nbsp;{{ $currency_info->cc_currency_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">User Responsible</label>
                                <select class="bs-select form-control" id="J_USER_ID" tabindex="6"  name="j_user_id">
                        			<option value="0">-- Select User --</option>
                                    @foreach($lst_users as $index => $user_info)
                                      <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Customer &nbsp;<a href="#" id="ADD_NEW_CUSTOMER" style="text-decoration: none;" ><i class="flaticon-add-circular-button"></i></a>&nbsp; </label>
                                <select class="bs-select form-control" id="J_CUSTOMER_ID"  tabindex="7"  name="j_customer_id">
                        			<option value="0">-- Select Customer --</option>
                                    @foreach($lst_customers as $index => $customer_info)
                                      <option value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Vendor &nbsp;<a href="#" id="ADD_NEW_VENDOR" style="text-decoration: none;" ><i class="flaticon-add-circular-button"></i></a>&nbsp;</label>
                                <select class="bs-select form-control" id="J_VENDOR_ID"  tabindex="8"  name="j_vendor_id">
                        			<option value="0">-- Select Vendor --</option>
                                    @foreach($lst_vendors as $index => $vendor_info)
                                      <option value="{{ $vendor_info->iv_id }}">{{ $vendor_info->iv_vendor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                                <label class="control-label"> Item Serialnumber</label>
                                <input type="text" name="j_product_serial_number"  tabindex="9"  id="J_PRODUCT_SERIAL_NUMBER" class="form-control"  maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Job Description</label>
                                <textarea class="form-control" style="width:100%;height: 250px;"  tabindex="10"  name="j_job_description" id="J_JOB_DESCRIPTION"></textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                   <div class="row">
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
    									<div class="col-md-12 ProductInformation">
    									</div>
    								</div>
    								<div class="row">
    									<div class="col-md-12" style="height:10px">&nbsp;</div>
    								</div>
    								<div class="row">
    									<div class="col-md-12">
    										<table class="table m-table m-table--head-separator-danger">
											<thead>
												<tr>
													<th>Maintenance Barecode</th>
													<th>Maintenance Date</th>
													<th>Maintenance Label</th>
													<th>Status</th>
												</tr>
											</thead>
											<tbody class="ProductLog">
												<!-- <tr>
													<th scope="row">1</th>
													<td>Jhon</td>
													<td>Stone</td>
													<td>@jhon</td>
												</tr> -->
											</tbody>
										</table>
'
    									</div>
    								</div>
    							</div>
    						</div>
                   		</div>
                   </div>
                   <div class="row" style="height:5px;"></div>
                   <div class="row">
                   	<div class="col-md-12">                   		
                        <ul class="nav nav-tabs" role="tablist">
                        	<li class="nav-item">
                        		<a class="nav-link active" id="LinkTabItems" data-toggle="tab" href="#tabItems">
                        			Items
                        		</a>
                        	</li>
                        </ul>
                        <div class="tab-content">
                        	<div class="tab-pane active" id="tabItems" role="tabpanel">
                        		<div class="row">
                    				<div class="col-md-12 col-lg-12">
                    					<table class="table m-table m-table--head-separator-primary">
											<thead>
												<tr>
													<th>#</th>
													<th>Item Name</th> 
													<th>Cost</th>
													<th style="width:4px;white-space: nowrap;">Delete</th>
												</tr>
											</thead>
											<tbody class="LstJobItems" id="JOB_ITEMS"></tbody>
										</table>
                    				</div>
                        		</div>
                        		<div class="row">
                        			<div class="col-md-12" align="right">
                        				<button type="button" name="btn_add_item" id="BTN_ADD_ITEM" class="btn btn-danger" >Add Item</button>
                        			</div>
                        		</div>
                        	</div>
                        </div>
                   	</div>
                   </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_job" id="BTN_SAVE_JOB"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>
<!-- Insert Product -->
<div class="modal fade" id="InserItems" tabindex="-1" role="dialog" aria-labelledby="InserItemsModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InserItemsModalLabel">
					Insert Item
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_job_items" id="FRM_JOB_ITEMS" method="post"  enctype="multipart/form-data"> 
				    {!! csrf_field() !!}
				 	<div class="row">
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> Item Types </label><br/>
                                     <select class="bs-select form-control" name="mj_job_maintenance" id="MJ_JOB_MAINTENANCE"  style="width:100%" data-actions-box="true">
                                            <option value=""> --Select Item -- </option>
                                            <option value="1"> Products </option>
                                            <option value="2"> Services </option>
                                    </select>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group">
				 			  	 <label class="control-label"> Item </label><br/>
				 			  	 <div class="ItemsDropdown"></div>
				 			  </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> Item barecode </label><br/>
                                     <input type="text" name="mj_item_barecode" id="MJ_ITEM_BARECODE" class="form-control"  value="" />
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> Item Cost </label><br/>
                                     <input type="text" name="mj_item_cost" id="MJ_ITEM_COST" class="form-control"  value="0" />&nbsp;&nbsp;<span class="ItemCurrency"></span>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> Quanity </label><br/>
                                     <input type="number" name="bi_quanity" class="form-control" max="99999999" min="1" step="1" value="1" />
                                </div>
				 		</div>
				 		<div class="col-md-12" align="right">
				 			<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
            					Close
            				</button>
            				<button type="button" name="btn_insert_item" id="BTN_INSERT_ITEM" class="btn btn-primary">
            					Insert
            				</button>
				 		</div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">
				
			</div>
		</div>
	</div>
</div>
<!-- End Insert Product -->
<!-- Start Add Customer Model -->
<div class="modal fade" id="InsertCustomers" tabindex="-1" role="dialog" aria-labelledby="InsertCustomerModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InsertCustomerModalLabel">
					Insert Customers
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_save_customer" id="FRM_SAVE_CUSTOMER">
                    <span id="hidden_fields">
                   		{!! csrf_field() !!}
                    </span>
                    <div class="col-md-12" align="left">
                		<label>Customer Logo </label>
                    </div>
                    <div class="col-md-4">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="" >
                                    <img id="CUSTOMER_LOGO_PIC" width="100" src="" alt="" /> </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" > </div>
                            </div>
                     </div>
                     <div class="col-md-8">
                        <div class="clearfix margin-top-10">
                                <span class="btn default btn-file">
                                    <span class="fileinput-new"> Select image </span><br/>
                                    <input type="file" name="ic_avatar_pic" id="IC_AVATAR_PIC" /> 
                                </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                     	<div class="form-group">
                            <label class="control-label"> Customer Name <span class="required"> * </span></label>
                            <input type="text" name="ic_customer_name" id="IC_CUSTOMER_NAME" class="form-control" required="required" maxLength="255" value="" />
                        </div>
                     </div>
                      <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Customer Address</label>
                            <input type="text" name="ic_customer_address" id="IC_CUSTOMER_ADDRESS" class="form-control" maxLength="500" value="" />
                        </div>
                      </div>
                       <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Customer Email</label>
                            <input type="email" name="ic_customer_email" id="IC_CUSTOMER_EMAIL" class="form-control" maxLength="500" value="" />
                        </div>
                       </div>
                        <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Customer Website</label>
                            <input type="url" name="ic_customer_website" id="IC_CUSTOMER_WEBSITE" class="form-control" maxLength="500" value="" />
                        </div>
                        </div>
                         <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Customer Phone</label>
                            <input type="text" name="ic_customer_phone" id="IC_CUSTOMER_PHONE" class="form-control" maxLength="500" value="" />
                        </div>
                        </div>
                         <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Customer Mobile</label>
                            <input type="text" name="ic_customer_mobile" id="IC_CUSTOMER_MOBILE" class="form-control" maxLength="500" value="" />
                        </div>
                        </div>
                         <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" name="btn_save_customer" id="BTN_SAVE_CUSTOMER" class="btn btn-primary btn-block btn-lg">Save Customer</button>
                            </div>
                        </div>

                           
                </form>
			</div>
		</div>
	</div>
</div>

<!-- End Add Customer Model -->
<!-- Start Add Vendor Model -->
<div class="modal fade" id="InsertVendors" tabindex="-1" role="dialog" aria-labelledby="InsertVendorModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InsertVendorModalLabel">
					Insert Vendors
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_save_vendor" id="FRM_SAVE_VENDOR">
                    <span id="hidden_fields">
                   		{!! csrf_field() !!}
                    </span>
                    <div class="col-md-12" align="left">
                		<label>Vendor Logo </label>
                    </div>
                    <div class="col-md-4">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="" >
                                    <img id="VENDOR_LOGO_PIC" width="100" src="" alt="" /> </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" > </div>
                            </div>
                     </div>
                     <div class="col-md-8">
                        <div class="clearfix margin-top-10">
                                <span class="btn default btn-file">
                                    <span class="fileinput-new"> Select image </span><br/>
                                    <input type="file" name="iv_avatar_pic" id="IV_AVATAR_PIC" /> 
                                </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                     	<div class="form-group">
                            <label class="control-label"> Vendor Name <span class="required"> * </span></label>
                            <input type="text" name="iv_vendor_name" id="IC_VENDOR_NAME" class="form-control" required="required" maxLength="255" value="" />
                        </div>
                     </div>
                      <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Vendor Address</label>
                            <input type="text" name="iv_vendor_address" id="IC_VENDOR_ADDRESS" class="form-control" maxLength="500" value="" />
                        </div>
                      </div>
                       <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Vendor Email</label>
                            <input type="email" name="iv_vendor_email" id="IC_VENDOR_EMAIL" class="form-control" maxLength="500" value="" />
                        </div>
                       </div>
                        <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Vendor Website</label>
                            <input type="url" name="iv_vendor_website" id="IC_VENDOR_WEBSITE" class="form-control" maxLength="500" value="" />
                        </div>
                        </div>
                         <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Vendor Phone</label>
                            <input type="text" name="iv_vendor_phone" id="IC_VENDOR_PHONE" class="form-control" maxLength="500" value="" />
                        </div>
                        </div>
                         <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Vendor Mobile</label>
                            <input type="text" name="iv_vendor_mobile" id="IC_VENDOR_MOBILE" class="form-control" maxLength="500" value="" />
                        </div>
                        </div>
                         <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" name="btn_save_vendor" id="BTN_SAVE_VENDOR" class="btn btn-primary btn-block btn-lg">Save Vendor</button>
                            </div>
                        </div>
                </form>
			</div>
		</div>
	</div>
</div>
<!-- End Add Vendor Model -->
@endsection