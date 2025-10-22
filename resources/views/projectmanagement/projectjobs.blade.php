<?php
/***********************************************************
 * projectjobs.blade.php
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




@extends('layouts.layout',['page_title' => "Project Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/projjobs.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/pmp/projjobs.js') }}"></script>
@endsection

@section('content')


    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Project Jobs Management</h3>
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
            <span id="hidden_fields">
                <input type="hidden" name="page_number" value="1" />
            </span>
            <div class="col-md-12">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="form-group m-form__group row align-items-center">
                            <div class="col-md-4">
                                <div class="position-relative me-md-2">
                                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid ps-10" name="general_search" value="" placeholder="Search" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Project :&nbsp;</label><br/>
                                    <select name="fk_project_id" id="FK_PROJECT_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Project">
                                        <option value="">-- Select Project --</option>
                                        @foreach ( $lst_projects as $key => $project_info )
                                            <option value="{{ $project_info->pp_id }}">{{ $project_info->pp_project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                &nbsp;<div class="form-group">
                                    <label class="control-label">Project :&nbsp;</label><br/>
                                    <select name="fk_phase_id" id="FK_PHASE_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Phase">
                                        <option value="">-- Select Phase --</option>
                                        @foreach ( $lst_project_phases as $key => $phase_info )
                                            <option value="{{ $phase_info->pp_phase_id }}">{{ $phase_info->pp_phase_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 align-right">
                        <a href="{{ url('projects/phases/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-user"></i>
							<span>
								New Phase
							</span>
						</span>
                        </a>
                    </div>
                </div>
            </div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 gy-7">
                    <thead>
                        <tr class="fw-bold fs-6 text-gray-800">
                            <th style="width:2%" title="#">#</th>
                            <th style="width:2%" title="Id"> ID </th>
                            <th title="Job Code"> Job Code </th>
                            <th title="Job Title"> Job Title </th>
                            <th title="Project"> Project </th>
                            <th title="Status"> Status </th>
                            <th title="Assign to"> Assign to </th>
                            <th title="Start Date"> Start Date </th>
                            <th title="End Date"> End Date </th>
                            <th style="width:2px;" nowrap title="edit"> edit </th>
                            <th style="width:2px;" nowrap title="delete"> Delete </th>
                        </tr>
                    </thead>
                    <tbody  id="LstProjectJobs">

                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-10" align="left">
                    <ul id="ProjectJobsPagination" class="pagination-sm"></ul>
                </div>
                <div class="col-md-2" align="right"></div>
            </div>
            <div class="row">
                <div class="col-md-12 order-1 order-md-1 align-right">
                    <a href="{{ url('projects/phases/addform') }}" class="btn btn-info">
					<span>
						<i class="fas fa-user"></i>
						<span>
							New Phase
						</span>
					</span>
                    </a>

                </div>
            </div>
        </div>
    </div>
@endsection
