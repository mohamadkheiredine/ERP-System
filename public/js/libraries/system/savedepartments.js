/**
 * 
 */

$(function(){
	 $("#BTN_SAVE_DEPARTMENT").on('click',departments_module.SaveDepartmentInfo);
	 $('#SD_PARENT_DEPARTMENT').select2({  placeholder: "Select a Parent Department" });
	 $('#SD_DEPARTMENT_MANAGER').select2({  placeholder: "Select a Manager Department" });
})