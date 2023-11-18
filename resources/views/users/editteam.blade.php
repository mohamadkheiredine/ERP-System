<?php
/***********************************************************
editteam.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Feb 2, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


?>
@extends('layouts.layout',['page_title' => "Team Management"])

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
<script type="text/javascript" src="{{ url('js/modules/team.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/users/saveteams.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Existing Team</h3>
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
    <form name="frm_save_team" id="FORM_SAVE_TEAM">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                       <input type="hidden" name="ut_id" value="{{ $team_info->ut_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> User Team Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Team Label <span class="required"> * </span></label>
                                    <input type="text" name="ut_team" id="UT_TEAM" class="form-control" required="required" maxlength="255"  value="{{ $team_info->ut_team }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Team Members</label>
                               <select class="form-control m-bootstrap-select m_selectpicker" name="ut_members_id[]" multiple="multiple" id="UT_MEMBERS_ID" data-actions-box="true">
                                        <option value="">-- Select Users --</option>
                                        @foreach ( $lst_users as $key => $user_info )
                                                <option {{  array_search($user_info->id,$team_array) !== FALSE ? "selected" : "" }}  value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Team Notes</label>
								<textarea name="ut_description" id="UT_DESCRIPTION" class="form-control" style="width:100%;height:250px;">{{ $team_info->ut_description }}</textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_team" id="BTN_SAVE_TEAM"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
    

@endsection
