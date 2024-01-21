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
<link href="{{ url('theme/style/src/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('plugins')
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
    <script src="{{ url('theme/style/src/assets/js/scripts.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/drawing.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/warehouses.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/inventory/warehousesettings.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Warehouses</h3>
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
                                <div class="stepper stepper-pills" id="stepper_settings">
                                <div class="stepper-nav flex-center flex-wrap mb-10">
                                    <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                                        <!--begin::Wrapper-->
                                        <div class="stepper-wrapper d-flex align-items-center">
                                            <!--begin::Icon-->
                                            <div class="stepper-icon w-40px h-40px WarehouseDimensions">
                                                <i class="stepper-check fas fa-check"></i>
                                                <span class="stepper-number">1</span>
                                            </div>
                                            <!--end::Icon-->
                                
                                            <!--begin::Label-->
                                            <div class="stepper-label WarehouseDimensions">
                                                <h3 class="stepper-title">
                                                    Step 1
                                                </h3>
                                
                                                <div class="stepper-desc">
                                                    Warehouse Dimensions
                                                </div>
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Wrapper-->
                                
                                        <!--begin::Line-->
                                        <div class="stepper-line h-40px"></div>
                                        <!--end::Line-->
                                    </div>
                                    <!--end::Step 1-->
                                
                                    <!--begin::Step 2-->
                                    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                                        <!--begin::Wrapper-->
                                        <div class="stepper-wrapper d-flex align-items-center">
                                             <!--begin::Icon-->
                                            <div class="stepper-icon w-40px h-40px WarehouseZones">
                                                <i class="stepper-check fas fa-check"></i>
                                                <span class="stepper-number">2</span>
                                            </div>
                                            <!--begin::Icon-->
                                
                                            <!--begin::Label-->
                                            <div class="stepper-label WarehouseZones">
                                                <h3 class="stepper-title">
                                                    Step 2
                                                </h3>
                                
                                                <div class="stepper-desc">
                                                    Warehouse Zones
                                                </div>
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Wrapper-->
                                
                                        <!--begin::Line-->
                                        <div class="stepper-line h-40px"></div>
                                        <!--end::Line-->
                                    </div>
                                    <!--end::Step 2-->
                                
                                    <!--begin::Step 3-->
                                    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                                       <!--begin::Wrapper-->
                                        <div class="stepper-wrapper d-flex align-items-center">
                                            <!--begin::Icon-->
                                            <div class="stepper-icon w-40px h-40px WarehouseEmployees">
                                                <i class="stepper-check fas fa-check"></i>
                                                <span class="stepper-number">3</span>
                                            </div>
                                            <!--begin::Icon-->
                                
                                            <!--begin::Label-->
                                            <div class="stepper-label WarehouseEmployees">
                                                <h3 class="stepper-title">
                                                    Step 3
                                                </h3>
                                
                                                <div class="stepper-desc">
                                                     Warehouse Employees
                                                </div>
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Wrapper-->
                                
                                        <!--begin::Line-->
                                        <div class="stepper-line h-40px"></div>
                                        <!--end::Line-->
                                    </div>
                                    <!--end::Step 3-->
                                
                                    <!--begin::Step 4-->
                                    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                                        <!--begin::Wrapper-->
                                        <div class="stepper-wrapper d-flex align-items-center">
                                            <!--begin::Icon-->
                                            <div class="stepper-icon w-40px h-40px WarehouseLoad">
                                                <i class="stepper-check fas fa-check"></i>
                                                <span class="stepper-number">4</span>
                                            </div>
                                            <!--begin::Icon-->
                                
                                            <!--begin::Label-->
                                            <div class="stepper-label WarehouseLoad">
                                                <h3 class="stepper-title">
                                                    Step 4
                                                </h3>
                                
                                                <div class="stepper-desc">
                                                    Warehouse Load
                                                </div>
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Wrapper-->
                                    </div>
                                    <!--end::Step 4-->
                                </div>
                                <!--end::Nav-->
                                
                                    <!--begin::Form-->
                                        <!--begin::Group-->
                                        <div class="mb-5">
                                            <!--begin::Step 1-->
                                            <div class="flex-column current" data-kt-stepper-element="content">
                                            	<div class="row">
                                				 		<div class="col-md-3"></div>
                                				 		<div class="col-md-7" id="WAREHOUSEDIMENSIONS" ></div>
                                				 		<div class="col-md-2"></div>
                                				 		<div class="col-md-12" id="CanvasPage" style="height:600px;" >
                                				 			<canvas width="800" height="600" id="WAREHOUSEDRAWING" style="overflow: scroll;" ></canvas>
                                				 		</div>
                                				 </div>
                                            </div>
                                            <!--begin::Step 1-->
                                
                                            <!--begin::Step 1-->
                                            <div class="flex-column" data-kt-stepper-element="content"  id="m_wizard_warehouse_zones">
                                                
                                            </div>
                                            <!--begin::Step 1-->
                                
                                            <!--begin::Step 1-->
                                            <div class="flex-column" data-kt-stepper-element="content"   id="m_wizard_warehouse_employees">
                                                 
                                            </div>
                                            <!--begin::Step 1-->
                                
                                            <!--begin::Step 1-->
                                            <div class="flex-column" data-kt-stepper-element="content"  id="m_wizard_warehouse_load">
                                               
                                            </div>
                                            <!--begin::Step 1-->
                                        </div>
                                        <!--end::Group-->
                                
                                        <!--begin::Actions-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Wrapper-->
                                            <div class="me-2">
                                                <button type="button" class="btn btn-dark" data-kt-stepper-action="previous">
                                                    Back
                                                </button>
                                            </div>
                                            <!--end::Wrapper-->
                                
                                            <!--begin::Wrapper-->
                                            <div>
                                                <button type="button" class="btn btn-primary" data-kt-stepper-action="submit">
                                                    <span class="indicator-label">
                                                        Submit
                                                    </span>
                                                    <span class="indicator-progress">
                                                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                
                                                <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                                                    Continue
                                                </button>
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Actions-->
                                </div>
                                <!--end::Stepper-->
                             
                             
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