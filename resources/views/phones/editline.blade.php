<?php
/***********************************************************
editline.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 21, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/
?>

@extends('layouts.layout',['page_title' => "Phone Line Management"])

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
<script type="text/javascript" src="{{ url('js/modules/phonelines.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/phones/savelines.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Edit Existing Line
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
     <form name="frm_save_line" id="FORM_SAVE_LINES">
        <div class="form-body">
             <span id="hidden_fields">
               {!! csrf_field() !!}
               <input type="hidden" name="pl_id" id="PL_ID" value="{{ $phone_lines->pl_id }}" />
            </span>
            <div class="alert alert-success" style="display:none">
    				<strong>Success!</strong> Phone Line Information is saved successfully!
    			</div>
    			<div class="alert alert-danger" style="display:none">
    				<strong>Error!</strong> You have some form errors. Please check below.
    			</div>
            <div class="row">
            	<div class="col-md-4">
                    <div class="form-group">
                        <label> Line Provider </label>
                        <select class="bs-select form-control" name="pl_phone_type" id="PL_PHONE_TYPE" data-actions-box="true">
                                <option value=""> Select Provider </option>
                                <option {{ $phone_lines->pl_phone_type == 1 ? "selected" : "" }} value="1"> Alfa </option>
                                <option {{ $phone_lines->pl_phone_type == 2 ? "selected" : "" }} value="2"> Mtc touch </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                      <div class="form-group">
                            <label class="control-label"> Line Label <span class="required"> * </span></label>
                            <input type="text" name="pl_line_title" id="PL_LINE_TITLE" class="form-control" required="required" maxlength="255"  value="{{ $phone_lines->pl_line_title }}" />
                        </div>
                </div> 
                <div class="col-md-4">
                      <div class="form-group">
                            <label class="control-label"> Line Number <span class="required"> * </span></label>
                            <input type="text" name="pl_line_number" id="PL_LINE_NUMBER" class="form-control" required="required" maxlength="15"  value="{{ $phone_lines->pl_line_number }}" />
                        </div>
                </div> 
                <div class="col-md-4">
                  	<div class="form-group">
                        <label class="control-label"> Total Units <span class="required"> * </span></label>
                        <input type="text" name="pl_total_units" id="PL_TOTAL_UNITS" class="form-control" required="required" maxlength="15"  value="{{ $phone_lines->pl_total_units }}" />
                    </div>
                </div> 
            </div>
           <div class="row" style="height:5px;"></div>
            <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-3" align="right">
                     <button type="submit" name="btn_save_line" id="BTN_SAVE_LINE"  class="btn btn-info">Save</button>
                    <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                </div>
            </div>
        </div>
    </form>
	</div>
</div>

@endsection