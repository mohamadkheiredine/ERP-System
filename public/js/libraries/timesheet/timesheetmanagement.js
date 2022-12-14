/**
 * 
 */

$(function(){
	$('select').select2();
	timesheet_module.DisplayListMonthlyTimeSheet();
	$("#BTN_CHECKIN").on("click",timesheet_module.CheckInCheckOutTimesheet);
	$("#BTN_SAVE_CHECKIN").on("click",timesheet_module.SaveCheckOutInformation);
})