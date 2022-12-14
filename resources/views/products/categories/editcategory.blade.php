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
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/productcategories.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/products/savecategories.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Edit Category
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