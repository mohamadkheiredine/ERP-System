<?php
/***********************************************************
 * editappresult.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 7/3/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>


@extends('layouts.layout',['page_title' => "CRM Leads Management"])

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
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/appresults.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/crm/saveappresult.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Edit App Result</h3>
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
            <form name="frm_save_result" id="FORM_SAVE_RESULT">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                         <input type="hidden" name="ar_id" value="{{ $result_info->ar_id  }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> App Result Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Parent Result </label>
                                <select name="ar_result_parent" id="AR_RESULT_PARENT"   class="form-control form-select" data-control="select2" data-placeholder="Select Parent Result">
                                    <option value=""> -- Select Parent Result -- </option>
                                    @foreach($lst_results_parents as $key => $res_info)
                                        <option {{ $result_info->ar_result_parent == $res_info->ar_id  ? "selected" : "" }} value="{{ $res_info->ar_id }}">{{ $res_info->ar_app_result }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Result Label <span class="required"> * </span></label><br/>
                                <input type="text" name="ar_app_result" id="AR_APP_RESULT" class="form-control" value="{{ $result_info->ar_app_result }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Result Color <span class="required"> * </span></label><br/>
                                <input type="color" name="ar_result_color" required="required" id="AR_RESULT_COLOR" class="form-control" value="{{ $result_info->ar_result_color }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <br/>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" {{ $result_info->ar_app_show_apt == 1 ? "checked" : "" }} name="ar_app_show_apt" id="AR_APP_SHOW_APT"   value="1"  />
                                    <span class="form-check-label fw-semibold text-muted">
                                          Show in appointment results
                                        </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="AR_APP_DESCRIPTION"  class="form-control" name="ar_app_description"  cols="">{{ $result_info->ar_app_description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_result" id="BTN_SAVE_RESULT"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
