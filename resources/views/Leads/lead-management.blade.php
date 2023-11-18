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
<ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#tabNotes">Notes</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="LnkTabFiles" data-bs-toggle="tab" href="#tabFiles">Files</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="LnkTabContacts" data-bs-toggle="tab" href="#tabContacts">Contacts</a>
    </li>
     <li class="nav-item">
        <a class="nav-link" id="LnkTabActivities" data-bs-toggle="tab" href="#tabActivites">Activities</a>
    </li>
      <li class="nav-item">
        <a class="nav-link" id="LnkTabAppointments" data-bs-toggle="tab" href="#tabAppointments">Appointments</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="TABITEMS" data-bs-toggle="tab" href="#tabItems">Sٍervices</a>
    </li>
     <li class="nav-item">
        <a class="nav-link" id="LnkTabLogs" data-bs-toggle="tab" href="#tabLogs">Logs</a>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="tabNotes" role="tabpanel">
      	<div class="row">
          	 <div id="NotesManagement" class="col-md-12 col-lg-12">
    					
    		</div>
      	</div>
    </div>
    <div class="tab-pane fade" id="tabFiles" role="tabpanel">
       <div class="row">
			<div id="FilesManagement" class="col-md-12 col-lg-12">
			
			</div>
		</div>
		<div class="row">
			<div class="col-12" align="right">
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#UploadFiles"> Upload </button>
			</div>
		</div>
    </div>
    <div class="tab-pane fade" id="tabContacts" role="tabpanel">
       <div class="row">
				<div class="col-md-4">
					<div class="position-relative w-md-400px me-md-2">
								<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								<input type="text" class="form-control form-control-solid ps-10" name="contact_search" value="" placeholder="Search" />
							</div>
				</div>
				<div class="col-md-8"></div>
				<div id="ContactsManagement" class="col-md-12 col-lg-12">
				
				</div>
		</div>
		<div class="col-md-12" align="right">
			<button type="button" name="btn_add_contact" id="BTN_ADD_CONTACT" class="btn btn-primary m-btn--wide">Add Contact</button>
		</div>
    </div>
    
    <div class="tab-pane fade" id="tabActivites" role="tabpanel">
    	<div class="row">
    	<div class="col-md-4">
					<div class="position-relative w-md-400px me-md-2">
								<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								<input type="text" class="form-control form-control-solid ps-10" name="activities_search" value="" placeholder="Search" />
							</div>
				</div>
				<div class="col-md-8"></div>
				<div id="ActivitiesManagement" class="col-md-12 col-lg-12">
				
				</div>
		</div>
		<div class="row">
    		<div class="col-md-12" align="right">
    			<button type="button" name="btn_add_activity" id="BTN_ADD_ACTIVITY" class="btn btn-primary">Add Activity</button>
    		</div>
		</div>
    </div>
    
    
    <div class="tab-pane fade" id="tabAppointments" role="tabpanel">
        <span>
    			<input type="hidden" name="display_type" id="DISPLAY_TYPE" value="list" />
    		</span>
    		
    		<div class="row">
    			<div class="col-md-12" align="right">
    			<button type="button"  id="SWITCH_VIEW_LIST" style="background-color: transparent;border:solid 0px #f00;cursor: pointer"  data-type="list"><i class="fa-solid fa-list"></i></button>&nbsp;&nbsp;<button type="button"   id="SWITCH_VIEW_CALENDAR" style="background-color: transparent;border:solid 0px #f00;cursor: pointer" data-type="calendar"><i class="fa-solid fa-calendar-days"></i></button>
    			</div>
    		</div>
    		<div class="row">
    		<div class="col-md-4">
					<div class="position-relative w-md-400px me-md-2">
								<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								<input type="text" class="form-control form-control-solid ps-10" name="appointments_search" id="AppointmentsSearch" value="" placeholder="Search" />
							</div>
				</div>
				<div class="col-md-8"></div> 
    				<div id="AppointmentsManagement" class="col-md-12 col-lg-12">
    				
    				</div>
    		</div> 
    		<div class="col-md-12" align="right">
    			<button type="button" name="btn_add_appointment" id="BTN_ADD_APPOINTMENTS" class="btn btn-primary m-btn--wide">Add Appointment</button>
    		</div>
    </div>
    <div class="tab-pane fade" id="tabItems" role="tabpanel">
    <div class="row">
    	<div class="col-md-4">
					<div class="position-relative w-md-400px me-md-2">
								<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								<input type="text" class="form-control form-control-solid ps-10" name="items_search" id="ItemsSearch" value="" placeholder="Search" />
							</div>
				</div>
				<div class="col-md-8"></div>  
				<div id="ItemsManagement" class="col-md-12 col-lg-12">
				
				</div>
		</div>
		<div class="col-md-12" align="right">
			<button type="button" name="btn_add_item" id="BTN_ADD_ITEM" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#InserItems">Add Item</button>
		</div>
    </div>
    <div class="tab-pane fade" id="tabLogs" role="tabpanel">
    <div class="row"> 
				<div class="col-md-4">
					<div class="position-relative w-md-400px me-md-2">
								<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								<input type="text" class="form-control form-control-solid ps-10" name="logs_search" id=""LogsSearch"" value="" placeholder="Search" />
							</div>
				</div>
				<div class="col-md-8"></div>  
				<div id="LogsManagement" class="col-md-12 col-lg-12">
				
				</div>
		</div>
    </div>
</div>

  