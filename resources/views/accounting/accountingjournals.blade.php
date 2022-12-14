<?php
/***********************************************************
accountingjournals.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Accounting Journals
***********************************************************/



?>


@extends('layouts.layout',['page_title' => "Accounting Journals"])

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
<script type="text/javascript" src="{{ url('js/modules/accountingjournals.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/accountingjournals.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
											Accounting Journals
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
																	<li class="m-nav__item">
																		<a data-action_type="PRINT"  href="#" class="m-nav__link quickactions">
																			<i class="m-nav__link-icon fa fa-print"></i>
																			<span class="m-nav__link-text">
																				Print
																			</span>
																		</a>
																	</li>
																	<li class="m-nav__item">
																		<a data-action_type="EXPORT_AS_CSV"  href="#" class="m-nav__link quickactions">
																			<i class="m-nav__link-icon fa fa-download"></i>
																			<span class="m-nav__link-text">
																				Export As CSV
																			</span>
																		</a>
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
								<!--begin: Search Form -->
								<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
									<div class="row align-items-center">
										<div class="col-xl-8 order-2 order-xl-1">
											<div class="form-group m-form__group row align-items-center">
												<div class="col-md-4">
												<div class="m-input-icon m-input-icon--left">
														<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
														<span class="m-input-icon__icon m-input-icon__icon--right">
															<span>
																<i class="la la-search"></i>
															</span>
														</span>
													</div>

												</div>
												<div class="col-md-4">
                                                    <div class="d-md-none m--margin-bottom-10"></div>
												</div>
												<div class="col-md-4">
                                                    <div class="d-md-none m--margin-bottom-10"></div>
												</div>
											</div>
										</div>
										<div class="col-xl-12 order-1 order-xl-12 m--align-right">
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
									</div>
								</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div id="LstAccountJournals" class="m_datatable">
									 
								</div>
								<!--end: Datatable -->
								<div class="col-xl-12 order-1 order-xl-12 m--align-right" style="margin-top: 12px;">
									<div class="m-separator m-separator--dashed d-xl-none"></div>
								</div>
							</div>
						</div>
						
						
						<div class="modal fade" id="ImportModal" tabindex="-1" role="dialog" aria-labelledby="ImportsModallLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="ImportsModallLabel">
											Import Journals
										</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
										</button>
									</div>
									<div class="modal-body">
										<form name="frm_import_journals" id="FRM_IMPORT_JOURNALS">
											   {!! csrf_field() !!} 
											<div class="form-group">
												<label for="message-text" class="form-control-label">
													CSV File :
												</label>
												<input type="file" name="csv_file" class="form-control" />
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">
											Close
										</button>
										<button type="button" name="btn_import_journals" id="BTN_IMPORT_JOURNALS" class="btn btn-primary">
											Import Journals
										</button>
									</div>
								</div>
							</div>
						</div>
@endsection