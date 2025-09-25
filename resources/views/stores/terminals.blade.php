<?php
/***********************************************************
 * terminals.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/21/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



?>




@extends('layouts.layout',['page_title' => "Terminals Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/terminals.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/stores/terminals.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Terminals management</h3>
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
        <span id="hidden_fields">
            <input type="hidden" name="page_number" value="1" />
        </span>
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="form-group m-form__group row align-items-center">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative w-md-400px me-md-2">
                                        <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-control" data-control="select2" id="PT_STORE_ID" name="pt_store_id">
                                    <option value="0">-- Select Store --</option>
                                    @foreach($lst_stores as $index => $store_info)
                                        <option value="{{ $store_info->ps_id }}">{{ $store_info->ps_store_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="d-md-none m--margin-bottom-10"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 align-right" style="text-align: right">
                        <a href="{{ url('terminals/addform') }}" class="btn btn-info">
                        <span>
                            <i class="fas fa-user"></i>
                            <span>
                                New Terminal
                            </span>
                        </span>
                        </a>
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">&nbsp;</div>
            <!--end: Search Form -->
            <div class="col-md-12">
                <table class="table table-bordered table-hover" width="100%">
                    <thead>
                    <tr>
                        <th style="width:4px;white-space: nowrap;"  title="Id"></th>
                        <th style="width:4px;white-space: nowrap;"  title="Id">ID</th>
                        <th title="Store Name">Store Name</th>
                        <th title="Store Terminal">Store Terminal</th>
                        <th title="Manager">Manager</th>
                        <th style="width:4px;white-space: nowrap;"  title="#">edit</th>
                        <th style="width:4px;white-space: nowrap;"  title="#">Delete</th>
                    </tr>
                    </thead>
                    <tbody class="LstStoreTerminalsGrid" id="LstStoreTerminalsGrid">
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-10" align="left">
                    <ul id="TerminalsPagination" class="pagination-sm"></ul>
                </div>
                <div class="col-md-2" align="right"></div>
            </div>
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4" align="right">
                    <a href="{{ url('terminals/addform') }}" class="btn btn-info">
                    <span>
                        <i class="fas fa-user"></i>
                        <span>
                            New Terminal
                        </span>
                    </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
