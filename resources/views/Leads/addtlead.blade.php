<?php
/***********************************************************
addlead.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 22, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>
@extends('layouts.layout',['page_title' => "Leads Management"])



@section('themes')
<style>
    .form-control{
        color:#0000FF !important;
    }
</style>
@endsection


@section('plugins')
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/leads.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/savelead.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Lead - {{ date('Y-m-d') }}</h3>
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
    <form name="frm_save_lead" id="FORM_SAVE_LEAD">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                      <input type="hidden" name="cl_date_creation" value="{{ date('Y-m-d') }}" />
                        <input type="hidden" name="cl_type_items" id="CL_TYPE_ITEMS" value="1" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Lead Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">sheet number <span class="required"> * </span></label>
                                <input type="text" name="cl_sheet_number" id="CL_SHEET_NUMBER" class="form-control" tabindex="1" required="required" maxlength="15" placeholder="sheet number"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Full Name <span class="required"> * </span></label>
                                <input type="text" name="cl_full_name" id="CL_FULL_NAME" class="form-control" required="required"tabindex="2"  maxlength="255" placeholder="Full Name"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Mobile <span class="required"> * </span></label>
                                <input type="text" name="cl_mobile" id="CL_MOBILE" required="required" class="form-control" maxlength="25" tabindex="3"   value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Region <span class="required"> * </span> </label>
                                <input type="text" name="cl_region"  required="required"  id="CL_REGION" class="form-control" maxlength="255" tabindex="3"  value="" />
                            </div>
                        </div>
                          <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Area <span class="required"> * </span> </label>
                                 <select name="cl_area" required="required" id="CL_AREA"  tabindex="5"  class="form-control form-select" data-control="select2" data-placeholder="Select Area">
                                     <option value="">-- Select Area --</option>
                                     <?php foreach ( $lst_areas as $key => $area_info ) { ?>
                                     <option value="<?php echo $area_info->la_area;  ?>"><?php echo $area_info->la_area;  ?></option>
                                     <?php  } ?>
                                 </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Salesman <span class="required"> * </span> </label>
                                <select name="cl_sales_id" required="required" id="CL_SALES_ID"  tabindex="5"  class="form-control form-select" data-control="select2" data-placeholder="Salesman">
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
                                    <select name="cl_telemarketing_id" id="CL_TELEMARKETING_ID" tabindex="6"  class="form-control form-select" data-control="select2" data-placeholder="Select Telemarketing">
                                        <option value="">-- Select Telemarketing --</option>
                                        <?php foreach ( $lst_telemarketing as $key => $user_info ) { ?>
                                                <option {{ Session('user_id') == $user_info->id ? "selected" : "" }} value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                        <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Lead Type <span class="required"> * </span> </label>
                                <select name="cl_lead_type_id" id="CL_LEAD_TYPE_ID"  tabindex="7"  required="required" class="form-control form-select" data-control="select2" data-placeholder="Select Lead Type">
                                        <option value="">-- Select Lead Type --</option>
                                        @foreach ($lst_lead_types as $ind_index => $type_info )
                                                <option value="{{ $type_info->lt_id }}">{{ $type_info->lt_deal_type }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Referred by<span class="required"> * </span></label>
                                <input type="text" name="cl_referred_by" tabindex="8"  required="required" class="form-control" value="" />
                            </div>
                        </div>
                   </div>
                     <div class="row" style="height:50px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_lead" id="BTN_SAVE_LEAD" tabindex="9"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                    <div class="row" style="height:50px;"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                        <thead>
                                                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                        <th title="#"></th>
                                                        <th title="RS#"> RS# </th>
                                                        <th title="Lead name"> Lead Name </th>
                                                        <th title="Area"> Area </th>
                                                        <th title="Region"> Region </th>
                                                        <th title="Salesman"> Salesman </th>
                                                        <th title="Telemarketer"> Telemarketer </th>
                                                        <th title="Mobile"> Mobile </th>
                                                        <th title="Referred by"> Referred by </th>
                                                        <th style="width:2px;" nowrap title="#"> Delete </th>
                                                </tr>
                                        </thead>
                                        <tbody id="LstLeads">

                                        </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" style="height:50px;"></div>

                     <div class="row">
                        <div class="col-md-12 ExistingLeadTabs" style="display: none">
                            <table class="table table-bordered">
                                        <thead>
                                                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                        <th title="#"></th>
                                                        <th title="RS#"> RS# </th>
                                                        <th title="Lead name"> Lead Name </th>
                                                        <th title="Area"> Area </th>
                                                        <th title="Region"> Region </th>
                                                        <th title="Salesman"> Salesman </th>
                                                        <th title="Telemarketer"> Telemarketer </th>
                                                        <th title="Mobile"> Mobile </th>
                                                        <th title="Referred by"> Referred by </th>
                                                        <th style="width:2px;" nowrap title="#"> Delete </th>
                                                </tr>
                                        </thead>
                                        <tbody id="LstExistingLeads">

                                        </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>


@endsection
