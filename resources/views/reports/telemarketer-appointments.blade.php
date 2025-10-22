<?php
/***********************************************************
 * telemarketer-appointments.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/16/2025
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
            <h3 class="card-title">Telemarketing Appointment Report</h3>
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
                    <h4 class="mb-0">📞 Telemarketer Appointment Report</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Telemarketer</th>
                            <th>Average</th>
                            <th>App</th>
                            <th>Confirmed App</th>
                            <th>Pending App</th>
                            <th>Demo</th>
                            <th>Cancel</th>
                            <th>Sold</th>
                            <th>Reset</th>
                            <th>Reset DA</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $row)
                            <tr @if(strtolower($row->telemarketer) == 'total marketing') class="fw-bold table-secondary" @endif>
                                <td>{{ $row->telemarketer }}</td>
                                <td>{{ number_format($row->average, 2) * 100 }}</td>
                                <td>{{ $row->app }}</td>
                                <td>{{ $row->confirmed_app }}</td>
                                <td>{{ $row->pending_app }}</td>
                                <td>{{ $row->demo }}</td>
                                <td>{{ $row->cancel }}</td>
                                <td>{{ $row->sold }}</td>
                                <td>{{ $row->reset }}</td>
                                <td>{{ $row->reset_da }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection



