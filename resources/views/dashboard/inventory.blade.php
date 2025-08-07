<?php
/***********************************************************
 * inventory.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 7/28/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

@extends('layouts.layout',['page_title' => "CRM Dashboard"])

@section('plugins')
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script type="text/javascript" src="{{ url('js/libraries/dashboard/inventory.js') }}"></script>

    <script>





    </script>
@endsection
@section('themes')
    <style>
        #chartdiv1,
        #chartdiv2,
        #chartdiv3,
        #chartdiv4,
        #chartdiv5 {
            width: 100%;
            height: 400px;
        }

        .chart-container {
            margin-bottom: 30px;
        }
        .card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .card-title {
            font-size: 1.2em;
            font-weight: 600;
            color: #2c3e50;
        }
        .alert-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            background: #f8f9fa;
            border-left: 4px solid #e74c3c;
        }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Inventory Dashboard</h3>
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
            <div class="container mt-5">

                <div class="row chart-container">
                    <div class="col-md-6">
                        <h3 class="text-center">Stock BY Products</h3>
                        <div id="chartdiv1"></div>
                    </div>
                    <div class="col-md-6">
                        <h3 class="text-center">Stock By Warehouses</h3>
                        <div id="chartdiv2"></div>
                    </div>
                </div>

                <!-- Row 2: Agent Performance and Customer Satisfaction -->
                <div class="row chart-container">
                    <div class="col-md-6">
                        <h3 class="text-center">Selling Products</h3>
                        <div id="chartdiv3"></div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>

                <!-- Row 3: Call Volume by Hour -->
                <div class="row chart-container">
                    <div class="col-md-12">
                        <h3 class="text-center"></h3>
                        <div id="chartdiv5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
