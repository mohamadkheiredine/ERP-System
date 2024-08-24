<?php
/***********************************************************
addcasestatus.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 18, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :

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

<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/casestatus.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/callcenter/savecasestatus.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Case Status</h3>
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
    <form name="frm_save_status" id="FORM_SAVE_STATUS">
                <div class="form-body">
                     <span id="hidden_fields"> 
                        {!! csrf_field() !!} 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Case Status Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>  
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Status </label>
                                     <select name="fk_parent_status" id="FK_PARENT_STATUS"   class="form-control form-select" data-control="select2" data-placeholder="Select Parent Status">
                                            <option value=""> -- Select Parent Status -- </option>
                                            @foreach($lst_statuses as $key => $status_info)
                                                    <option value="{{ $status_info->cc_id }}">{{ $status_info->cc_status_title }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div>
                       
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Status Label <span class="required"> * </span></label><br/>
                                <input type="text" name="cc_status_title" id="CC_STATUS_TITLE" class="form-control" value="" />
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Status Color <span class="required"> * </span></label><br/>
                                <input type="color" name="cc_status_color" required="required" id="CC_STATUS_COLOR" class="form-control" value="" />
                             </div>
                        </div>  
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CC_STATUS_DESCRIPTION"  class="form-control" name="cc_status_description"  cols=""></textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_status" id="BTN_SAVE_STATUS"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection