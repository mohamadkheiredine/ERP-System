<?php
/***********************************************************
lead-management.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" id="LnkTabNotes" data-toggle="tab" href="#tabNotes">
			Notes
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="LnkTabFiles" data-toggle="tab" href="#tabFiles">
			Files
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="LnkTabContacts" data-toggle="tab" href="#tabContacts">
			Contacts
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="LnkTabActivities" id="TABACTIVITIES" data-toggle="tab" href="#tabActivites">
			Activities
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="LnkTabAppointments"  id="TABAPPOINTMENTS" data-toggle="tab" href="#tabAppointments">
			Appointments
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="TABITEMS" data-toggle="tab" href="#tabItems">
			Sٍervices
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="LnkTabLogs" data-toggle="tab" href="#tabLogs">
			Logs
		</a>
	</li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tabNotes" role="tabpanel">
		<div class="row">
				<div id="NotesManagement" class="col-md-12 col-lg-12">
					
				</div>
		</div>
	</div>
	<div class="tab-pane" id="tabFiles" role="tabpanel">
		<div class="row">
				<div id="FilesManagement" class="col-md-12 col-lg-12 m_datatable">
				
				</div>
		</div>
		<div class="row">
			<div class="col-12" align="right">
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#UploadFiles"> Upload </button>
			</div>
		</div>
	</div>
	<div class="tab-pane" id="tabContacts" role="tabpanel">
		<div class="row">
				<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
					<div class="row align-items-center">
						<div class="col-xl-12">
							<div class="form-group m-form__group row align-items-center">
								<div class="col-md-12">
								<div class="m-input-icon m-input-icon--left">
										<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." name="contacts_search" id="ContactsSearch" />
										<span class="m-input-icon__icon m-input-icon__icon--right">
											<span>
												<i class="la la-search"></i>
											</span>
										</span>
									</div>

								</div>  
							</div>
						</div>
						 
					</div>
				</div>
				<div id="ContactsManagement" class="col-md-12 col-lg-12">
				
				</div>
		</div>
		<div class="col-md-12" align="right">
			<button type="button" name="btn_add_contact" id="BTN_ADD_CONTACT" class="btn btn-primary m-btn--wide">Add Contact</button>
		</div>
	</div>
	<div class="tab-pane" id="tabActivites" role="tabpanel">
		<div class="row">
				<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
					<div class="row align-items-center">
						<div class="col-xl-12">
							<div class="form-group m-form__group row align-items-center">
								<div class="col-md-12">
								<div class="m-input-icon m-input-icon--left">
										<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." name="activities_search" id="ActivitiesSearch" />
										<span class="m-input-icon__icon m-input-icon__icon--right">
											<span>
												<i class="la la-search"></i>
											</span>
										</span>
									</div>

								</div>  
							</div>
						</div>
						 
					</div>
				</div>
				<div id="ActivitiesManagement" class="col-md-12 col-lg-12">
				
				</div>
		</div>
		<div class="col-md-12" align="right">
			<button type="button" name="btn_add_activity" id="BTN_ADD_ACTIVITY" class="btn btn-primary m-btn--wide">Add Activity</button>
		</div>
	</div>
	<div class="tab-pane" id="tabAppointments" role="tabpanel">
		<span>
			<input type="hidden" name="display_type" id="DISPLAY_TYPE" value="list" />
		</span>
		
		<div class="row">
			<div class="col-md-12" align="right">
			<button type="button"  id="SWITCH_VIEW_LIST" style="background-color: transparent;border:solid 0px #f00;cursor: pointer"  data-type="list"><i class="flaticon-list-3"></i></button>&nbsp;&nbsp;<button type="button"   id="SWITCH_VIEW_CALENDAR" style="background-color: transparent;border:solid 0px #f00;cursor: pointer" data-type="calendar"><i class="flaticon-calendar-2"></i></button>
			</div>
		</div>
		<div class="row">
				<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
					<div class="row align-items-center">
						<div class="col-xl-12">
							<div class="form-group m-form__group row align-items-center">
								<div class="col-md-12">
								<div class="m-input-icon m-input-icon--left" id="SEARCH_BLOCK">
										<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." name="appointments_search" id="AppointmentsSearch" />
										<span class="m-input-icon__icon m-input-icon__icon--right">
											<span>
												<i class="la la-search"></i>
											</span>
										</span>
									</div>

								</div>  
							</div>
						</div>
						 
					</div>
				</div>
				<div id="AppointmentsManagement" class="col-md-12 col-lg-12">
				
				</div>
		</div> 
		<div class="col-md-12" align="right">
			<button type="button" name="btn_add_appointment" id="BTN_ADD_APPOINTMENTS" class="btn btn-primary m-btn--wide">Add Appointment</button>
		</div>
	</div>
	<div class="tab-pane" id="tabItems" role="tabpanel">
		<div class="row">
				<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
					<div class="row align-items-center">
						<div class="col-xl-12">
							<div class="form-group m-form__group row align-items-center">
								<div class="col-md-12">
								<div class="m-input-icon m-input-icon--left">
										<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." name="items_search" id="ItemsSearch" />
										<span class="m-input-icon__icon m-input-icon__icon--right">
											<span>
												<i class="la la-search"></i>
											</span>
										</span>
									</div>

								</div>  
							</div>
						</div>
						 
					</div>
				</div>
				<div id="ItemsManagement" class="col-md-12 col-lg-12">
				
				</div>
		</div>
		<div class="col-md-12" align="right">
			<button type="button" name="btn_add_item" id="BTN_ADD_ITEM" class="btn btn-primary m-btn--wide"  data-toggle="modal" data-target="#InserItems">Add Item</button>
		</div>
	</div>
	<div class="tab-pane" id="tabLogs" role="tabpanel">
		<div class="row">
				<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
					<div class="row align-items-center">
						<div class="col-xl-12">
							<div class="form-group m-form__group row align-items-center">
								<div class="col-md-12">
								<div class="m-input-icon m-input-icon--left">
										<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." name="logs_search" id="LeadLogs" />
										<span class="m-input-icon__icon m-input-icon__icon--right">
											<span>
												<i class="la la-search"></i>
											</span>
										</span>
									</div>

								</div>  
							</div>
						</div>
						 
					</div>
				</div>
				<div id="LogsManagement" class="col-md-12 col-lg-12">
				
				</div>
		</div>
	</div>
</div>