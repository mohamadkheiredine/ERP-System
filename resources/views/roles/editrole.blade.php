<?php
/************************************************************
editrole.blade.php
Product :
Version : 1.0
Release : 0
Date Created : Aug 7, 2015
Developed By  : Mohamad. Mantach  PHP Department Softweb S.A.R.L
All Rights Reserved, Softweb S.A.R.L COPYRIGHT 2015

Page Description :
--
************************************************************/


?>

@extends('layouts.layout',['page_title' => 'Roles Management'])

@section('plugins')
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/roles.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/roles/saverole.js') }}"></script>
@endsection
@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Existing Role</h3>
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
    <form name="form_save_role" id="FORM_SAVE_ROLE">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="role_id" id="ROLE_ID" value="{{ $roles->role_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Role Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                      <div class="row">
                        <div class="col-md-9">
                             <div class="form-group">
                                <label class="control-label">Role Name <span class="required"> * </span></label>
                                <input type="text" name="role_name" id="ROLE_NAME" class="form-control" required="required"  value="{{ $roles->role_name }}" />
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                   <div class="row">
                                    <div class="col-md-9">
                                         <div class="form-group">
                                            <label class="control-label">Role Description</label>
                                            <textarea style="width:100%;height:150px;resize:none" name="role_description" id="ROLE_DESCRIPTION"  class="form-control">{{ $roles->role_description }}</textarea>
                                        </div>
                                    </div>
                        <div class="col-md-3"></div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                   <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="white-space: nowrap;">
                        <div class="portlet-body">
        					<h3>Privileges</h3>
                               <ul class="nav nav-tabs nav-line-tabs mb-12 fs-6">
        					@foreach($pa_result_array as $tab_title => $value)
                                <li class="nav-item <?php echo str_replace(" ", "", $tab_title) == 'SystemManagement' ? 'active' : ''; ?>">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_{{ str_replace(' ', '', $tab_title) }}">{{ $tab_title }}</a>
                                </li>
                                @endforeach
                            </ul>

                            <div class="tab-content" id="myTabContent">
                            	@foreach($pa_result_array as $tab_title => $pa_info)
                                <div class="tab-pane fade show <?php echo str_replace(" ", "", $tab_title) == 'SystemManagement' ? 'active' : ''; ?>" id="tab_{{ str_replace(' ', '', $tab_title) }}" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-300 gy-7" style="width:100%">
                                            <thead>
                                                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                    <th style="width:2%;"><input type="checkbox" class="group-checkable CheckAll"  name="checkall_{{ str_replace(' ', '', $tab_title)  }}" id="CHECKALL_{{ str_replace(' ', '', $tab_title)  }}" value="1" /></th>
                                                    <th style="width:98%;">Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pa_info as $index => $pa_priv_info )
                                                <tr>
                                                    <td style="width:3%;">
                                                        <input type="checkbox" <?php echo ( count($rp_result_array) > 0 && isset($rp_result_array[ $pa_priv_info['code'] ]) && $rp_result_array[ $pa_priv_info['code'] ] == 'allow' ) ? "checked='checked'" : ""; ?> name="<?php echo $pa_priv_info['code']; ?>" id="<?php echo strtoupper($pa_priv_info['code']); ?>" value="1" />
                                                    </td>
                                                    <td style="width:97%;"><?php echo $pa_priv_info['description']; ?></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endforeach
                            </div>

        			     </div>
                    </div>
                </div>
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

