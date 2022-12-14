<?php
/***********************************************************
editleaditeminfo.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 15, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>



@extends('layouts.layout',['page_title' => "Leads Management > Edit Lead Service"])

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
<script type="text/javascript" src="{{ url('js/modules/leads.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/saveleaditems.js') }}"></script>
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
             <form name="frm_save_leaditems" id="FORM_SAVE_LEADITEMS">
                <div class="form-body">
                     <span id="hidden_fields"> 
                        {!! csrf_field() !!}
                        <input type="hidden" name="ci_id" value="{{ $CRMLeadItem->ci_id }}" />
                        <input type="hidden" name="cost_service_per_hour" value="{{ $LeadService->cs_cost_per_hour }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Lead Service Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>  
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Lead <span class="required"> * </span> </label>
                                     <select class="bs-select form-control" required="required" name="fk_lead_id" id="FK_LEAD_ID" data-actions-box="true">
                                            <option value=""> -- Lead -- </option>
                                            @foreach($lst_leads as $key => $lead_info)
                                                    <option {{ $CRMLeadItem->fk_lead_id == $lead_info->cl_id ? "selected" : ""  }} value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Service Category  <span class="required"> * </span> </label>
                                      <select class="bs-select form-control" required="required" name="fk_category_id" id="FK_CATEGORY_ID"  style="width:100%" data-actions-box="true">
                                            <option value=""> -- Service Category -- </option>
                                            @foreach($lst_service_categories as $key => $sc_info)
                                                    <option {{ $CRMLeadItem->fk_category_id  == $sc_info->sc_id ? "selected" : ""  }} value="{{ $sc_info->sc_id }}">{{ $sc_info->sc_category_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Services <span class="required"> * </span> </label>
                                    <div class="LeadServices">
                                    	<select class="bs-select form-control" required="required" name="fk_item_id" id="FK_ITEM_ID"  style="width:100%" data-actions-box="true">
                                                <option value=""> -- Services -- </option>
                                                @foreach($lst_services as $key => $service_info)
                                                        <option {{ $CRMLeadItem->fk_item_id  == $service_info->cs_id ? "selected" : ""  }} value="{{ $service_info->cs_id }}">{{ $service_info->cs_service_title }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Start Date <span class="required"> * </span></label><br/>
                                <input type="text" name="ci_start_date" required="required" id="CI_START_DATE" class="form-control" value="{{ $CRMLeadItem->ci_start_date }}" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">End Date <span class="required"> * </span></label><br/>
                                <input type="text" name="ci_end_date" required="required" id="CI_END_DATE" class="form-control" value="{{ $CRMLeadItem->ci_end_date }}" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Number of Hours <span class="required"> * </span></label><br/>
                                <input type="text" name="ci_item_nbr_of_hours" required="required" id="CI_ITEM_NBR_OF_HOURS" class="form-control" value="{{ $CRMLeadItem->ci_item_nbr_of_hours }}" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Cost <span class="required"> * </span></label><br/>
                                <input type="text" name="ci_total_cost" readonly="readonly" required="required" id="CI_TOTAL_COST" class="form-control" value="{{ $CRMLeadItem->ci_total_cost }}" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Price <span class="required"> * </span></label><br/>
                                <input type="text" name="ci_total_price" required="required" id="CI_TOTAL_PRICE" class="form-control" value="{{ $CRMLeadItem->ci_total_price }}" />
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Service Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CI_ITEM_DESCRIPTION"  class="form-control" name="ci_item_description"  cols="">{{ $CRMLeadItem->ci_item_description }}</textarea>
                             </div>
                        </div>
                     
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_lead_item" id="BTN_SAVE_LEAD_ITEM"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection