<?php
/***********************************************************
 * index.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 3/11/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 * Manage Zone Information
 ***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Warehouse Zones Management"])


@section('plugins')
    <script type="text/javascript" src="{{ url('js/modules/warehousezones.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/inventory/warehousezones.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Warehouse Zones</h3>
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
                                <br/>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> Warehouse </label>
                                    <select  name="fk_warehouse_id" id="FK_WAREHOUSE_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Warehouse">
                                        <option value="">Select Warehouse</option>
                                        @foreach ( $lst_warehouses as $key => $warehouse_info )
                                            <option value="{{  $warehouse_info->w_id }}">{{  $warehouse_info->w_warehouse_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 align-right">
                        <a href="{{ url('inventory/zones/addform') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Zone
													</span>
												</span>
                        </a>
                        <br/>
                    </div>
                </div>
            </div>

            <!--end: Search Form -->
            <!--begin: Datatable -->
            <div class="table-responsive">
                <table class="table table-rounded table-striped border gy-7 gs-7" width="100%">
                    <thead>
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th title="#">#</th>
                        <th title="Id">ID</th>
                        <th title="Zone Name">Zone Name</th>
                        <th title="Warehouse">Warehouse</th>
                        <th style="width:3px;white-space: nowrap;" title="edit">edit</th>
                        <th style="width:3px;white-space: nowrap;" title="delete">Delete</th>
                    </tr>
                    </thead>
                    <tbody id="LstWarehouseZones" class="LstWarehouseZones">



                    </tbody>
                </table>
            </div>
            <!--end: Datatable -->
            <div class="row">
                <div class="col-xl-10 order-1 order-xl-12 align-left">
                    <ul id="ZonesPagination" class="pagination-sm"></ul>
                </div>
                <div class="col-xl-2 order-1 order-xl-12 align-right">
                    <a href="{{ url('inventory/zones/addform') }}" class="btn btn-info">
                        <span>
                            <i class="fas fa-user"></i>
                            <span>
                                New Zone
                            </span>
                        </span>
                    </a>
                    <div class="m-separator m-separator--dashed d-xl-none"></div>
                </div>
            </div>
        </div>
    </div>

@endsection
