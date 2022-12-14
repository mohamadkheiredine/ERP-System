<?php
/***********************************************************
editplan.blade.php
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
#minutes{
	margin-bottom: 0px !important;
	font-size:16px;
}
#seconds{
	margin-bottom: 0px !important;
	font-size:16px;
}
#hours{
	margin-bottom: 0px !important;
	font-size:16px;
}
</style>
@endsection
@section('plugins')
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/productionplans.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/production/saveplaninfo.js') }}"></script>
<<script type="text/javascript">
function pad(val) {
	  valString = val + "";
	  if(valString.length < 2) {
	     return "0" + valString;
	     } else {
	     return valString;
	     }
	}
 
	function setTime(HoursLabel , minutesLabel, secondsLabel) {
		 var hours 	= $("#hours").html();
		 var minutes = $("#minutes").html();
		 var seconds = $("#seconds").html();
		 hours   = parseInt(hours);
		 minutes = parseInt(minutes);
		 seconds = parseInt(seconds);
		 if(seconds == 59)
		 {

			 if(minutes == 59)
			 {
				 hours++;
				 minutes = 0;
			 }
			 else
			 {
				 minutes++;
			 }
			 
			 
			 seconds = 0;
		 }
		 else
		 {
			 seconds++;
		 }
		 
		 
		 HoursLabel.innerHTML = pad(hours);
	    minutesLabel.innerHTML = pad(minutes);
	    secondsLabel.innerHTML = pad(seconds);
	    }

	function set_timer() {
		HoursLabel = document.getElementById("hours");
	    minutesLabel = document.getElementById("minutes");
	    secondsLabel = document.getElementById("seconds");
	    my_int = setInterval(function() { setTime(HoursLabel , minutesLabel, secondsLabel)}, 1000);
	}

	function stop_timer() {
	  clearInterval(my_int);
	}

</script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			
				<div class="row">
					<div class="col-md-5 col-xs-5">
						<div class="m-portlet__head-title">
    						<h3 class="m-portlet__head-text">
            					Edit Existing Plan
            				</h3>
						</div>
					</div>
					<div class="col-md-7 col-xs-7">
						
					</div>
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
											<li class="m-nav__item" id="AssignToUser">
												<a href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-user-ok"></i>
													<span class="m-nav__link-text">
														Assign To
													</span>
												</a>
											</li>
											@if($plan_info->pp_approval_user == 0)
											<li class="m-nav__item" id="PlanApproval">
												<a  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-user-ok"></i>
													<span class="m-nav__link-text">
														Production Plan Approval
													</span>
												</a>
											</li>
											@endif
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
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<button type="button" name="btn_start_production"  style="display:{{ $plan_info->pp_start_production == 1 ? 'none' : '' }}"  id="BTN_START_PRODUCTION" class="btn btn-accent m-btn m-btn--icon">
					<span>
						<i class="fa fa-play"></i>
						<span>
							Start Production
						</span>
					</span>
				</button>
				<button type="button" name="btn_quality_check"  id="BTN_QUALITY_CHECK"  style="display:{{ $plan_info->pp_run_production == 1 ? '' : 'none' }}" class="btn btn-outline-brand m-btn m-btn--icon">
					<span>
						<i class="fa fa-check-square"></i>
						<span>
							Quality Check
						</span>
					</span> 
				</button>
				<button type="button" name="btn_pause_production" id="BTN_PAUSE_PRODUCTION" style="display:{{ $plan_info->pp_start_production == 1 ? '' : 'none' }}" class="btn btn-warning m-btn m-btn--icon">
					<span>
						<i class="fa  fa-pause"></i>
						<span>
							Pause
						</span>
					</span>
				</button>
				<button type="button" name="btn_block_production"  id="BTN_BLOCK_PRODUCTION" style="display:{{ $plan_info->pp_run_production == 1 ? '' : 'none' }}" class="btn btn-danger m-btn m-btn--icon">
					Block
				</button>
				<button type="button" name="btn_scrap" id="BTN_SCRAP" style="display:{{ $plan_info->pp_run_production == 1 ? '' : 'none' }}" class="btn btn-outline-success m-btn m-btn--icon m-btn--outline-2x">
					<span>
						<i class="la la-file-excel-o"></i>
						<span>
							Scrap
						</span>
					</span>
				</button>
				<button type="button" name="btn_quality_alert"  id="BTN_QUALITY_ALERT"  style="display:{{ $plan_info->pp_run_production == 1 ? '' : 'none' }}" class="btn btn-outline-brand m-btn m-btn--icon">
					<span>
						<i class="fa  fa-warning"></i>
						<span>
							Quality Alert
						</span>
					</span> 
				</button>
				<button  id="BTN_MAINT_REQUEST" name="btn_maint_request"  style="display:{{ $plan_info->pp_run_production == 1 ? '' : 'none' }}"  type="button" class="btn btn-outline-accent m-btn m-btn--outline-2x ">
					<span>
						<i class="flaticon-file-1"></i>
						<span>
							Maintenance Request
						</span>
					</span> 
				</button>
			</div>
		</div>
			<div class="row">
				<div class="col-md-12" style="height:50px;">&nbsp;</div>
			</div>
             <form name="frm_save_plan" id="FORM_SAVE_PLAN">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="pp_id" id="PP_ID" value="{{ $plan_info->pp_id }}" />
                        <input type="hidden" name="timer_minutes" id="timer_minutes" value="{{ $timer_array[1] }}" />
                        <input type="hidden" name="timer_seconds" id="timer_seconds" value="{{ $timer_array[2] }}" />
                        <input type="hidden" name="timer_hours" id="timer_hours" value="{{ $timer_array[0] }}" />
                        <input type="hidden" name="production_run" id="production_run" value="0" />
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
                                    <input type="text" name="pp_plan_code" id="PP_PLAN_CODE" class="form-control" required="required" maxlength="15"  value="{{ $plan_info->pp_plan_code }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Plan Label <span class="required"> * </span></label>
                                <input type="text" name="pp_plan_label" id="PP_PLAN_LABEL" class="form-control" required="required" maxlength="255"  value="{{ $plan_info->pp_plan_label }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Plan Manager </label>
                                <select class="bs-select form-control" name="pp_production_manager" id="PP_PRODUCTION_MANAGER" data-actions-box="true">
                                        <option value="">-- Plan Manager --</option>
                                        @foreach ( $lst_users as $key => $user_info )
                                                <option {{ $plan_info->pp_production_manager == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
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
                                                <option {{ $plan_info->pp_plan_status == $status_info->ps_id ? "selected" : "" }} value="{{ $status_info->ps_id }}">{{ $status_info->ps_status_title }}</option>
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
                                                <option {{ $plan_info->pp_customer_id == $customer_info->ic_id ? "selected" : "" }} value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>  
                    </div>
                   <div class="row" style="height:5px;"></div>
                   <div class="row">
                   		<div class="col-md-12">
                   			<ul class="nav nav-tabs" role="tablist"> 
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#DescriptionTab">
										Work Instruction
									</a>
								</li> 
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#ProductTab">
										Products
									</a>
								</li> 
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#TimeTrackingTab">
										Time Tracking
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#QualityCheckTab">
										Quality Check
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane" id="ProductTab" role="tabpanel">
									<div class="row">
										<div class="col-md-12" id="LstPlanProducts">
										</div>
									</div>
									<div class="row">
										<div class="col-md-12" align="right">
											<button type="button" name="btn_add_product" id="BTN_ADD_PRODUCT" class="btn btn-success" >Add Product</button>
										</div>
									</div>
								</div>
								<div class="tab-pane active" id="DescriptionTab" role="tabpanel">
									<div class="row">
										 <div class="col-md-12">
                                             <div class="form-group">
                                                <label class="control-label"> Work Instruction <span class="required"> * </span></label><br/>
                                                <textarea style="width:100%;height:250px;resize:none" id="PP_PLAN_DESCRIPTION"  class="form-control" name="pp_plan_description"  cols="">{{ $plan_info->pp_plan_description }}</textarea>
                                             </div>
                                        </div>
									</div>
								</div>
								<div class="tab-pane" id="TimeTrackingTab" role="tabpanel">
									<div class="row">
										 <div class="col-md-4">
                                             <div class="form-group">
                                                <label class="control-label"> Prepare Date <span class="required"> * </span></label>
                                                <input type="text" name="pp_prepare_date" id="PP_PREPARE_DATE" class="form-control"  readonly="readonly" maxlength="255"  value="{{ $plan_info->pp_prepare_date }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                             <div class="form-group">
                                                <label class="control-label"> End Date</label>
                                                <input type="text" name="pp_end_date" id="PP_END_DATE" class="form-control"  maxlength="255"  readonly="readonly"  value="{{ $plan_info->pp_end_date }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                             <div class="form-group">
                                                <label class="control-label"> Start Date</label>
                                                <input type="text" name="pp_start_date" id="PP_START_DATE" class="form-control"  maxlength="255"  readonly="readonly"  value="{{ $plan_info->pp_start_date }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                             <div class="form-group">
                                                <label class="control-label"> Finish Date</label>
                                                <input type="text" name="pp_finish_date" id="PP_FINISH_DATE" class="form-control"  maxlength="255"  readonly="readonly" value="{{ $plan_info->pp_finish_date }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                             <div class="form-group">
                                                <label class="control-label"> Estimation Time</label><br/>
                                                 <span class="m-badge m-badge--accent m-badge--wide" style="font-size:16px;">{{ $plan_info->pp_estimation_time }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                             <div class="form-group">
                                                <label class="control-label"> Real Duration </label><br/>
                                                 <span class="m-badge m-badge--focus m-badge--wide">
                                                 	<label id="hours">{{ $timer_array[0] }}</label><span class='bigger'>:</span><label id="minutes">{{ $timer_array[1] }}</label><span class='bigger'>:</span><label id="seconds">{{ $timer_array[2] }}</label>
                                                 </span>
                                            </div>
                                        </div>
									</div>
								</div>
								<div class="tab-pane" id="QualityCheckTab" role="tabpanel">
									<div class="row">
										<div class="col-md-12" id="LstQualityCheck">
											
										</div>
									</div>
								</div>
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
<div class="modal fade" id="PlanItemsModel" tabindex="-1" role="dialog" aria-labelledby="PlanItemsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="PlanItemsModalLabel">Plan Items</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<form name="frm_plan_item" id="FRM_PLAN_ITEMS">
      		       <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="fk_plan_id" id="FK_PLAN_ID" value="{{ $plan_info->pp_id }}" />
                    </span>
      		<div class="row">
          		<div class="col-md-12">
          			<div class="form-group">
          				<label> Product </label><br/>
          				<select name="pi_item_product" id="PI_ITEM_PRODUCT" style="width:100%;" class="form-control">
          				         <option value="">-- Select product --</option>
          					@foreach($lst_products as $index => $product_info)
          						<option value="{{ $product_info->p_id }}" >{{ $product_info->p_product_name }}</option>
          					@endforeach
          				</select>
          			</div>
          		</div>
          		<div class="col-md-12">
          			<div class="form-group">
          				<label> Quanity </label><br/>
          				<input type="number" class="form-control" name="pi_item_quanity" id="PI_ITEM_QUANTITY" required="required" min="0.0000" max="999999999999.0000" />
          			</div>
          		</div> 
          		<div class="col-md-12" align="right">
      	  			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        			<button type="submit" name="btn_insert_item" id="BTN_INSERT_ITEM" class="btn btn-primary">Save Item</button>
          		</div>
          	</div>
      	</form> 
      </div>
      <div class="modal-footer">
      
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="PlanQualityCheckModel" tabindex="-1" role="dialog" aria-labelledby="QualityCheckModelLabel"  aria-hidden="true">
  <div class="modal-dialog" role="document"><!-- PlanQualityCheckModel -->
    <div class="modal-content" style="width:800px;">
      <div class="modal-header">
        <h5 class="modal-title" id="QualityCheckModelLabel"> Quality Check </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<form name="frm_quality_check" id="FRM_QUALITY_CHECK">
      		       <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="fk_plan_id" id="FK_PLAN_ID" value="{{ $plan_info->pp_id }}" />
                        <input type="hidden" name="qc_id" id="QC_ID" value="" />
                    </span>
      		<div class="row">
          		<div class="col-md-6">
          			<div class="form-group">
          				<label> Check Label </label><br/>
          				<input type="text" name="qc_check_label" class="form-control" value="" maxlength="255" />
          			</div>
          		</div>
          		<div class="col-md-6">
          			<div class="form-group">
          				<label> Team </label><br/>
          				<select name="qc_team_id" id="QC_TEAM_ID" style="width:100%;" class="form-control">
          				         <option value="">-- Select Team --</option>
          					@foreach($lst_teams as $index => $team_info)
          						<option value="{{ $team_info->ut_id }}" >{{ $team_info->ut_team }}</option>
          					@endforeach
          				</select>
          			</div>
          		</div>
          		<div class="col-md-6">
          			<div class="form-group">
          				<label> Product </label><br/>
          				<select name="qc_product_id" id="QC_PRODUCT_ID" style="width:100%;" class="form-control">
          				         <option value="">-- Select product --</option>
          					@foreach($lst_products as $index => $product_info)
          						<option value="{{ $product_info->p_id }}" >{{ $product_info->p_product_name }}</option>
          					@endforeach
          				</select>
          			</div>
          		</div>
          		<div class="col-md-6">
          			<div class="form-group">
          				<label> Status </label><br/>
          				<select name="qc_status_id" id="QC_STATUS_ID" style="width:100%;" class="form-control">
          				         <option value="">-- Select Status --</option>
          					@foreach($lst_check_status as $index => $cs_info)
          						<option value="{{ $cs_info->cs_id }}" >{{ $cs_info->cs_status_title }}</option>
          					@endforeach
          				</select>
          			</div>
          		</div>
          		<div class="col-md-12">
          			<div class="form-group">
          				<label> Comment </label><br/>
          				<textarea style="width:100%;height:250px;"  name="qc_description" id="QC_DESCRIPTION"  class="form-control" ></textarea>
          			</div>
          		</div> 
          		<div class="col-md-12" align="right">
      	  			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        			<button type="button" name="btn_check_pass" id="BTN_CHECK_PASS" class="btn btn-primary">Pass</button>
        			<button type="button" name="btn_check_fail" id="BTN_CHECK_FAIL" class="btn btn-danger">Fail</button>
        			<button type="submit" name="btn_save_check" id="BTN_SAVE_CHECK" class="btn btn-success">Save Check</button>
          		</div>
          	</div>
      	</form> 
      </div>
      <div class="modal-footer">
      
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="AssignPlanModel" tabindex="-1" role="dialog" aria-labelledby="AssignPlanModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="AssignPlanModalLabel">Plan Assign To</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<form name="frm_plan_assign_to" id="FRM_PLAN_ASSIGN_TO">
      		       <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="fk_plan_id" id="FK_PLAN_ID" value="{{ $plan_info->pp_id }}" />
                    </span>
      		<div class="row">
          		<div class="col-md-12">
          			<div class="form-group">
          				<label> Assign To </label><br/>
          				<select name="pp_assign_to" id="PP_ASSIGN_TO" style="width:100%;" class="form-control">
          				         <option value="">-- Select User --</option>
          					@foreach($lst_prod_dep_users as $index => $user_info)
          						<option value="{{ $user_info->id }}" >{{ $user_info->u_fullname }}</option>
          					@endforeach
          				</select>
          			</div>
          		</div>
          		<div class="col-md-12" align="right">
      	  			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        			<button type="submit" name="btn_assign_user" id="BTN_ASSIGN_USER" class="btn btn-primary">Save Item</button>
          		</div>
          	</div>
      	</form> 
      </div>
      <div class="modal-footer">
      
      </div>
    </div>
  </div>
</div>
@if($plan_info->pp_approval_user == 0)
<div class="modal fade" id="ProductionPlanApprovalModel" tabindex="-1" role="dialog" aria-labelledby="labelProductionPlanApprovalModel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelProductionPlanApprovalModel">Plan Approval</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<form name="frm_plan_approval" id="FRM_PLAN_APPROVAL">
      		       <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="fk_plan_id" id="FK_PLAN_ID" value="{{ $plan_info->pp_id }}" />
                    </span>
      		<div class="row">
          		<div class="col-md-12">
          			<div class="form-group">
          				<label> Approval Message </label><br/>
          				 <textarea style="width:100%;height:250px;" name="pp_approve_note" class="form-control" ></textarea>
          			</div>
          		</div>
          		<div class="col-md-12" align="right">
      	  			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        			<button type="submit" name="btn_approval_user" id="BTN_APPROVAL_USER" class="btn btn-primary">Plan Approve</button>
          		</div>
          	</div>
      	</form> 
      </div>
      <div class="modal-footer">
      
      </div>
    </div>
  </div>
</div>
@endif
@endsection