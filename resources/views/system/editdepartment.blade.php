<?php
/***********************************************************
editform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 2, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Departments Management"])

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
<script type="text/javascript" src="{{ url('js/modules/departments.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/system/savedepartments.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Edit Department</h3>
			</div>
		</div>
		<div class="m-portlet__head-tools">
			<ul class="m-portlet__nav">
				<li class="m-portlet__nav-item">
					<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
						<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
							<i class="la la-ellipsis-h m--font-brand"></i>
						</a>
						<div class="m-dropdown__wrapper">
							<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
							<div class="m-dropdown__inner">
								<div class="m-dropdown__body">
									<div class="m-dropdown__content">
										<ul class="m-nav">
											<li class="m-nav__section m-nav__section--first">
												<span class="m-nav__section-text">
													Quick Actions
												</span>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="m-portlet__body">
             <form name="frm_save_department" id="FORM_SAVE_DEPARTMENT">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="d_id" value="{{ $department_info->sd_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Department Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
            			 
                    <div class="row">
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Department Code <span class="required"> * </span></label>
                                <input type="text" name="sd_department_code" id="SD_DEPARTMENT_CODE" class="form-control" required="required" maxlength="10"  value="{{ $department_info->sd_department_code }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Department Name <span class="required"> * </span></label>
                                <input type="text" name="sd_department_title" id="SD_DEPARTMENT_TITLE" class="form-control" required="required" maxlength="100"  value="{{ $department_info->sd_department_title }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Department Order <span class="required"> * </span></label>
                                    <input type="number" name="sd_department_order" id="SD_DEPARTMENT_ORDER" class="form-control" required="required" step="1"  value="{{ $department_info->sd_department_order }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Parent Department</label>
                                  <select name="sd_parent_department" id="SD_PARENT_DEPARTMENT" class="form-control m-select2" style="width:100%">
                                        <option value="0">--Select One--</option>
                                        @foreach( $lst_departments as $key => $dep_info)
                                         <option {{ $department_info->sd_parent_department == $dep_info->sd_id ? "selected" : "" }} value="{{ $dep_info->sd_id }}">{{ $dep_info->sd_department_title }}</option>
                                        @endforeach 
                                    </select>
                            </div>
                        </div>
                          <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Department Manager</label>
                                  <select name="sd_department_manager" id="SD_DEPARTMENT_MANAGER" class="form-control m-select2" style="width:100%">
                                        <option value="0">--Select One--</option>
                                        @foreach( $lst_managers as $key => $man_info)
                                         <option {{ $department_info->sd_department_manager == $man_info->id ? "selected" : "" }} value="{{ $man_info->id }}">{{ $man_info->u_fullname }}</option>
                                        @endforeach 
                                    </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Department Color</label>
                                    <input type="color" name="sd_department_color" id="SD_DEPARTMENT_COLOR" class="form-control" value="{{ $department_info->sd_department_color }}" />
                                </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_department" id="BTN_SAVE_DEPARTMENT"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection