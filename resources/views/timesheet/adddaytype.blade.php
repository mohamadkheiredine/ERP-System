<?php
/***********************************************************
adddaytype.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 5, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>
@extends('layouts.layout',['page_title' => "Day Types Management > Add New Day Type"])

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
<script type="text/javascript" src="{{ url('js/modules/daytypes.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/savedaytypes.js') }}"></script>
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
             <form name="frm_save_daytype" id="FORM_SAVE_DAYTYPE">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Day Type Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Day Type <span class="required"> * </span></label>
                                    <input type="text" name="dt_day_type" id="DT_DAY_TYPE" class="form-control" required="required" maxlength="255"  value="" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Color <span class="required"> * </span></label>
                                <input type="color" name="dt_day_color" id="DT_DAY_COLOR" class="form-control" required="required"  value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> working hours <span class="required"> * </span></label>
                                <input type="number" step="0.1" min="0" max="12" step="0.1" name="dt_working_hours" id="DT_WORKING_HOURS" class="form-control" required="required"  value="1" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Show From Employees</label><br/>
                                <span class="m-switch m-switch--lg">
									<label>
										<input type="checkbox" name="dt_show_for_employee" id="DT_SHOW_FOR_EMPLOYEE" class="form-control" value="1" />
										<span></span>
									</label>
								</span>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_daytype" id="BTN_SAVE_DAYTYPE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection