<?php
/***********************************************************
 * cumulativeleadsreport.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/12/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

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
            <h3 class="card-title">monthly Cumulative Number of Leads Report</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                    <ul class="dropdown-menu">
{{--                        <li><a class="dropdown-item QuickAction" data-action_type="DOWNLOAD_PDF" href="#">Download PDF</a></li>--}}
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">


            <div class="row">
                <div class="col-md-4">

                </div>
                <div class="col-md-4">

                </div>

                <div class="col-md-4">

                </div>
            </div>
            <div class="row">

            </div>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 gy-7">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800">
                            <th title="Date"> Date </th>
                            <th title="Daily Count"> Daily Count </th>
                            <th title="Cumulative Number of Leads"> Cumulative Number of Leads </th>
                        </tr>
                        </thead>
                        <tbody  id="LstCumulativeLeads">
                            @foreach($cumulative_results as $index => $record_info)
                                <tr>
                                    <td title="Date"> {{ $record_info->lead_date }} </td>
                                    <td title="Daily Count"> {{ $record_info->daily_leads }} </td>
                                    <td title="Cumulative Number of Leads"> {{ $record_info->cumulative_total }}  </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
