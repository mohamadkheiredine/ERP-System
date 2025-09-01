<?php
/***********************************************************
 * editprojectrole.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/24/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



?>


@extends('layouts.layout',['page_title' => "Projects Management"])

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
    <script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
    <script type="text/javascript" src="{{ url('js/modules/projectroles.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/pmp/saveprojectrole.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Project Roles Management > Edit Project Role</h3>
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
            <form name="frm_save_role" id="FORM_SAVE_ROLE">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                         <input type="hidden" name="pr_id" value="{{ $project_roles->pr_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Project Role Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Role Name <span class="required"> * </span></label>
                                <input type="text" name="pr_name" id="PR_NAME" class="form-control" required="required" maxlength="255"  value="{{ $project_roles->pr_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <br/>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" {{ $project_roles->pr_is_billable_default == 1 ? "checked" : "" }} type="checkbox" name="pr_is_billable_default" id="PR_IS_BILLABLE_DEFAULT"  value="1"  />
                                    <span class="form-check-label fw-semibold text-muted">
                                           Is Billable Default
                                        </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Role Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="PR_DESCRIPTION"  class="form-control" name="pr_description"  cols="">{{ $project_roles->pr_description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_role" id="BTN_SAVE_ROLE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
