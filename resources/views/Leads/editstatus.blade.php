<?php
/***********************************************************
editstatus.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Feb 8, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


?>
@extends('layouts.layout',['page_title' => "Leads Management"])

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
<script type="text/javascript" src="{{ url('js/modules/leadsstatus.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/saveleadstatus.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Edit Status
				</h3>
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
             <form name="frm_save_status" id="FORM_SAVE_STATUS">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                       <input type="hidden" name="ls_id" id="LS_ID" value="{{ $status_info->ls_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Lead Status Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Status Title <span class="required"> * </span></label>
                                    <input type="text" name="ls_status_title" id="LS_STATUS_TITLE" class="form-control" required="required" maxlength="255"  value="{{ $status_info->ls_status_title }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Status Color</label>
                                <input type="color" name="ls_status_color" id="LS_STATUS_COLOR" class="form-control" required="required" maxlength="8"  value="{{ $status_info->ls_status_color }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Parent Status</label>
                                 <select class="bs-select form-control" name="ls_parent_status" id="LS_PARENT_STATUS" data-actions-box="true">
                                        <option value="">No Parent</option>
                                        @foreach ( $lst_lead_status as $key => $ls_info )
                                                <option value="{{ $ls_info->ls_id }}" {{ $status_info->ls_parent_status == $ls_info->ls_id ? "selected" : "" }} >{{ $ls_info->ls_status_title }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Status Description</label>
								<textarea name="ls_status_description" id="LS_STATUS_DESCRIPTION" class="form-control" style="width:100%;height:250px;">{{ $status_info->ls_status_description }}</textarea>
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