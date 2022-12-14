/**
 * 
 */
$(function(){
	//timesheet_module.DisplayListHolidaysEmployees();
	//$("#TS_USER , #TS_YEAR").on('change',timesheet_module.DisplayListHolidaysEmployees);
	$("button[name=btn_search]").on('click',timesheet_module.DisplayListHolidaysEmployees);
	$('select').select2();
})