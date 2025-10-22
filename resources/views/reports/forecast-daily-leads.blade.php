<?php
/***********************************************************
 * forecast-daily-leads.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/13/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



{
    $dt = date('Y-m-d');
}
?>


@extends('layouts.layout',['page_title' => "Call Center Management"])

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
            <h3 class="card-title">Forcast Daily Leads Report</h3>
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
            <div class="row">
                <div style="text-align:center"  class='col-md-12'>
                    <div class="container py-4">
                        <h4 class="mb-4">📈 Daily Lead Forecast (Current Month)</h4>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Actual Leads</th>
                                    <th>Expected Daily Leads</th>
                                    <th>Cumulative Expected Leads</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($results as $row)
                                    <tr>
                                        <td>{{ $row->report_date }}</td>
                                        <td>{{ number_format($row->actual_leads) }}</td>
                                        <td>{{ number_format($row->expected_daily_leads) }}</td>
                                        <td>{{ number_format($row->cumulative_expected_leads) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
