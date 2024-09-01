<?php 


?>

@extends('layouts.layout',['page_title' => "CRM Dashboard"])

@section('plugins')
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
       
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
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">CRM Dashboard</h1>

        <!-- Row 1: Sales Performance and Lead Conversion -->
        <div class="row chart-container">
            <div class="col-md-6">
                <h3 class="text-center">Sales Performance</h3>
                <div id="chartdiv1"></div>
            </div>
            <div class="col-md-6">
                <h3 class="text-center">Lead Conversion Funnel</h3>
                <div id="chartdiv2"></div>
            </div>
        </div>

        <!-- Row 2: Agent Performance and Customer Satisfaction -->
        <div class="row chart-container">
            <div class="col-md-6">
                <h3 class="text-center">Agent Performance</h3>
                <div id="chartdiv3"></div>
            </div>
            <div class="col-md-6">
                <h3 class="text-center">Customer Satisfaction</h3>
                <div id="chartdiv4"></div>
            </div>
        </div>

        <!-- Row 3: Call Volume by Hour -->
        <div class="row chart-container">
            <div class="col-md-12">
                <h3 class="text-center">Call Volume by Hour</h3>
                <div id="chartdiv5"></div>
            </div>
        </div>
    </div>
@endsection