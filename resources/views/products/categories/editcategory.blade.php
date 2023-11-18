<?php
/***********************************************************
editcategory.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Edit Product Category View
***********************************************************/

{
    $image_src_url  = url('/')."/".Config::get('constants.PRODUCTS_PATH').$product_categories->pc_avatar_base_src.$product_categories->pc_avatar_file_name.".".$product_categories->pc_avatar_extension;
    $image_src_path = public_path(). "/" .Config::get('constants.PRODUCTS_PATH').$product_categories->pc_avatar_base_src.$product_categories->pc_avatar_file_name.".".$product_categories->pc_avatar_extension;
 
    if(strlen($product_categories->pc_avatar_base_src) > 0 ){
        $img_src = $image_src_url;
    }else{
        $img_src = url('images/NoImageAvailable.jpg');
    }
    
}

?>



@extends('layouts.layout',['page_title' => "Product Categories Management" ])

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
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/productcategories.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/products/savecategories.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Lead</h3>
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
    <div class="card-body"><div class="card shadow-sm">
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
                        <input type="hidden" name="pc_id" value="{{ $product_categories->pc_id }}"  />
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Product Category Information is saved successfully!
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
                                    <input type="file" name="pc_avatar_pic" id="PC_AVATAR_PIC" /> </span>
                            </div>
                            <br>
                            <span class="label label-danger"> NOTE! </span><br><br>
                            <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-md-4" style="display:none">
                              <div class="form-group">
                                    <label class="control-label"> Category Code <span class="required"> * </span></label>
                                    <input type="text" name=" pc_cat_ref" id="PC_CAT_REF" class="form-control"  maxlength="100"  value="{{ $product_categories->pc_cat_ref }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Parent Category </label>
                                <select class="bs-select form-control" name="fk_pc_id" id="FK_PC_ID" data-actions-box="true">
                                        <option value="">No Parent</option>
                                        <?php foreach ( $lst_product_categories as $key => $category_info ) { ?>
                                                <option {{ $product_categories->fk_pc_id == $category_info->pc_id ? "selected" : ""  }} value="<?php echo $category_info->pc_id;  ?>"><?php echo $category_info->pc_category;  ?></option>
                                        <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Category Name <span class="required"> * </span></label>
                                <input type="text" name="pc_category" id="PC_CATEGORY" class="form-control" required="required" maxlength="100"  value="{{ $product_categories->pc_category }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Products use Serialnumber</label><br/>
								<input data-switch="true" type="checkbox"  name="pc_use_serial_number" id="PC_USE_SERIAL_NUMBER"  value="1"  {{ $product_categories->pc_use_serial_number == 1 ? 'checked="checked"' : "" }} data-on-text="Yes" data-handle-width="50" data-off-text="No" data-on-color="success" />
                            </div>
                        </div>
                                                <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Products Maintenance</label><br/>
                               	 <input data-switch="true" type="checkbox"  name="pc_maintenance_category" id="PC_MAINTENANCE_CATEGORY"  value="1"  {{ $product_categories->pc_maintenance_category == 1 ? 'checked="checked"' : "" }} data-on-text="Yes" data-handle-width="50" data-off-text="No" data-on-color="brand" />
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Category Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="PC_DESCRIPTION"  class="form-control" name="pc_description"  cols="">{{ $product_categories->pc_description }}</textarea>
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