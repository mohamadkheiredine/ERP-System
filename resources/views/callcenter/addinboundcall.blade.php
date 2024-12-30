<?php
/***********************************************************
addinboundcall.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 18, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Calls Management"])

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
<script type="text/javascript" src="{{ url('js/modules/inboundcalls.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/callcenter/saveinboundcall.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Call</h3>
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
    <form name="frm_save_inbound" id="FORM_SAVE_INBOUND">
                <div class="form-body">
                     <span id="hidden_fields"> 
                        {!! csrf_field() !!} 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Create Call Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>  
                    <div class="row">
                     <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Client Code</label>
                            <input type="text" name="ic_client_code" id="IC_CLIENT_CODE" class="form-control" required="required"  maxlength="15"  value="" />
                            <input type="hidden" name="fk_customer_id" id="FK_CUSTOMER_ID" class="form-control"  value="" />
                        </div>
                    </div>
                     <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Contract Code</label>
                            <input type="text" name="ic_contract_code" id="IC_CONTRACT_CODE" class="form-control" required="required"  maxlength="15"  value="" /> 
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Client Name</label>
                            <input type="text" name="ca_account_name" id="CA_ACCOUNT_NAME" class="form-control" required="required" readonly="readonly"  maxlength="255"  value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Client Address</label>
                            <input type="text" name="ca_account_address" id="CA_ACCOUNT_ADDRESS" class="form-control" required="required" readonly="readonly"  maxlength="255"  value="" />
                        </div>
                    </div>
                     <div class="col-md-4" style="display:none">
                        <div class="form-group">
                          <label>Result </label>
                          <select name="ic_result_id" id="IC_RESULT_ID"  class="form-control form-select" data-control="select2" data-placeholder="Result">
                                  <option value="">-- Select Result --</option>
                                  <?php foreach ( $lst_results as $key => $result_info ) { ?>
                                          <option value="<?php echo $result_info->cr_id;  ?>"><?php echo $result_info->cr_result_title;  ?></option>
                                  <?php  } ?>
                          </select>
                      </div>
                  </div>
                    <div class="col-md-4" style="display:none">
                        <div class="form-group">
                          <label>Salesman </label>
                          <select name="ic_sales_id" required="required" id="IC_SALES_ID"  class="form-control form-select" data-control="select2" data-placeholder="Salesman">
                                  <option value="">-- Select Salesman --</option>
                                  <?php foreach ( $lst_sales as $key => $user_info ) { ?>
                                          <option value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                  <?php  } ?>
                          </select>
                      </div>
                  </div>
                    <div class="col-md-4">
                        <label class="control-label">Technician</label>
                        <select name="ic_technician_id" id="IC_TECHNICIAN_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Technician">
                               <option value="">-- Select Technician --</option>
                               @foreach ( $lst_technicians as $key => $user_info )
                                       <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                               @endforeach
                       </select>
                    </div>
                   <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Bill Situation</label><br/>
                                <input type="text" name="ic_bill_situation" id="IC_BILL_SITUATION" class="form-control" maxlength="45" value="" />
                             </div>
                        </div>
                         <div class="col-md-4" style="display:none">
                              <div class="form-group" >
                                    <label class="control-label"> Product  </label>
                                     <select name="ic_customer_product" id="IC_CUSTOMER_PRODUCT"  class="form-control form-select" data-control="select2" data-placeholder="Select Product">
                                            <option value=""> -- Select Product -- </option>
                                            @foreach($lst_products as $key => $product_info)
                                                    <option value="{{ $product_info->p_id }}" data-ref_id="{{ $product_info->p_product_ref }}" >{{ $product_info->p_product_ref }}&nbsp;-&nbsp;{{ $product_info->p_product_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div> 
                       <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Product Serial Number</label><br/>
                                <input type="text" name="ic_product_machine_id" id="IC_PRODUCT_MACHINE_ID" class="form-control" maxlength="45" value="" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                 <br/>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="ic_under_warranty"  id="IC_UNDER_WARRANTY" checked="checked"  value="1"  />
                                      <span class="form-check-label fw-semibold text-muted">
                                         Under Warranty
                                      </span>
                                  </label>  
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Warranty Expiry </label><br/>
                                <input type="text" name="ic_warranty_expiry"  id="IC_WARRANTY_EXPIRY" class="form-control" value="{{  date('Y-m-d', strtotime('+10 years')) }}" />
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Call Date</label><br/>
                                <input type="text" name="ic_call_date" id="IC_CALL_DATE" class="form-control" value="{{ date('Y-m-d') }}" />
                             </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Call Time</label><br/>
                                <input type="text" name="ic_call_start_time" id="IC_CALL_START_TIME" class="form-control" value="{{ date('H:i:s') }}" />
                             </div>
                        </div>
                        <div class="col-md-4"> 
                            <label class="control-label">Maintenance Type</label>
                            <select name="ic_maintenance_type" required id="IC_MAINTENANCE_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Maintenance Type">
                                   <option value="">-- Select Telemarketing --</option>
                                    <?php foreach ( $lst_maint_types as $key => $type_info ) { ?>
                                            <option value="{{ $type_info->mt_id }}">{{ $type_info->mt_type }}</option>
                                    <?php  } ?>
                           </select>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                 <br/>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" name="ic_issue_resolved" id="IC_ISSUE_RESOLVED"  value="1"  />
                                      <span class="form-check-label fw-semibold text-muted">
                                         Issue Resolved
                                      </span>
                                  </label>  
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Problem</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="IC_CALL_OUTCOME"  class="form-control" name="ic_call_outcome"  cols=""></textarea>
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Notes</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="IC_NOTES"  class="form-control" name="ic_notes"  cols=""></textarea>
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Result </label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="IC_ITEM_PROBLEM"  class="form-control" name="ic_item_problem"  cols=""></textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_call" id="BTN_SAVE_CALL"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection