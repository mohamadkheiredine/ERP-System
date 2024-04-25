<?php
/* * *********************************************************
  team.blade.php
  Product :
  Version : 1.0
  Release : 1
  Date Created : Jan 28, 2020
  Developed By  : Mohamad Mantach   PHP Department itm Solutions
  All Rights Reserved ,   itm Solutions COPYRIGHT 2020

  Page Description :

 * ********************************************************* */
?>


@extends('layouts.layout',['page_title' => "Users Team Management"])

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
<script type="text/javascript" src="{{ url('js/modules/team.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/users/teams.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">User Teams</h3>
        <div class="card-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Action
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Export as CSV</a></li>
                    <li><a class="dropdown-item" href="#">Download Import Template</a></li>
                    <li><a class="dropdown-item" href="#">Import</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="col-md-12">
            <div class="row align-items-center">
                <div class="col-xl-8 order-2 order-xl-1">
                    <div class="form-group m-form__group row align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <!--begin::Input group-->
                                <div class="position-relative w-md-400px me-md-2">
                                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" />
                                </div>
                                <!--end::Input group-->
                            </div>

                        </div>
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 order-1 order-xl-2 m--align-right">

                </div>
            </div>
        </div>
        <div class="col-md-12" style="height:50px">&nbsp;</div>
        <!--end: Search Form -->
        <!--begin: Datatable -->
        <div class="table-responsive">
            <table class="table table-rounded table-striped border gy-7 gs-7">
                <thead>
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Team Name</th>
                        <th>Number of Members</th>
                        <th>edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody  class="LstTeamsGrid"></tbody>
            </table>
        </div>
        <!--end: Datatable -->
        <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2">
                <a href="{{ url('administrator/team/addform') }}" class="btn btn-info">
                    <span>
                        <i class="fas fa-user"></i>
                        <span>
                            New Team
                        </span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection