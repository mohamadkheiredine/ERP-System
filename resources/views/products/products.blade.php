<?php
/***********************************************************
products.blade.php
Product :
Version : 1.0
Release : 2
Date Created :Oct 7, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Products Management"])

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
<script type="text/javascript" src="{{ url('js/modules/products.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/inventory/productssmanagement.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Products</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
                    <li><a data-action="EXPORT_CSV" class="dropdown-item" href="#">Export as CSV</a></li>
                	<li><a data-action="DOWNLOAD_TEMPLATE" class="dropdown-item" href="#">Download Import Template</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    <!--begin: Search Form -->
								<span id="hidden_fields">
									<input type="hidden" name='page_number' value="1" />
								</span>
								<div class="col-md-12">
									<div class="row align-items-center">
										<div class="col-xl-8 order-2 order-xl-1">
											<div class="row">
												<div class="col-md-4">
												<div class="d-flex align-items-center">
										<!--begin::Input group-->
										<div class="position-relative w-md-400px me-md-2">
											<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
											<input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" />
										</div>
										<!--end::Input group-->
									</div>

												</div>
												<div class="col-md-4">
													   <select class="bs-select form-control" name="product_category" id="PRODUCT_CATEGORY" data-actions-box="true">
                                                                <option value="">Select Category</option>
                                                                <?php foreach ( $lst_product_categories as $key => $category_info ) { ?>
                                                                        <option value="<?php echo $category_info->pc_id;  ?>"><?php echo $category_info->pc_category;  ?></option>
                                                                <?php  } ?>
                                                        </select>
												 
												</div>
												<div class="col-md-4">
													<select class="bs-select form-control" name="product_currency" id="PRODUCT_CURRENCY" data-actions-box="true">
                                                            <option value="">Select Currency</option>
                                                            <?php foreach ( $lst_currencies as $key => $currency_info ) { ?>
                                                                    <option value="<?php echo $currency_info->cc_id;  ?>"><?php echo $currency_info->cc_currency_code;  ?>&nbsp;-&nbsp;<?php echo $currency_info->cc_currency_name;  ?></option>
                                                            <?php  } ?>
                                                    </select> 
												</div>
											</div>
										</div>
										<div class="col-xl-4 order-1 order-xl-2 align-right">
											<a href="{{ url('inventory/addnewproduct') }}" class="btn btn-info">
												<span>
													<i class="fa fa-code-fork"></i>
													<span>
														New Product
													</span>
												</span>
											</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12">&nbsp;</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div class="table-responsive" id="LstProductsMain">
									<table class="table">
                                		<thead>
                                			<tr>
                                				<th title="#">#</th>
                                				<th title="Id"> ID </th>
                                				<th title="Reference"> Product Reference </th>
                                				<th title="Name"> Product Name  </th>
                                				<th title="Price"> Product Price </th>
                                				<th title="edit"> edit </th> 
                                				<th title="delete"> Delete </th>
                                			</tr>
                                		</thead>
                                    	<tbody  id="LstProducts" ></tbody>
                                    </table>
                                    									
								</div>
																 <div class="row">
                                     <div class="col-md-10" align="left">
                                        <ul id="ProductsPagination" class="pagination-sm"></ul>
                                     </div>
                                     <div class="col-md-2" align="right"></div>
                                 </div>
								<!--end: Datatable -->
								<div class="row">
									<div class="col-md-8"></div>
									<div class="col-md-4" align="right">
										<a href="{{ url('inventory/addnewproduct') }}" class="btn btn-info">
												<span>
													<i class="fa fa-code-fork"></i>
													<span>
														New Product
													</span>
												</span>
											</a>
									</div>
								</div>
    </div>
</div>

 
@endsection