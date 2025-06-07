<?php
/***********************************************************
 * depreciation.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/3/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Assets Depreciation Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/adepreciation.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/assets/depreciation.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">	Asset Depreciation</h3>
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
            <!--begin: Search Form -->
            <div class="col-md-12">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="form-group row align-items-center">
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
                                <div class="form-group">
                                    <select  name="at_asset_id" id="AT_ASSET_ID" class="form-select" data-control="select2" data-placeholder="Select Asset">
                                        <option value="">No Asset</option>
                                        @foreach ( $lst_assets as $key => $asset_info )
                                            <option value="{{ $asset_info->aa_id }}">{{ $asset_info->aa_asset_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 m--align-right">

                    </div>
                </div>
            </div>
            <div class="col-md-12">&nbsp;</div>
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4" style="text-align: right">
                    <a href="{{ url('assets/depreciation/addform') }}" class="btn btn-info">
							<span>
								<i class="fas fa-user"></i>
								<span>
									New Depreciation Asset
								</span>
							</span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 gy-7">
                            <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th style="width:2px;">#</th>
                                <th style="width:2px;">ID</th>
                                <th>Asset</th>
                                <th>Depreciation Value</th>
                                <th>New Value</th>
                                <th style="width:4px;white-space: nowrap;text-align: center">edit</th>
                                <th style="width:4px;white-space: nowrap;text-align: center">Delete</th>
                            </tr>
                            </thead>
                            <tbody  class="LstDepreciationGrid"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10" align="left">
                    <ul id="AssetDepreciationPagination" class="pagination-sm"></ul>
                </div>
                <div class="col-md-2" align="right"></div>
            </div>
            <!--end: Datatable -->
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4" style="text-align: right">
                    <a href="{{ url('assets/depreciation/addform') }}" class="btn btn-info">
							<span>
								<i class="fas fa-user"></i>
								<span>
									New Depreciation Asset
								</span>
							</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
