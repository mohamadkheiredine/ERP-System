<?php
/***********************************************************
listconfigurations.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 8, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Configuration Management"])

@section('plugins')
<script type="text/javascript" src="{{ url('js/libraries/system/configuration.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Main Configuration
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
												<a href="" id="DUPLICATE_PRODUCT" class="m-nav__link">
													<i class="m-nav__link-icon fas fa-clone"></i>
													<span class="m-nav__link-text">
														Duplicate
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-share"></i>
													<span class="m-nav__link-text">
														Print
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-chat-1"></i>
													<span class="m-nav__link-text">
														Export As CSV
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-multimedia-2"></i>
													<span class="m-nav__link-text">
														Import
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-multimedia-2"></i>
													<span class="m-nav__link-text">
														Download Import Template
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
	<form name="form_save_configurations" id="FORM_SAVE_CONFIGURATIONS">
         <table  class="table table-bordered table-striped table-condensed flip-content" style="width:100%">
            <thead class="flip-content">
                <tr>
                    <th style="width:2%">#</th>
                    <th style="width:30%">Index</th>
                    <th style="width:20%">Value</th>
                    <th style="width:48%">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < count($sys_configurations); $i++) { ?>
                    <tr>
                       <td>
                       <input type="hidden" name="sa_id[]" value="{{ $sys_configurations[$i]->sa_id }}" />
                       <input type="hidden" name="sa_config_index[]" value="{{ $sys_configurations[$i]->sa_config_index }}" />
                        {{ $sys_configurations[$i]->sa_id }}
                       </td>
                       <td>{{ $sys_configurations[$i]->sa_config_index }}</td>
                       <td>
                            <input type="text" name="sa_config_value[]" class="form-control" value="{{ $sys_configurations[$i]->sa_config_value }}" />
                       </td>
                       <td>
                            <input type="text" name="sa_config_description[]" class="form-control" value="{{ $sys_configurations[$i]->sa_config_description }}" />
                       </td>
                   </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>
	<div class="row">
        <div class="col-md-12" align="right">
            <button id="BTN_SAVE_CONFIGURATION" name="btn_save_configuration" type="button" class="btn btn-info"  >Save</button>
        </div>
    </div>
	</div>
	</div>
@endsection