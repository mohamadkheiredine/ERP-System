<?php
/***********************************************************
maindashboard.blade.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Nov 3, 2023
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2023

Page Description :
{Enter page description Here}
 ***********************************************************/

?>
@extends('layouts.layout',['page_title' => "Dashboard"])

@section('plugins')
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>

    <script src="{{ url('default/assets/app/js/dashboard.js') }}" type="text/javascript"></script>
@endsection
@section('themes')
    <link href="{{ url('admin/assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('admin/assets/assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .amcharts-export-menu.amcharts-export-menu-top-right.amExportButton {
            display: none;
        }

        #chartsalesbyproducts {
            width: 100%;
            height: 500px;
        }
    </style>
@endsection

@section('content')
@endsection
