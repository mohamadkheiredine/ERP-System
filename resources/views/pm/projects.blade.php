<?php
/***********************************************************
 * projects.blade.php
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
    <script type="text/javascript" src="{{ url('js/modules/projects.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/pmp/projects.js') }}"></script>
@endsection

@section('content')


    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Project Management</h3>
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
                                    <label class="control-label">Company :&nbsp;</label><br/>
                                    <select name="fk_company_id" id="FK_COMPANY_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Company Branch">
                                        <option value="">-- Select Branch --</option>
                                        @foreach ( $lst_companies as $key => $company_info )
                                            <option value="{{ $company_info->cd_id }}">{{ $company_info->cd_company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Project Type :&nbsp;</label><br/>
                                    <select name="fk_project_type_id" id="FK_PROJECT_TYPE_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Project Type">
                                        <option value="">-- Select Type --</option>
                                        @foreach ( $lst_project_types as $key => $type_info )
                                            <option value="{{ $type_info->pt_id }}">{{ $type_info->pt_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Project Manager :&nbsp;</label><br/>
                                    <select name="fk_project_manager_id" id="FK_PROJECT_MANAGER_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Project Manager">
                                        <option value="">-- Select Project Manager --</option>
                                        @foreach ( $lst_project_managers as $key => $user_info )
                                            <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Project Status :&nbsp;</label><br/>
                                    <select name="pp_status_id" id="PP_STATUS_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Project Status">
                                        <option value="">-- Select Status --</option>
                                        @foreach ( $lst_project_statuses as $key => $status_info )
                                            <option value="{{ $status_info->ps_id }}">{{ $status_info->ps_status_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 align-right">
                        <a href="{{ url('pm/projects/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-user"></i>
							<span>
								New Project
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
                        <th title="Project Code"> Project Code </th>
                        <th title="Project Name"> Project Name </th>
                        <th title="Project Status"> Project Status </th>
                        <th title="Start Date"> Start Date </th>
                        <th title="End Date"> End Date </th>
                        <th style="width:2px;" nowrap title="edit"> edit </th>
                        <th style="width:2px;" nowrap title="delete"> Delete </th>
                    </tr>
                    </thead>
                    <tbody  id="LstProjects">

                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-10" align="left">
                    <ul id="ProjectsPagination" class="pagination-sm"></ul>
                </div>
                <div class="col-md-2" align="right"></div>
            </div>
            <div class="row">
                <div class="col-md-12 order-1 order-md-1 align-right">
                    <a href="{{ url('pm/projects/addform') }}" class="btn btn-info">
					<span>
						<i class="fas fa-user"></i>
						<span>
							New Project
						</span>
					</span>
                    </a>

                </div>
            </div>
        </div>
    </div>
@endsection
