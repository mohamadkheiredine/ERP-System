<?php
/***********************************************************
 * editproject.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/25/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Projects Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/projects.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/pmp/saveproject.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Project Management > Edit Existing Project</h3>
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
            <form name="frm_save_project" id="FORM_SAVE_PROJECT">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                         <input type="hidden" name="pp_id" value="{{ $project_info->pp_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Project Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Project Code <span class="required"> * </span></label>
                                <input type="text" name="pp_project_code" id="PP_PROJECT_CODE" class="form-control" readonly required="required" maxlength="25"  value="{{ $project_info->pp_project_code }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Project Name <span class="required"> * </span></label>
                                <input type="text" name="pp_project_name" id="PP_PROJECT_NAME" class="form-control" required="required" maxlength="255"  value="{{ $project_info->pp_project_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Project Type :&nbsp;</label><br/>
                                <select name="fk_project_type_id" id="FK_PROJECT_TYPE_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Project Type">
                                    <option value="">-- Select Type --</option>
                                    @foreach ( $lst_project_types as $key => $type_info )
                                        <option {{ $project_info->fk_project_type_id == $type_info->pt_id ? "selected" : "" }} value="{{ $type_info->pt_id }}">{{ $type_info->pt_type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Company :&nbsp;</label><br/>
                                <select name="fk_company_id" id="FK_COMPANY_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Company Branch">
                                    <option value="">-- Select Branch --</option>
                                    @foreach ( $lst_companies as $key => $company_info )
                                        <option {{ $project_info->fk_company_id == $company_info->cd_id ? "selected" : "" }} value="{{ $company_info->cd_id }}">{{ $company_info->cd_company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Project Manager :&nbsp;</label><br/>
                                <select name="fk_project_manager_id" id="FK_PROJECT_MANAGER_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Project Manager">
                                    <option value="">-- Select Manager --</option>
                                    @foreach ( $lst_project_managers as $key => $user_info )
                                        <option {{ $project_info->fk_project_manager_id == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Start Date</label>
                                <input type="text" name="pp_start_date" id="PP_START_DATE" class="form-control" readonly required="required" maxlength="25"  value="{{ $project_info->pp_start_date }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> End Date</label>
                                <input type="text" name="pp_end_date" id="PP_END_DATE" class="form-control" required="required" maxlength="25"  value="{{ $project_info->pp_end_date }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Estimated End Date</label>
                                <input type="text" name="pp_estimated_end_date" id="PP_ESTIMATED_END_DATE" class="form-control" required="required" maxlength="25"  value="{{ $project_info->pp_estimated_end_date }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Project Status :&nbsp;</label><br/>
                                <select name="pp_status_id" id="PP_STATUS_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Project Status">
                                    <option value="">-- Select Status --</option>
                                    @foreach ( $lst_project_statuses as $key => $status_info )
                                        <option {{ $project_info->pp_status_id == $status_info->ps_id ? "selected" : "" }} value="{{ $status_info->ps_id }}">{{ $status_info->ps_status_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Cost Center :&nbsp;</label><br/>
                                <select name="pp_cost_center_id" id="PP_COST_CENTER_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Cost Center">
                                    <option value="">-- Select Cost Center --</option>
                                    @foreach ( $lst_cost_centers as $key => $cc_info )
                                        <option {{ $project_info->pp_cost_center_id == $cc_info->ac_id ? "selected" : "" }} value="{{ $cc_info->ac_id }}">{{ $cc_info->ac_cost_center_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Budget</label>
                                <input type="text" name="pp_budget" id="PP_BUDGET" class="form-control" required="required" maxlength="25"  value="{{ $project_info->pp_budget }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Currency :&nbsp;</label><br/>
                                <select name="pp_currency_id" id="PP_CURRENCY_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                    <option value="">-- Select Currency --</option>
                                    @foreach ( $lst_currencies as $key => $cc_info )
                                        <option {{ $project_info->pp_currency_id == $cc_info->cc_id ? "selected" : "" }} value="{{ $cc_info->cc_id }}">{{ $cc_info->cc_currency_code }}&nbsp;-&nbsp;{{ $cc_info->cc_currency_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <br/>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input {{ $project_info->pp_is_template == 1 ? "checked='checked'" : "" }} class="form-check-input" type="checkbox" name="pp_is_template" id="PP_IS_TEMPLATE"  value="1"  />
                                    <span class="form-check-label fw-semibold text-muted">
                                           Is Template
                                        </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Project Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="PP_DESCRIPTION"  class="form-control" name="pp_description"  cols="">{{ $project_info->pp_description }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Project Notes</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="PP_NOTES"  class="form-control" name="pp_notes"  cols="">{{ $project_info->pp_notes }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#TabTeams">Project Teams</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#TabPhases">Project Phases</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#TabJobs">Project Jobs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#TabTasks">Project Tasks</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="TabTeams" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr class="fw-bold fs-6 text-gray-800">
                                                <th>#</th>
                                                <th>Id</th>
                                                <th>Team</th>
                                                <th>Allocation</th>
                                                <th>Start Date</th>
                                                <th>End date</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody class="LstProjectTeams">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" align="right">
                                            <button type="button" name="btn_add_team" id="BTN_ADD_TEAM" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#modal_link_teams">Add Team</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="TabPhases" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr class="fw-bold fs-6 text-gray-800">
                                                <th>Phase Code</th>
                                                <th>Department</th>
                                                <th>Phase Name</th>
                                                <th>Planned Start Date</th>
                                                <th>Planned End Date</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody class="LstProjectPhases">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" align="right">
                                            <button type="button" name="btn_add_phase" id="BTN_ADD_PHASE" class="btn btn-primary">Add Phase</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="TabJobs" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr class="fw-bold fs-6 text-gray-800">
                                                <th>Job Code</th>
                                                <th>Phase</th>
                                                <th>Job Code</th>
                                                <th>Job Name</th>
                                                <th>Planned Start Date</th>
                                                <th>Planned End Date</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody class="LstProjectJobs">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" align="right">
                                            <button type="button" name="btn_add_job" id="BTN_ADD_JOB" class="btn btn-primary">Add Job</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="TabTasks" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr class="fw-bold fs-6 text-gray-800">
                                                <th>Task Code</th>
                                                <th>Phase</th>
                                                <th>Job</th>
                                                <th>Task Title</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Assigned To</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody class="LstProjectTasks">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" align="right">
                                            <button type="button" name="btn_add_task" id="BTN_ADD_TASK" class="btn btn-primary">Add Task</button>
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
                            <button type="submit" name="btn_save_project" id="BTN_SAVE_PROJECT"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" id="modal_link_teams">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Link Team</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form name="form_add_team" id="FRM_ADD_TEAM">
                        <span id="hidden_fields">

                       {!! csrf_field() !!}
                         <input type="hidden" name="pt_project_id" value="{{ $project_info->pp_id }}" />
                        </span>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Team :&nbsp;</label><br/>
                            <select name="pp_team_id" id="PP_TEAM_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select User Team">
                                <option value="">-- Select Team --</option>
                                @foreach ( $lst_user_teams as $key => $team_info )
                                    <option  value="{{ $team_info->ut_id }}">{{ $team_info->ut_team }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Allocation Time</label>
                                <input type="text" name="ptm_allocation_pct" id="PTM_ALLOCATION_PCT" class="form-control" required="required" maxlength="25"  value="" />
                            </div>
                        </div>
                        <div class="col-md-12" align="right">&nbsp;</div>
                        <div class="col-md-12" align="right">
                            <button type="submit" name="btn_link_team" id="BTN_LINK_TEAM" class="btn btn-primary">Link Team</button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
