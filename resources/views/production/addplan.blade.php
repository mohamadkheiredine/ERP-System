<?php
/***********************************************************
addplan.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 15, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Production Plan Management"])

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
<script type="text/javascript" src="{{ url('js/modules/productionplans.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/production/saveplaninfo.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Add New Plan
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
             <form name="frm_save_plan" id="FORM_SAVE_PLAN">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Production Plan Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Plan Code <span class="required"> * </span></label>
                                    <input type="text" name="pp_plan_code" id="PP_PLAN_CODE" class="form-control" required="required" maxlength="15"  value="{{ $plan_code }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Plan Label <span class="required"> * </span></label>
                                <input type="text" name="pp_plan_label" id="PP_PLAN_LABEL" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Plan Manager </label>
                                <select class="bs-select form-control" name="pp_production_manager" id="PP_PRODUCTION_MANAGER" data-actions-box="true">
                                        <option value="">-- Plan Manager --</option>
                                        @foreach ( $lst_users as $key => $user_info )
                                                <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> Plan Status</label>
                                <select class="bs-select form-control" name="pp_plan_status" id="PP_PLAN_STATUS" data-actions-box="true">
                                        <option value="">-- Plan Status --</option>
                                        @foreach ( $lst_plan_status as $key => $status_info )
                                                <option value="{{ $status_info->ps_id }}">{{ $status_info->ps_status_title }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> Plan Customer</label>
                                <select class="bs-select form-control" name="pp_customer_id" id="PP_CUSTOMER_ID" data-actions-box="true">
                                        <option value="">-- Customer --</option>
                                        @foreach ( $lst_customers as $key => $customer_info )
                                                <option value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Prepare Date</label>
                                <input type="text" name="pp_prepare_date" id="PP_PREPARE_DATE" class="form-control" readonly="readonly" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> End Date</label>
                                <input type="text" name="pp_end_date" id="PP_END_DATE" class="form-control" maxlength="255"  readonly="readonly"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Start Date</label>
                                <input type="text" name="pp_start_date" id="PP_START_DATE" class="form-control" maxlength="255"  readonly="readonly"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Finish Date</label>
                                <input type="text" name="pp_finish_date" id="PP_FINISH_DATE" class="form-control" maxlength="255"  readonly="readonly" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Estimation Time</label><br/>
                                <div class='input-group timepicker' id='PT_ESTIMATION_TIME' >
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="la la-clock-o"></i>
										</span>
									</div>
									<input type='text' id="PP_ESTIMATION_TIME" name="pp_estimation_time" class="form-control m-input" placeholder="Select time" type="text"/>
								</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Work Instruction <span class="required"> * </span></label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="PP_PLAN_DESCRIPTION"  class="form-control" name="pp_plan_description"  cols=""></textarea>
                             </div>
                        </div>

                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_plan" id="BTN_SAVE_PLAN"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection