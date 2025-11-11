<?php
/***********************************************************
plans.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 14, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Production Plans Management"])

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
<script type="text/javascript" src="{{ url('js/modules/productionplans.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/production/plansmanagement.js') }}"></script>
@endsection

@section('content')

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Production Plan Management</h3>
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
            <div class="row align-items-center">
                <div class="col-xl-8 order-2 order-xl-1">
                    <div class="form-group m-form__group row align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="position-relative w-md-400px me-md-2">
                                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-control" data-control="select2" name="ps_plan_status" id="PS_PLAN_STATUS" >
                                    <option value="">Plan Status</option>
                                    @foreach ( $lst_plan_status as $key => $status_info )
                                        <option value="{{ $status_info->ps_id }}">{{ $status_info->ps_status_title }}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 order-1 order-xl-2 align-right" style="text-align: right">
                    <a href="{{ url('production/plan/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-microchip"></i>
							<span>
								New Plan
							</span>
						</span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-md-12 table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 gy-7">
                            <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                            <th>#</th>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Label</th>
                            <th>Status</th>
                            <th>Manager</th>
                            <th>edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody  class="LstProdPlan"></tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 order-1 order-md-1 align-right">
                    <a href="{{ url('production/plan/addform') }}" class="btn btn-info">
					<span>
						<i class="fas fa-microchip"></i>
						<span>
							New Plan
						</span>
					</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
