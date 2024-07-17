<?php
/***********************************************************
companies.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page to manage companies 
***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Companies Management"])

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
<script type="text/javascript" src="{{ url('js/modules/companies.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/system/companies.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Companies Management</h3>
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
    <div class="row">
                 
                        <div class="col-xl-12 order-2 order-xl-1">
                                <div class="row">
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
                                               <br/>
                                        </div>
                                       <div class="col-xl-4 order-1 order-xl-2 align-right">
                                <a href="{{ url('system/companies/addform') }}" class="btn btn-info">
                                        <span>
                                                <i class="fa fa-building"></i>
                                                <span>
                                                        New Company
                                                </span>
                                        </span>
                                </a> 
                                      
                                </div> 
                                </div>        
                        
                </div>
        </div> 
     <div class="row">
        <!--end: Search Form -->
<!--begin: Datatable -->
        <div class=" col-md-12 table-responsive">
                <table class="table">
        <thead>
                <tr class="fw-bold fs-6 text-gray-800">
                        <th><input type="checkbox" name="ck_cmp_all" id="CK_CMP_ALL" class="group-checkable" value="1" /></th>
                        <th>ID</th>
                        <th>Company Name</th>
                        <th>Company Owner</th>
                        <th>edit</th>
                        <th>Delete</th>
                </tr>
        </thead>
        <tbody  class="LstCompaniesGrid"></tbody>
</table>
        </div>
        <!--end: Datatable -->
        <div class="col-xl-12 order-1 order-xl-12 align-right">
                                <a href="{{ url('system/companies/addform') }}" class="btn btn-info">
                                        <span>
                                                <i class="fa fa-building"></i>
                                                <span>
                                                        New Company
                                                </span>
                                        </span>
                                </a>
                        </div>
    </div>
</div>
@endsection