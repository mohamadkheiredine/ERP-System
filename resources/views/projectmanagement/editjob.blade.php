<?php
/***********************************************************
 * editjob.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/26/2025
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
    <script type="text/javascript" src="{{ url('js/modules/projjobs.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/pmp/savejobs.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Project Management > Add New Project Job</h3>
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
            <form name="frm_save_job" id="FORM_SAVE_JOB">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                         <input type="hidden" name="pj_id" value="{{ $project_jobs->pj_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Project Job Information is saved successfully!
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
                                        <option {{ $project_jobs->fk_project_id == $project_info->pp_id ? "selected" : "" }} value="{{ $project_info->pp_id }}">{{ $project_info->pp_project_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Phase :&nbsp;</label><br/>
                                <div class="PhaseDropdown">
                                    <select name="fk_phase_id" id="FK_PHASE_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Phase">
                                        <option value="">-- Select Phase --</option>
                                        @foreach ( $lst_phases as $key => $phase_info )
                                            <option {{ $project_jobs->fk_phase_id == $phase_info->pp_phase_id ? "selected" : "" }} value="{{ $phase_info->pp_phase_id }}">{{ $phase_info->pp_phase_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Owner :&nbsp;</label><br/>
                                <select name="pj_owner_id" id="PJ_OWNER_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Owner">
                                    <option value="">-- Select Owner --</option>
                                    @foreach ( $lst_users as $key => $user_info )
                                        <option {{ $project_jobs->pj_owner_id == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Job Code <span class="required"> * </span></label>
                                <input type="text" name="pj_job_code" id="PJ_JOB_CODE" class="form-control" readonly required="required" maxlength="25"  value="{{ $project_jobs->pj_job_code }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Job Title <span class="required"> * </span></label>
                                <input type="text" name="pj_job_name" id="PJ_JOB_NAME" class="form-control" required="required" maxlength="255"  value="{{ $project_jobs->pj_job_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Planned Start Date</label>
                                <input type="text" name="pj_planned_start" id="PJ_PLANNED_START" class="form-control" readonly required="required" maxlength="25"  value="{{ $project_jobs->pj_planned_start }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Planned End Date</label>
                                <input type="text" name="pj_planned_end" id="PJ_PLANNED_END" class="form-control" required="required" maxlength="25"  value="{{ $project_jobs->pj_planned_end }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Actual Start Date</label>
                                <input type="text" name="pj_actual_start" id="PJ_ACTUAL_START" class="form-control" readonly required="required" maxlength="25"  value="{{ $project_jobs->pj_actual_start }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Actual End Date</label>
                                <input type="text" name="pj_actual_end" id="PJ_ACTUAL_END" class="form-control" required="required" maxlength="25"  value="{{ $project_jobs->pj_actual_end }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Phase Status :&nbsp;</label><br/>
                                <select name="pj_status_id" id="PJ_STATUS_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Job Status">
                                    <option value="">-- Select Status --</option>
                                    @foreach ( $lst_job_status as $key => $status_info )
                                        <option {{ $project_jobs->pj_status_id == $status_info->ss_id ? "selected" : "" }} value="{{ $status_info->ss_id }}">{{ $status_info->ss_status_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Job Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="PJ_DESCRIPTION"  class="form-control" name="pj_description"  cols="">{{ $project_jobs->pj_description }}</textarea>
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
@endsection
