<?php
/***********************************************************
 * editphase.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/31/2025
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
    <script type="text/javascript" src="{{ url('js/modules/projphases.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/pmp/savephases.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Project Management > Add New Project Phase</h3>
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
            <form name="frm_save_phase" id="FORM_SAVE_PHASE">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                         <input type="hidden" name="pp_phase_id" value="{{ $project_phase->pp_phase_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Project Phase Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Project :&nbsp;</label><br/>
                                <select name="fk_project_id" id="FK_PROJECT_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Project">
                                    <option value="">-- Select Project --</option>
                                    @foreach ( $lst_projects as $key => $project_info )
                                        <option {{ $project_phase->fk_project_id == $project_info->pp_id ? "selected" : "" }} value="{{ $project_info->pp_id }}">{{ $project_info->pp_project_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Department :&nbsp;</label><br/>
                                <select name="pp_department_id" id="PP_DEPARTMENT_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Department">
                                    <option value="">-- Select Department --</option>
                                    @foreach ( $lst_departments as $key => $dep_info )
                                        <option {{ $project_phase->pp_department_id == $dep_info->sd_id ? "selected" : "" }} value="{{ $dep_info->sd_id }}">{{ $dep_info->sd_department_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Team :&nbsp;</label><br/>
                                <select name="pp_team_id" id="PP_TEAM_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Team">
                                    <option value="">-- Select Team --</option>
                                    @foreach ( $lst_teams as $key => $team_info )
                                        <option {{ $project_phase->pp_team_id == $team_info->ut_id ? "selected" : "" }} value="{{ $team_info->ut_id }}">{{ $team_info->ut_team }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Phase Code <span class="required"> * </span></label>
                                <input type="text" name="pp_phase_code" id="PP_PHASE_CODE" class="form-control" readonly required="required" maxlength="25"  value="{{ $project_phase->pp_phase_code }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Phase Title <span class="required"> * </span></label>
                                <input type="text" name="pp_phase_name" id="PP_PHASE_NAME" class="form-control" required="required" maxlength="255"  value="{{ $project_phase->pp_phase_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Planned Start Date</label>
                                <input type="text" name="pp_planned_start" id="PP_PLANNED_START" class="form-control" readonly required="required" maxlength="25"  value="{{ $project_phase->pp_planned_start }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Planned End Date</label>
                                <input type="text" name="pp_planned_end" id="PP_PLANNED_END" class="form-control" required="required" maxlength="25"  value="{{ $project_phase->pp_planned_end }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Actual Start Date</label>
                                <input type="text" name="pp_actual_start" id="PP_ACTUAL_START" class="form-control" readonly required="required" maxlength="25"  value="{{ $project_phase->pp_actual_start }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Actual End Date</label>
                                <input type="text" name="pp_actual_end" id="PP_ACTUAL_END" class="form-control" required="required" maxlength="25"  value="{{ $project_phase->pp_actual_end }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Phase Status :&nbsp;</label><br/>
                                <select name="pp_status_id" id="PP_STATUS_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Project Status">
                                    <option value="">-- Select Status --</option>
                                    @foreach ( $lst_phase_statuses as $key => $status_info )
                                        <option {{ $project_phase->pp_status_id == $status_info->ps_id ? "selected" : "" }} value="{{ $status_info->ps_id }}">{{ $status_info->ps_status_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Phase Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="PP_PHASE_DESCRIPTION"  class="form-control" name="pp_phase_description"  cols="">{{ $project_phase->pp_phase_description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_phase" id="BTN_SAVE_PHASE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
