<?php
/***********************************************************
addstatus.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 6, 2022
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2022

Page Description :

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
<script type="text/javascript" src="{{ url('js/modules/projectstatuses.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/pmp/saveprojectstatus.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Project Status Management > Add New Project Status</h3>
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
            				<strong>Success!</strong> Project Status Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Status Name <span class="required"> * </span></label>
                                    <input type="text" name="ps_status_title" id="PS_STATUS_TITLE" class="form-control" required="required" maxlength="255"  value="" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Status Color</label>
                                <input type="color" name="ps_status_color" id="PS_STATUS_COLOR" class="form-control" required="required" maxlength="8"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Status Dependancy </label>
                                <select class="bs-select form-control" name="ps_depend_on" id="PS_DEPEND_ON" data-actions-box="true">
                                        <option value="0">No Dependancy</option>
                                        <?php foreach ( $lst_project_status as $key => $status_info ) { ?>
                                                <option value="<?php echo $status_info->ps_id;  ?>"><?php echo $status_info->ps_status_title;  ?></option>
                                        <?php  } ?>
                                </select>
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
