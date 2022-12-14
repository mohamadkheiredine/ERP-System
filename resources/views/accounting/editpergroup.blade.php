<?php
/***********************************************************
editpergroup.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 21, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/
 
?>
@extends('layouts.layout',['page_title' => "Personalized Groups > Edit Existing Group"])

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
<script type="text/javascript" src="{{ url('js/modules/personalizedgroups.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/savepersgroups.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">

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
             <form name="frm_save_group" id="FORM_SAVE_GROUP">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                      <input type="hidden" name="pg_id" id="PG_ID" value="{{ $personalized_group_info->pg_id }}" /> 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Group Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Group Code  <span class="required"> * </span> </label>
                                    <input type="text" name="pg_group_code" id="PG_GROUP_CODE" class="form-control"  maxlength="15"  value="{{ $personalized_group_info->pg_group_code }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Label <span class="required"> * </span></label>
                                <input type="text" name="pg_group_label" id="PG_GROUP_LABEL" class="form-control" required="required" maxlength="255"  value="{{ $personalized_group_info->pg_group_label }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Calculated</label>
                                <ul class="LstradioBtn">
                                	<li><input type="radio" name="pg_group_calculated" {{ $personalized_group_info->pg_group_calculated == 1 ? "checked" : "" }}  value="1"  /><label>&nbsp;YES</label></li>
                                	<li><input type="radio" name="pg_group_calculated" {{ $personalized_group_info->pg_group_calculated == 0 ? "checked" : "" }}   value="0"  /><label>&nbsp;NO</label></li>
                                </ul>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Formula</label>
                                <input type="text" name="pg_group_formula" id="PG_GROUP_FORMULA" class="form-control" maxlength="255"  value="{{ $personalized_group_info->pg_group_formula }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Position </label>
                                    <input type="number" step="1" min="0" max="1000" name="pg_group_position" id="PG_GROUP_POSITION" class="form-control"  value="{{ $personalized_group_info->pg_group_position }}" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Is Active </label><br/>
                                    <span class="m-switch m-switch--icon m-switch--info">
            							<label>
            								<input type="checkbox" {{ $personalized_group_info->pg_is_active == 1 ? "checked" : "" }}  name="pg_is_active" value="1" />
            								<span class="ActualSwitch"></span>
            							</label>
            						</span>
                                </div>
                        </div> 
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> Group Comment </label>
                                <textarea class="form-control" id="PG_GROUP_COMMENT" name="pg_group_comment" style="width:100%;height:250px;resize:none" >{{ $personalized_group_info->pg_group_comment }}</textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_group" id="BTN_SAVE_GROUP"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection