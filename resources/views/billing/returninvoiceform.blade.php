<?php
/***********************************************************
 * returninvoiceform.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 2/8/2026
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2026
 *
 * Page Description :
 ***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Invoices Management"])

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
    <script type="text/javascript" src="{{ url('js/modules/invoices.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/billing/returninvoiceform.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Return Invoice Preview</h3>
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
            <form name="frm_return_invoice" id="FORM_RETURN_INVOICE">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                         <input type="hidden" name="page_number" value="1" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Return Invoice is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Client Code</label>
                                <input type="text" name="ca_client_code" id="CA_CLIENT_CODE" class="form-control" maxlength="25"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Invoice Code</label>
                                <input type="text" name="ca_invoice_code" id="CA_INVOICE_CODE" class="form-control" maxlength="25"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                                <br/>
                             <button type="button" name="btn_search" id="BTN_SEARCH" class="btn btn-info">Search</button>
                        </div>

                    <div class="row" style="height:15px;"></div>
                    <div class="row">
                        <div class="col-md-12 ContractInfo">

                        </div>
                    </div>
                    <div class="row" style="height:15px;"></div>

                    <div class="row">
                        <div class="col-md-12 ProductsForm" align="left">
                        </div>
                    </div>
                    <div class="row" style="height:50px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_return" id="BTN_SAVE_RETURN"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
