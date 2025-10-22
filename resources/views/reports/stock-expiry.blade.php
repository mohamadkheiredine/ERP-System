<?php
/***********************************************************
 * stock-expiry.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/16/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Inventory Management"])

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
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Stock Expiry Date Report</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                    <ul class="dropdown-menu">
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="container py-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>📦 Stock Expiry Report</h4>
                    <form method="get" class="d-flex gap-2">
                        <label class="form-label mb-0 mt-1">Expired In</label>
                        <label class="form-label mb-0 mt-1">{{ $days }} days</label>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Reference</th>
                            <th>Stock Label</th>
                            <th>Quantity</th>
                            <th>Warehouse</th>
                            <th>Expiry Date</th>
                            <th>Days to Expiry</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $row)
                            <tr
                                @class([
                                    'table-danger' => $row->expiry_status === 'Expired',
                                    'table-warning' => $row->expiry_status === 'Expiring Soon',
                                    'table-success' => $row->expiry_status === 'Valid',
                                ])
                            >
                                <td>{{ $row->p_product_name }}</td>
                                <td>{{ $row->p_product_ref }}</td>
                                <td>{{ $row->is_stock_label }}</td>
                                <td>{{ $row->quantity }}</td>
                                <td>{{ $row->warehouse_name }}</td>
                                <td>{{ $row->expiry_date }}</td>
                                <td>{{ $row->days_to_expiry }}</td>
                                <td><strong>{{ $row->expiry_status }}</strong></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

