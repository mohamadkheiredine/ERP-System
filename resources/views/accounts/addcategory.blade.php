<?php
/***********************************************************
addcategory.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>
@extends('layouts.layout',['page_title' => "Clients Management > Add New Category"])

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
<script type="text/javascript" src="{{ url('js/modules/clientcategories.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/saveclientcategories.js') }}"></script>
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
                                     <form name="frm_save_category" id="FORM_SAVE_CATEGORY">
                                        <div class="form-body">
                                             <span id="hidden_fields">
                                              <div class="form-group">
                                                {!! csrf_field() !!}
                                                 </div>
                                            </span>
                                            <div class="alert alert-success" style="display:none">
                                    				<strong>Success!</strong> Client Category Information is saved successfully!
                                    			</div>
                                    			<div class="alert alert-danger" style="display:none">
                                    				<strong>Error!</strong> You have some form errors. Please check below.
                                    			</div>
                                            <div class="row">
                                            	<div class="col-md-12" align="left">
                                            		<label>Profile Picture </label>
                                            	</div>
                                                 <div class="col-md-4">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                            <img id="AVATAR_PIC" height="120" src="{{ url('images/NoImageAvailable.jpg') }}" alt="" /> </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                            
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="clearfix margin-top-10">
                                                        <div>
                                                            <span class="btn default btn-file" style="text-align: left;">
                                                                <span class="fileinput-new"> Select image </span><br/>
                                                                <input type="file" name="cc_avatar_pic" id="CC_AVATAR_PIC" /> </span>
                                                        </div>
                                                        <br>
                                                        <span class="label label-danger"> NOTE! </span><br><br>
                                                        <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> Category Ref <span class="required"> * </span></label>
                                                            <input type="text" name="cc_category_ref" id="CC_CATEGORY_REF" class="form-control" required="required" maxlength="15"  value="" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Category Name <span class="required"> * </span></label>
                                                        <input type="text" name="cc_category_name" id="CC_CATEGORY_NAME" class="form-control" required="required" maxlength="100"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Category Parent</label>
                                                        <select class="bs-select form-control" name="fk_cc_id" id="FK_CC_ID" data-actions-box="true">
                                                                <option value="">No Parent</option>
                                                                @foreach ( $lst_client_categories as $key => $category_info )
                                                                        <option value="{{ $category_info->cc_id }}">{{ $category_info->cc_category_name }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                     <div class="form-group">
                                                        <label class="control-label"> Category Description <span class="required"> * </span></label><br/>
                                                        <textarea style="width:100%;height:250px;resize:none" id="CC_CATEGORY_DESCRIPTION"  class="form-control" name="cc_category_description"  cols=""></textarea>
                                                     </div>
                                                </div>
                                            </div>
                                           <div class="row" style="height:5px;"></div>
                                            <div class="row">
                                                <div class="col-md-9"></div>
                                                <div class="col-md-3" align="right">
                                                     <button type="submit" name="btn_save_category" id="BTN_SAVE_CATEGORY"  class="btn btn-info">Save</button>
                                                    <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
							</div>
					   </div>

@endsection