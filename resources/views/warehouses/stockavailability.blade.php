<?php
/***********************************************************
 * stockavailability.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/1/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/




?>

@extends('layouts.layout',['page_title' => "Inventory Reports"])


@section('plugins')
    <script type="text/javascript" src="{{ url('js/modules/warehouses.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/inventory/stockavailability.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Warehouse Stock Availability</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" data-action_type="EXPORT_AS_CSV" href="#">Export As CSV</a></li>
                        <li><a class="dropdown-item" data-action_type="EXPORT_AS_PDF" href="#">Export As PDF</a></li>
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
                                <select  name="sw_stock_warehouse" id="SW_STOCK_WAREHOUSE" data-actions-box="true" class="form-control form-select" data-control="select2" data-placeholder="Select warehouse">
                                    <option value="0">Select Warehouse</option>
                                    @foreach ( $lst_warehouses as $key => $warehouse_info )
                                        <option value="{{ $warehouse_info->w_id  }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <br/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-rounded table-striped border gy-7 gs-7" width="100%">
                    <thead>
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th title="Id">ID</th>
                        <th title="Warehouse Name">Warehouse Name</th>
                        <th title="Product Code">Product Code</th>
                        <th title="Product Name">Product Name</th>
                        <th title="Total Stock">Total Stock </th>
                    </tr>
                    </thead>
                    <tbody id="LstStockAvailability">



                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
