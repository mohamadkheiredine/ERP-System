<?php
/***********************************************************
 * stockmovements.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/5/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Inventory Reports"])


@section('plugins')
    <script type="text/javascript" src="{{ url('js/modules/warehouses.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/inventory/stockmovementreport.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Warehouse Stock Movement Report</h3>
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
                                &nbsp;
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <div class="form-group">
                                    <select  name="sm_stock_warehouse" id="SM_STOCK_WAREHOUSE" data-actions-box="true" class="form-control form-select" data-control="select2" data-placeholder="Select warehouse">
                                        <option value="0">Select Warehouse</option>
                                        @foreach ( $lst_warehouses as $key => $warehouse_info )
                                            <option value="{{ $warehouse_info->w_id  }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"> Up to date</label><br/>
                                    <input type="text" name="sm_upto_date"  id="SM_UPTO_DATE" class="form-control" value="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">&nbsp;</div>
            <div class="table-responsive">
                <table class="table table-rounded table-striped border gy-7 gs-7" width="100%">
                    <thead>
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th title="Type">Type</th>
                        <th title="Text">Text</th>
                        <th title="Warehouse Name">Warehouse Name</th>
                        <th title="Product Code">Product Code</th>
                        <th title="Product Name">Product Name</th>
                        <th title="Quantity">Quantity</th>
                    </tr>
                    </thead>
                    <tbody id="LstStockMovement">



                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
