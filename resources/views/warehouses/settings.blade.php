<?php
/***********************************************************
settings.blade.php
Product :
Version : 1.0
Release : 1
Date Created : May 22, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Configuration of all information related to the Warehouse already selected
***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Warehouse Management"])

@section('themes')
<style>
th{
    cursor: pointer;
}
#ModelPopUp{
	width:800px;
}
.m-content{
	padding:0px 0px !important;
}
.m-portlet__head{
	padding:0px 0px !important;
}
.m-portlet__body{
	padding:0px 0px !important;
}

</style>
<link href="//www.amcharts.com/lib/3/plugins/export/export.css" rel="stylesheet" type="text/css" />
@endsection
@section('plugins')
<script src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/radar.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/pie.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/plugins/tools/polarScatter/polarScatter.min.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>
<script src="{{ url('default/assets/demo/default/custom/components/forms/wizard/wizard.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ url('js/modules/drawing.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/warehouses.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/inventory/warehousesettings.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text"></h3>
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
                 <form name="frm_warehouse_settings" id="FORM_WAREHOUSE_SETTINGS">
                    <div class="form-body">
                         <span id="hidden_fields">
                        	 {!! csrf_field() !!}
                        	 <input type="hidden" name="warehouse_id" value="{{ $w_id }}" />
                        	 <input type="hidden" name="warehouse_type_id" value="{{ $wareHouseInfo->w_warehouse_size_type }}" />
                        	 <input type="hidden" name="tab" id="TAB"  value="warehouse_dimension" />
                        </span>
                        <div class="row">
                            <div class="col-md-12">
								<div class="m-content">
            						<div class="row">
            							<div class="col-md-12">
            								<!--Begin::Main Portlet-->
            								<div class="m-portlet">
            									<!--begin: Portlet Head-->
            									<div class="m-portlet__head">
            										<div class="m-portlet__head-caption">
            											<div class="m-portlet__head-title">
            												<h3 class="m-portlet__head-text">
            													<small></small>
            												</h3>
            											</div>
            										</div>
            										<div class="m-portlet__head-tools">
            											<ul class="m-portlet__nav">
            												<li class="m-portlet__nav-item">
            													<a href="#" data-toggle="m-tooltip" class="m-portlet__nav-link m-portlet__nav-link--icon" data-direction="left" data-width="auto" title="Get help with filling up this form">
            														<i class="flaticon-info m--icon-font-size-lg3"></i>
            													</a>
            												</li>
            											</ul>
            										</div>
            									</div>
            									<!--end: Portlet Head-->
            			<!--begin: Form Wizard-->
            									<div class="m-wizard m-wizard--1 m-wizard--success" id="m_wizard">
            										<!--begin: Message container -->
            										<div class="m-portlet__padding-x">
            											<!-- Here you can put a message or alert -->
            										</div>
            										<!--end: Message container -->
            				<!--begin: Form Wizard Head -->
            										<div class="m-wizard__head m-portlet__padding-x">
            											<!--begin: Form Wizard Progress -->
            											<div class="m-wizard__progress">
            												<div class="progress">
            													<div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            												</div>
            											</div>
            											<!--end: Form Wizard Progress -->
            					<!--begin: Form Wizard Nav -->
            											<div class="m-wizard__nav">
            												<div class="m-wizard__steps">
            													<div class="m-wizard__step m-wizard__step--current" data-wizard-target="#m_wizard_warehouse_dimensions">
            														<div class="m-wizard__step-info">
            															<a href="#" class="m-wizard__step-number WarehouseDimensions">
            																<span>
            																	<span>
            																		1
            																	</span>
            																</span>
            															</a>
            															<div class="m-wizard__step-line">
            																<span></span>
            															</div>
            															<div class="m-wizard__step-label">
            																Warehouse Dimensions
            															</div>
            														</div>
            													</div>
            													<div class="m-wizard__step" data-wizard-target="#m_wizard_warehouse_zones">
            														<div class="m-wizard__step-info">
            															<a href="#" class="m-wizard__step-number WarehouseZones">
            																<span>
            																	<span>
            																		2
            																	</span>
            																</span>
            															</a>
            															<div class="m-wizard__step-line">
            																<span></span>
            															</div>
            															<div class="m-wizard__step-label">
            																Warehouse Zones
            															</div>
            														</div>
            													</div>
            													<div class="m-wizard__step" data-wizard-target="#m_wizard_warehouse_employees">
            														<div class="m-wizard__step-info">
            															<a href="#" class="m-wizard__step-number WarehouseEmployees">
            																<span>
            																	<span>
            																		3
            																	</span>
            																</span>
            															</a>
            															<div class="m-wizard__step-line">
            																<span></span>
            															</div>
            															<div class="m-wizard__step-label">
            																Warehouse Employees 
            															</div>
            														</div>
            													</div>
            													<div class="m-wizard__step" data-wizard-target="#m_wizard_warehouse_load">
            														<div class="m-wizard__step-info">
            															<a href="#" class="m-wizard__step-number WarehouseLoad">
            																<span>
            																	<span>
            																		4
            																	</span>
            																</span>
            															</a>
            															<div class="m-wizard__step-line">
            																<span></span>
            															</div>
            															<div class="m-wizard__step-label">
            																Warehouse Load
            															</div>
            														</div>
            													</div>
            												</div>
            											</div>
            											<!--end: Form Wizard Nav -->
            										</div>
            										<!--end: Form Wizard Head -->
            				<!--begin: Form Wizard Form-->
            										<div class="m-wizard__form"> 
            												<!--begin: Form Body -->
            												<div class="m-portlet__body"> 
            													<div class="m-wizard__form-step m-wizard__form-step--current" id="m_wizard_warehouse_dimensions">
            														 <div class="row">
            														 		<div class="col-md-3"></div>
            														 		<div class="col-md-7" id="WAREHOUSEDIMENSIONS" ></div>
            														 		<div class="col-md-2"></div>
            														 		<div class="col-md-12" id="CanvasPage" style="height:600px;" >
            														 			<canvas width="800" height="600" id="WAREHOUSEDRAWING" style="overflow: scroll;" ></canvas>
            														 		</div>
            														 </div>
            													</div> 
            													<div class="m-wizard__form-step" id="m_wizard_warehouse_zones">
            														 
            													</div> 
            													<div class="m-wizard__form-step" id="m_wizard_warehouse_employees">
            														 
            													</div> 
            													<div class="m-wizard__form-step" id="m_wizard_warehouse_load">
            														 
            													</div>
            													<!--end: Form Wizard Step 4-->
            												</div>
            												<!--end: Form Body -->
            						<!--begin: Form Actions -->
            												<div class="m-portlet__foot m-portlet__foot--fit m--margin-top-40">
            													<div class="m-form__actions m-form__actions">
            														<div class="row">
            															<div class="col-lg-2"></div>
            															<div class="col-lg-4 m--align-left">
            																<a href="#" class="btn btn-secondary m-btn m-btn--custom m-btn--icon" data-wizard-action="prev">
            																	<span>
            																		<i class="la la-arrow-left"></i>
            																		&nbsp;&nbsp;
            																		<span>
            																			Back
            																		</span>
            																	</span>
            																</a>
            															</div>
            															<div class="col-lg-4 m--align-right">
            																<a href="#" id="SUBMIT_SETTINGS"  class="btn btn-primary m-btn m-btn--custom m-btn--icon" data-wizard-action="submit">
            																	<span>
            																		<i class="la la-check"></i>
            																		&nbsp;&nbsp;
            																		<span>
            																			Submit
            																		</span>
            																	</span>
            																</a>
            																<a href="#" id="SAVE_SETTINGS" class="btn btn-warning m-btn m-btn--custom m-btn--icon" data-wizard-action="next">
            																	<span>
            																		<span>
            																			Save & Continue
            																		</span>
            																		&nbsp;&nbsp;
            																		<i class="la la-arrow-right"></i>
            																	</span>
            																</a>
            															</div>
            															<div class="col-lg-2"></div>
            														</div>
            													</div>
            												</div> 
            										</div>
            										<!--end: Form Wizard Form-->
            									</div>
            									<!--end: Form Wizard-->
            								</div>
            								<!--End::Main Portlet-->
            							</div> 
            						</div>
            					</div>
                            </div>
                        </div>
                    </div>
                </form>
		</div>
   </div>
<div class="modal fade" id="EmployeeModel" tabindex="-1" role="dialog" aria-labelledby="EmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Warehouse Employees</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-md-12">
      			<div class="form-group">
      				<label>Employee</label><br/>
      				<select name="fk_user_id" id="FK_USER_ID" style="width:100%;" class="form-control">
      					@foreach($lst_users as $index => $user_info)
      						<option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
      					@endforeach
      				</select>
      			</div>
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" name="btn_save_employee" id="BTN_SAVE_EMPLOYEE" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection