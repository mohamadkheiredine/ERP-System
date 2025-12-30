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
    <script type="text/javascript">
$(function(){
    new tempusDominus.TempusDominus(document.getElementById('TA_START_DATE'),{
        display: {
            components: {
                calendar: true,
                date: true,
                month: true,
                year: true,
                decades: true,
                clock: false,
                hours: false,
                minutes: false,
                seconds: false,
                useTwentyfourHour: undefined
            }
        },
        localization: {
            format : "yyyy-MM-dd"

        }
    });


    new tempusDominus.TempusDominus(document.getElementById('TA_END_DATE'),{
        display: {
            components: {
                calendar: true,
                date: true,
                month: true,
                year: true,
                decades: true,
                clock: false,
                hours: false,
                minutes: false,
                seconds: false,
                useTwentyfourHour: undefined
            }
        },
        localization: {
            format : "yyyy-MM-dd"

        }
    });

})
    </script>
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
                <form name="frm_search_telemarketing" method="get" id="FORM_SEARCH_TELEMARKETING" action="{{ url('callcenter/reports/telemarketing') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label>Start Date</label>
                        <input type="text" name="from" id="TA_START_DATE" class="form-control" value="" />
                    </div>
                    <div class="col-md-4">
                        <label>End Date</label>
                        <input type="text" name="to" id="TA_END_DATE" class="form-control" value="" />
                    </div>
                    <div class="col-md-4">
                        <br/>
                        <button type="submit" class="btn btn-info" name="btn_search" id="BTN_SEARCH">Submit</button>
                    </div>
                </div>
                </form>
                <div class="row">
                    <div class="col-md-12" style="height:20px">&nbsp;</div>
                </div>
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



