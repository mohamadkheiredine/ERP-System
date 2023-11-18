<?php
/***********************************************************
editcategory.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

{
    $image_src_url  = url('/')."/".Config::get('constants.CRM_PATH').$client_categories->cc_profile_base_src.$client_categories->cc_profile_file_name.".".$client_categories->cc_profile_extension;
    $image_src_path = public_path(). "/" .Config::get('constants.CRM_PATH').$client_categories->cc_profile_base_src.$client_categories->cc_profile_file_name.".".$client_categories->cc_profile_extension;
    
    if(strlen($client_categories->cc_profile_base_src) > 0 ){
        $img_src = $image_src_url;
    }else{
        $img_src = url('images/NoImageAvailable.jpg');
    }
    
}



?>
@extends('layouts.layout',['page_title' => "Clients Management > Edit Category Info"])

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
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Category</h3>
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
    <form name="frm_save_category" id="FORM_SAVE_CATEGORY">
                                        <div class="form-body">
                                             <span id="hidden_fields">
                                              <div class="form-group">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="cc_id" value="{{ $client_categories->cc_id }}" />
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
                                                            <img id="AVATAR_PIC" height="120" src="{{ $img_src }}" alt="" /> </div>
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
                                                            <input type="text" name="cc_category_ref" id="CC_CATEGORY_REF" class="form-control" required="required" maxlength="15"  value="{{ $client_categories->cc_category_ref }}" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Category Name <span class="required"> * </span></label>
                                                        <input type="text" name="cc_category_name" id="CC_CATEGORY_NAME" class="form-control" required="required" maxlength="100"  value="{{ $client_categories->cc_category_name }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Category Parent</label>
                                                        <select class="bs-select form-control" name="fk_cc_id" id="FK_CC_ID" data-actions-box="true">
                                                                <option value="">No Parent</option>
                                                                @foreach ( $lst_client_categories as $key => $category_info )
                                                                        <option {{ $client_categories->fk_cc_id == $category_info->cc_id ? "selected" : "" }} value="{{ $category_info->cc_id }}">{{ $category_info->cc_category_name }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                 <div class="col-md-12">
                                                     <div class="form-group">
                                                        <label class="control-label"> Category Description <span class="required"> * </span></label><br/>
                                                        <textarea style="width:100%;height:250px;resize:none" id="CC_CATEGORY_DESCRIPTION"  class="form-control" name="cc_category_description"  cols="">{{ $client_categories->cc_category_description }}</textarea>
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