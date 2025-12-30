<?php
/***********************************************************
 * reportcallbackleads.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 6/22/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
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
        .SwitchDisplay i{
            font-size: 24px;
        }
    </style>
@endsection
@section('plugins')
    <script type="text/javascript" src="{{ url('js/modules/leads.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/callapt.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/callcenter/callbackleads.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Callback Leads</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item DownloadLeads" data-action_type="DOWNLOAD_LEADS" href="#">Download Leads</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <span id="hidden_fields">
                <input type="hidden" name="display_type" value="list" />
            </span>
            <div class="row">
                <div class="col-md-12 order-2 order-xl-1">
                    <div class="form-group row align-items-center">
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <div class="d-flex align-items-center">
                                <!--begin::Input group-->
                                <div class="position-relative w-md-400px me-md-2">
                                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid ps-10" name="lead_name" id="LeadName" value="" placeholder="Lead Name" />
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <div class="d-flex align-items-center">
                                <!--begin::Input group-->
                                <div class="position-relative w-md-400px me-md-2">
                                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid ps-10" name="referred_by" id="LeadRegion" value="" placeholder="Referred By" />
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <div class="d-flex align-items-center">
                                <!--begin::Input group-->
                                <div class="position-relative w-md-400px me-md-2">
                                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid ps-10" name="lead_mobile" id="LeadMobile" value="" placeholder="Lead Mobile" />
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="cl_date" id="CL_DATE" value="" placeholder="Date" />
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <div class="d-flex align-items-center">
                                <!--begin::Input group-->
                                <div class="position-relative w-md-400px me-md-2">
                                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid ps-10" name="sheet_number" id="SheetNumber" value="" placeholder="Sheet Number" />
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Area <span class="required"> * </span> </label>
                                <select name="cl_area" required="required" id="CL_AREA"  tabindex="5"  class="form-control form-select" data-control="select2" data-placeholder="Select Area">
                                    <option value="0">-- Select Area --</option>
                                    <?php foreach ( $lst_areas as $key => $area_info ) { ?>
                                    <option value="<?php echo $area_info->la_area;  ?>"><?php echo $area_info->la_area;  ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Region</label>
                                <div class="col-md-12" id="REGION_DROPDOWN">
                                    <select name="cl_region" required="required"  id="CL_REGION" class="form-control form-select" tabindex="5" data-control="select2" data-placeholder="Select Region">
                                        <option value="0">-- Select Region --</option>
                                        @foreach( $lst_regions as $key => $region_info )
                                            <option value="{{ $region_info->lr_region }}">{{ $region_info->lr_region }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                                <label>Lead Types </label>
                                <select name="cl_lead_types"  id="CL_LEAD_TYPES"  class="form-control form-select" data-control="select2" data-placeholder="Lead Types">
                                    <option value="0">-- Select Types --</option>
                                    <?php foreach ( $lst_lead_types as $key => $type_info ) { ?>
                                    <option value="<?php echo $type_info->lt_id;  ?>"><?php echo $type_info->lt_deal_type;  ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="height:10px">&nbsp;</div>
            </div>
            <div class="row">
                <div style="text-align:right" class='col-md-12'>
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="height:10px">&nbsp;</div>
            </div>
            <div class="row">
                <div style="text-align:center" id="LstLeads" class='col-md-12'>
                    <div class="table-responsive">
                        <div class="table-scroll-wrapper">
                            <table class="table fixed-header-table">
                                <thead class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                    <tr>
                                        <th title="#">#</th>
                                        <th title="index">Index</th>
                                        <th title="RS#"> RS# </th>
                                        <th title="Lead name"> Lead Name </th>
                                        <th title="Area"> Area </th>
                                        <th title="Region"> Region </th>
                                        <th title="Leads Type"> Leads Type </th>
                                        <th title="Salesman"> Salesman </th>
                                        <th title="Telemarketer"> Telemarketer </th>
                                        <th title="Mobile"> Mobile </th>
                                        <th title="Referred By"> Referred by </th>
                                        <th title="Last Call Date"> Last Call Date </th>
                                        <th title="Result" style="cursor: pointer" id="btnAddResult" data-sort="result">Result</th>
                                        <th title="Last Result">Last Result</th>
                                        <th title="Next Call">Next Call</th>
                                        <th title="Notes">Notes</th>
                                    </tr>
                                </thead>
                                <tbody id="LstCBLeadsContainers" class="LstCBLeadsContainers">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="height:50px" align="right"></div>
            </div>
            <div class="row">
                <div class="col-md-12" >
                    <table class="table table-rounded table-striped border gy-7 gs-7">
                        <thead>
                        <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th title="Salesman"> Salesman </th>
                            <th title="Telemarketer"> Telemarketer </th>
                            <th title="Next Call"> Next Call </th>
                            <th title="Next Call"> Result Date </th>
                            <th title="Notes"> Notes </th>
                            <th title="Results"> Results </th>
                        </tr>
                        </thead>
                        <tbody id="LstLeadResults">

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="height:50px" align="right"></div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="AddResultModel" tabindex="-1" role="dialog" aria-labelledby="AddResultModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AddResultModelLabel">
                        Add Lead Result Call
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">
                                            &times;
                                    </span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="frm_add_result" id="FRM_ADD_RESULT">
                                    <span id="hidden_field">
                                            <input type="hidden" name="lr_lead_ids" value="" />
                                              {!! csrf_field() !!}
                                    </span>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label">Next Call Date</label><br/>
                                    <input type="text" name="lr_next_date" id="LR_NEXT_DATE" class="form-control" value="" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Result</label>
                                    <select name="lr_text_result" id="LR_TEXT_RESULT" class="form-control form-select" data-control="select2" data-placeholder="Select Apt Result">
                                        <option value="">-- Select Apt Result --</option>
                                        <?php foreach ( $lst_appt_results as $key => $res_info ) { ?>
                                        <option  value="<?php echo $res_info->ar_id;  ?>"><?php echo $res_info->ar_app_result;  ?></option>
                                        <?php  } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label">Notes</label><br/>
                                    <textarea class="form-control" name="cl_lead_notes" id="LR_TEXT_NOTES" style="width:100%;height:250px;resize:none" ></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" name="btn_result_close" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="button" name="btn_add_result" id="BTN_ADD_RESULT" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
