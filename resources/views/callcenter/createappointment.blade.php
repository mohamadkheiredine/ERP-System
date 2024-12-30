<?php
/***********************************************************
createappointment
Product : titanerp
Version : 1.0
Release : 1
Date Created : Oct 2, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/
?>

@extends('layouts.layout',['page_title' => "Call center Management"])

@section('themes')
<style>
    textarea{
        resize:none;
    }
    div#kt_docs_card_collapsible {
        padding:10px;
    }
        .form-control{
        color:#0000FF !important;
    }
</style>
@endsection

@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/callapt.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/callcenter/createcallapt.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Create Call Appointment</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
                  <li><a class="dropdown-item" data-action_type="IMPORT" href="#"></a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form name="frm_create_apt" id="FRM_CREATE_APT">
             <div class="form-body">
                  <span id="hidden_fields">
                   {!! csrf_field() !!}
                   <input type="hidden" name="lead_id" value="{{ $lead_id }}" />
                   <input type="hidden" name="sales_id" value="{{ $leads_info->cl_sales_id }}" />
                   <input type="hidden" name="lead_type_id" value="{{ $leads_info->cl_lead_type_id }}" />
                   <input type="hidden" name="telemarketing_id" value="{{ $leads_info->cl_telemarketing_id }}" />
                 </span>
                 <div class="alert alert-success" style="display:none">
                    <strong>Success!</strong> Appointment Information is saved successfully!
                 </div>
                 <div class="alert alert-danger" style="display:none">
                   <strong>Error!</strong> You have some form errors. Please check below.
                 </div>
                 <div class="row">
                     <div class="col-md-12">
                         <div class="card shadow-sm">
                                  <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible">
                                        <h3 class="card-title">Create New Appointment</h3>
                                        <div class="card-toolbar rotate-180">
                                            <i class="ki-duotone ki-down fs-1"></i>
                                        </div>
                                    </div>
                                    <div id="kt_docs_card_collapsible" class="collapse show">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Date </label>
                                                <input type="text" class="form-control" readonly="readonly" name="ca_apt_date" id="CA_APT_DATE" value="{{ date("Y-m-d") }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Time </label>
                                                <input type="text" class="form-control" name="ca_apt_time" id="CA_APT_TIME" value="{{ date("H:i:s") }}" maxlength="15" />
                                            </div>
                                        </div>
                                          <div class="col-md-4">
                                                <div class="form-group">
                                                  <label>Salesman <span class="required"> * </span> </label>
                                                  <select name="cl_sales_id" required="required" id="CL_SALES_ID"  class="form-control form-select" data-control="select2" data-placeholder="Salesman">
                                                          <option value="">-- Select User --</option>
                                                          <?php foreach ( $lst_sales as $key => $user_info ) { ?>
                                                                  <option value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                                          <?php  } ?>
                                                  </select>
                                              </div>
                                          </div> 
                                          <div class="col-md-4">
                                                <div class="form-group">
                                                  <label>Telemarketing <span class="required"> * </span>  </label> 
                                                      <select name="cl_telemarketing_id" id="CL_TELEMARKETING_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Telemarketing">
                                                          <option value="">-- Select Telemarketing --</option>
                                                          <?php foreach ( $lst_telemarketing as $key => $user_info ) { ?>
                                                                  <option {{ Session('user_id') == $user_info->id ? "selected" : "" }} value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                                          <?php  } ?>
                                                  </select>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                                <div class="form-group">
                                                  <label>Lead Types</label> 
                                                      <select name="cl_lead_type" id="CL_LEAD_TYPE" class="form-control form-select" data-control="select2" data-placeholder="Select Lead Type">
                                                          <option value="">-- Select Lead Type --</option>
                                                          <?php foreach ( $lst_lead_types as $key => $type_info ) { ?>
                                                                  <option  value="<?php echo $type_info->lt_id;  ?>"><?php echo $type_info->lt_deal_type;  ?></option>
                                                          <?php  } ?>
                                                  </select>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                                <div class="form-group">
                                                  <label>Result</label> 
                                                      <select name="ca_apt_result" id="CA_APT_RESULT" class="form-control form-select" data-control="select2" data-placeholder="Select Apt Result">
                                                          <option value="">-- Select Apt Result --</option>
                                                          <option value="0">Pending</option>
                                                          <?php foreach ( $lst_appt_results as $key => $res_info ) { ?>
                                                                  <option  value="<?php echo $res_info->ar_id;  ?>"><?php echo $res_info->ar_app_result;  ?></option>
                                                          <?php  } ?>
                                                  </select>
                                              </div>
                                          </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Full Name </label>
                                                <input type="text" class="form-control" name="cl_full_name" id="CL_FULL_NAME" readonly="readonly" value="{{ $leads_info->cl_first_name }}&nbsp;{{ $leads_info->cl_last_name }}" maxlength="255" />
                                            </div>
                                        </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Phone </label>
                                                <input type="text" class="form-control" name="cl_phone" readonly="readonly" id="CL_PHONE" value="{{ $leads_info->cl_mobile }}" maxlength="45" />
                                            </div>
                                        </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                                <label>With </label>
                                                <input type="text" class="form-control" name="ca_apt_with" id="CA_APT_WITH" value="" maxlength="255" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Area </label>
                                                <input type="text" class="form-control" name="cl_area" id="CL_AREA" readonly="readonly" value="{{ $leads_info->cl_area }}" maxlength="255" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Address </label>
                                                <input type="text" class="form-control" name="ca_lead_address" id="CA_LEAD_ADDRESS"  value="{{ $leads_info->cl_region }}" maxlength="255" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Referred By </label>
                                                <input type="text" class="form-control" name="cl_referred_by" id="CL_REFERRED_BY" readonly="readonly" value="{{ $leads_info->cl_referred_by }}" maxlength="255" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Number of Leads </label>
                                                <input type="text" class="form-control" name="ca_nbr_leads" id="CA_NBR_LEADS" value="0" maxlength="5" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <br/>
                                               <label class="form-check form-switch form-check-custom form-check-solid">
                                                     <input class="form-check-input" type="checkbox" name="ca_lead_confirm"  id="CA_LEAD_CONFIRM"  value="1"  />
                                                     <span class="form-check-label fw-semibold text-muted">
                                                        Confirmed
                                                     </span>
                                                 </label>  
                                            </div>
                                       </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Notes </label>
                                                <textarea name="ca_apt_notes" id="CA_APT_NOTES" class="form-control" style="width:100%;height:250px"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Details </label>
                                                <textarea name="ca_apt_details" id="CA_APT_DETAILS" class="form-control" style="width:100%;height:250px"></textarea>
                                            </div>
                                        </div>
                                        <div style="height:50px" class="col-md-12"></div>
                                        <div style="text-align: right" class="col-md-12">
                                            <button name="btn_save_app" class="btn btn-primary" type="submit">Save Info</button>
                                            <button name="btn_reset" class="btn btn-danger" type="reset">Reset</button>
                                        </div>
                                    </div> 
                                </div> 
                            </div>
                            <div class="row">
                                <div style="height:50px" class="col-md-12"></div>
                            </div>
                         <div class="row">
                                        <div class="col-md-8">
                                            <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                                <thead>
                                                                        <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                                                <th title="#"></th>
                                                                                <th title="Date"> Date </th>
                                                                                <th title="Time"> Time </th>
                                                                                <th title="Full Name"> Lead Name </th>
                                                                                <th title="Area"> Area </th>
                                                                                <th title="Salesman"> Salesman </th>
                                                                                <th title="Result"> Result </th> 
                                                                                <th title="Telemarketing"> Telemarketing </th> 
                                                                                <th title="Confirmed"> Confirmed </th> 
                                                                        </tr>
                                                                </thead>
                                                                <tbody id="LstLeadAppts">

                                                                </tbody>
                                                    </table>
                                            </div>
                                        </div>
                                    </div>
                     </div>
                 </div>
             </div>
        </form>
    </div>
</div>
@endsection