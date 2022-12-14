<?php
/************************************************************
editrole.blade.php
Product :
Version : 1.0
Release : 0
Date Created : Aug 7, 2015
Developed By  : Mohamad. Mantach  PHP Department Softweb S.A.R.L
All Rights Reserved, Softweb S.A.R.L COPYRIGHT 2015

Page Description :
--
************************************************************/


?>

@extends('layouts.layout',['page_title' => 'Roles Management'])

@section('plugins')
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/roles.module.js') }}"></script> 
<script type="text/javascript" src="{{ url('js/libraries/roles/saverole.js') }}"></script>
@endsection
@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Edit Existing Role</h3>
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
             <form name="form_save_role" id="FORM_SAVE_ROLE">
                <div class="form-body">
                     <span id="hidden_fields"> 
                        {!! csrf_field() !!} 
                        <input type="hidden" name="role_id" id="ROLE_ID" value="{{ $roles->role_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Role Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                      <div class="row">
                        <div class="col-md-9">
                             <div class="form-group">
                                <label class="control-label">Role Name <span class="required"> * </span></label>
                                <input type="text" name="role_name" id="ROLE_NAME" class="form-control" required="required"  value="{{ $roles->role_name }}" />
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                   <div class="row">
                                    <div class="col-md-9">
                                         <div class="form-group">
                                            <label class="control-label">Role Description</label>
                                            <textarea style="width:100%;height:150px;resize:none" name="role_description" id="ROLE_DESCRIPTION"  class="form-control">{{ $roles->role_description }}</textarea>
                                        </div>
                                    </div>
                        <div class="col-md-3"></div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                   <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="white-space: nowrap;">
                        <div class="portlet-body">
        					<h3>Privileges</h3>
        					<div class="tabbable-line">
        						<ul class="nav nav-tabs" role="tablist"> 
									@foreach($pa_result_array as $tab_title => $value)
        							<li class="nav-item <?php echo str_replace(" ", "", $tab_title) == 'AdministrationManagement' ? 'active' : ''; ?>">
										<a class="nav-link" data-toggle="tab" href="#m_{{ str_replace(' ', '', $tab_title) }}">
											{{ $tab_title }}
										</a>
									</li>
									@endforeach
									 
								</ul>
								<div class="tab-content">
									@foreach($pa_result_array as $tab_title => $pa_info)
										<div class="tab-pane <?php echo str_replace(" ", "", $tab_title) == 'AdministrationManagement' ? 'active' : ''; ?>" id="m_{{ str_replace(' ', '', $tab_title) }}">
                                        <ul class="LstRoles">
                                           <?php
                                                foreach ($pa_info as $index => $pa_priv_info ) {
                                                   ?>
                                                    <li>
                                                        <table cellspacing="0" cellpadding="0" style="width:100%">
                                                              <tr>
                                                                   <td style="width:3%;">
                                                                       <input type="checkbox" <?php echo ( count($rp_result_array) > 0 && isset($rp_result_array[ $pa_priv_info['code'] ]) && $rp_result_array[ $pa_priv_info['code'] ] == 'allow' ) ? "checked='checked'" : ""; ?> name="<?php echo $pa_priv_info['code']; ?>" id="<?php echo strtoupper($pa_priv_info['code']); ?>" value="1" />
                                                                   </td>
                                                                   <td style="width:97%;"><?php echo $pa_priv_info['description']; ?></td>
                                                              </tr>
                                                        </table>
                                                    </li>
                                                   <?php
                                                }
                                           ?>
                                        </ul>
        							</div>
									@endforeach
								</div>
        					</div>
        			     </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_role" id="BTN_SAVE_ROLE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

 


@endsection

 