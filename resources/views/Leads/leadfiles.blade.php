<?php
/***********************************************************
leadfiles.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<div class="row">
	<div class="col-md-12">
		<table class="table" style="width:100%">
    		<thead>
    			<tr>
    				<th title="#">#</th>
    				<th title="Id"> ID </th>
    				<th title="File Name"> File Name </th>
    				<th title="Attached By"> Attached By </th>
    				<th title="Date Added"> Date Added </th>
    				<th title="Size"> Size </th> 
    				<th title="Size"> options </th> 
    			</tr>
    		</thead>
    		<tbody>
    			  	@foreach($lst_files as $index => $lf_info)
                    <tr  class="odd gradeX" data-lf_id="{{ $lf_info->lf_id }}">
                    	<td><input type="checkbox" name="ck_lf_{{ $lf_info->lf_id }}" id="CK_LF_{{ $lf_info->lf_id }}" class="checkboxes" value="{{ $lf_info->lf_id }}" /></td>
                       <td>{{ $lf_info->lf_id }}</td>
                       <td>{{ $lf_info->lf_file_name . "." . $lf_info->lf_file_extension }}</td>
                       <td>{{ $users_array[ $lf_info->lf_uploaded_by]['u_fullname'] }}</td>
                       <td>{{ $lf_info->lf_uploaded_date }}</td>
                       <td>{{ $lf_info->lf_file_size }}</td>
                        <td>
                        	<div id="MenuActions_{{ $lf_info->lf_id }}" class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
									<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
										<i class="la la-plus m--hide"></i>
										<i class="la la-ellipsis-h"></i>
									</a>
									<div class="m-dropdown__wrapper">
										<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 21.5px;"></span>
										<div class="m-dropdown__inner">
											<div class="m-dropdown__body">
												<div class="m-dropdown__content">
													<ul class="m-nav">
														<li class="m-nav__section m-nav__section--first m--hide">
															<span class="m-nav__section-text">
																Quick Actions
															</span>
														</li>
														<li class="m-nav__item">
															<a href="#" data-lf_id="{{ $lf_info->lf_id }}" class="m-nav__link DeleteFile">
																<i class="m-nav__link-icon flaticon-share"></i>
																<span class="m-nav__link-text">
																	Delete File
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
                        </td> 
                    </tr>
                    @endforeach
    		</tbody>
    </table>
	</div>
</div>