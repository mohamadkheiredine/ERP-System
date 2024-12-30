<?php
/***********************************************************
editcase.blade.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 2, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 

Page Description :
{Enter page description Here}
***********************************************************/



?>


@extends('layouts.layout',['page_title' => "Call Center Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/mcases.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/callcenter/savemaintenancecase.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Existing Maintenance Schedule Case</h3>
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
    <form name="frm_save_case" id="FORM_SAVE_CASE">
                <div class="form-body">
                     <span id="hidden_fields"> 
                        {!! csrf_field() !!}
                        <input type="hidden" name="cc_id" value="{{ $case_info->cc_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Maintenance Case Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>  
                    <div class="row">
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Maintenance Number <span class="required"> * </span></label><br/>
                                <input type="text" name="cc_case_code" id="CC_CASE_CODE" required="required" maxlength="15" class="form-control" value="{{ $case_info->cc_case_code }}" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Doc Number <span class="required"> * </span></label><br/>
                                <input type="text" name="cc_doc_number" id="CC_DOC_NUMBER" maxlength="15" class="form-control" value="{{ $case_info->cc_doc_number }}" />
                             </div>
                        </div>
                       <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Contract Code</label>
                                <input type="text" name="cc_contract_code" id="CC_CONTRACT_CODE" class="form-control"  maxlength="15"  value="{{ $case_info->cc_contract_code }}" /> 
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Client Code</label>
                                <input type="text" name="cc_client_code" id="CC_CLIENT_CODE" class="form-control"  maxlength="15"  value="{{ $case_info->cc_client_code }}" />
                                <input type="hidden" name="cc_client_id" id="CC_CLIENT_ID" class="form-control"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Client Name</label>
                                <input type="text" name="ca_account_name" id="CA_ACCOUNT_NAME" class="form-control" readonly="readonly"  maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Client Address</label>
                                <input type="text" name="ca_account_address" id="CA_ACCOUNT_ADDRESS" class="form-control" readonly="readonly"  maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Phone Number</label>
                                <input type="text" name="cc_phone_number" id="CC_PHONE_NUMBER" class="form-control"  maxlength="25"  value="{{ $case_info->cc_phone_number }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Comission</label>
                                <input type="text" name="cc_comission" id="CC_COMISSION" class="form-control"  maxlength="25"  value="{{ $case_info->cc_comission }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Machine Serial Number</label>
                                <input type="text" name="cc_serial_number" id="CC_SERIAL_NUMBER" class="form-control"  maxlength="255"  value="{{ $case_info->cc_serial_number }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Visit Type</label>
                                <input type="text" name="cc_visit_type" id="CC_VISIY_TYPE" class="form-control"  maxlength="255"  value="{{ $case_info->cc_visit_type }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                              <label>Maintenance Type <span class="required"> * </span>  </label> 
                                  <select name="cc_maint_type_id" id="CC_MAINT_TYPE_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Maintenance Type">
                                      <option value="">-- Select Maintenance Type --</option>
                                      <?php foreach ( $lst_maint_types as $key => $type_info ) { ?>
                                              <option {{ $case_info->cc_maint_type_id == $type_info->mt_id ? "selected" : "" }} value="{{ $type_info->mt_id }}">{{ $type_info->mt_type }}</option>
                                      <?php  } ?>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-4">
                            <div class="form-group">
                              <label>Telemarketing <span class="required"> * </span>  </label> 
                                  <select name="cc_telemarketing_id" id="CC_TELEMARKETING_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Telemarketing">
                                      <option value="">-- Select Telemarketing --</option>
                                      <?php foreach ( $lst_telemarketing as $key => $user_info ) { ?>
                                              <option  {{ $case_info->cc_telemarketing_id == $user_info->id ? "selected" : "" }} value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                      <?php  } ?>
                              </select>
                          </div>
                      </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                  <label class="control-label"> Technician <span class="required"> * </span> </label>
                                   <select name="cc_technician_id" id="CC_TECHNICIAN_ID"   class="form-control form-select" data-control="select2" data-placeholder="Select Technician">
                                          <option value=""> -- Select Agent -- </option>
                                          @foreach($lst_technicians as $key => $tech_info)
                                                  <option {{ $case_info->cc_technician_id == $tech_info->id ? "selected" : "" }} value="{{ $tech_info->id }}">{{ $tech_info->u_fullname }}</option>
                                          @endforeach
                                  </select>
                              </div>
                        </div> 
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Case Priority <span class="required"> * </span> </label>
                                     <select name="cc_priority_level" id="CC_PRIORITY_LEVEL"  class="form-control form-select" data-control="select2" data-placeholder="Select Priority Level">
                                            <option value=""> -- Select Priority Level -- </option> 
                                            <option {{ $case_info->cc_priority_level == 'low' ? "selected" : "" }} value="low"> Low </option> 
                                            <option {{ $case_info->cc_priority_level == 'medium' ? "selected" : "" }} value="medium"> Medium </option> 
                                            <option {{ $case_info->cc_priority_level == 'high' ? "selected" : "" }} value="high"> High </option> 
                                            <option {{ $case_info->cc_priority_level == 'urgent' ? "selected" : "" }} value="urgent"> Urgent </option> 
                                    </select>
                                </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                  <label class="control-label"> Case Status <span class="required"> * </span> </label>
                                   <select name="cc_case_status" id="CC_CASE_STATUS"   class="form-control form-select" data-control="select2" data-placeholder="Select Case Status">
                                          <option value=""> -- Select Status -- </option>
                                          @foreach($lst_statuses as $key => $status_info)
                                                  <option {{ $case_info->cc_case_status == $status_info->cc_id ? "selected" : "" }}  value="{{ $status_info->cc_id }}">{{ $status_info->cc_status_title }}</option>
                                          @endforeach
                                  </select>
                              </div>
                        </div> 
                       <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Case Date <span class="required"> * </span></label><br/>
                                <input type="text" name="cc_case_date" required="required" id="CC_CASE_DATE" class="form-control" value="{{ $case_info->cc_case_date }}" />
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Case Time <span class="required"> * </span></label><br/>
                                <input type="text" name="cc_case_time" required="required" id="CC_CASE_TIME" class="form-control" value="{{ $case_info->cc_case_time }}" />
                             </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Maintenance Types</label>
                           <select name="cc_maint_type_id" id="CC_MAIN_TYPE_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Main Type">
                                  <option value="">-</option>
                                  @foreach ( $lst_maint_types as $key => $type_info )
                                          <option {{ $case_info->cc_maint_type_id == $type_info->mt_id ? "selected" : "" }} value="{{ $type_info->mt_id }}">{{ $type_info->mt_type }}</option>
                                  @endforeach
                          </select>
                       </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Case Price Total</label><br/>
                                <input type="text" name="cc_case_price" id="CC_CASE_PRICE" class="form-control" value="{{ $case_info->cc_case_price }}" />
                             </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                  <label class="control-label"> Currency <span class="required"> * </span> </label>
                                   <select name="cc_currency_id" id="CC_CURRENCY_ID"   class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                          <option value=""> -- Select Currency -- </option>
                                          @foreach($lst_currencies as $key => $curr_info)
                                                  <option {{ $case_info->cc_currency_id == $curr_info->cc_id ? "selected" : "" }} value="{{ $curr_info->cc_id }}">{{ $curr_info->cc_currency_code }}&nbsp;{{ $curr_info->cc_currency_name }}</option>
                                          @endforeach
                                  </select>
                              </div>
                        </div> 
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Case Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CC_CASE_DESCRIPTION"  class="form-control" name="cc_case_description"  cols="">{{ $case_info->cc_case_description }}</textarea>
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Resolution Notes</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CC_RESOLUTION_NOTES"  class="form-control" name="cc_resolution_notes"  cols="">{{ $case_info->cc_resolution_notes }}</textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_case" id="BTN_SAVE_CASE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection