/**
 * 
 */

$(function(){
	$("#TS_USER").select2();
	 $('input[name=ts_date]').datepicker({ 
		 todayHighlight: true,
		 format: 'yyyy-mm-dd',
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $("#BTN_FIND").on("click",timesheet_module.DisplayAdminTimesheet);
	 $("#TimeSheetManagement").on("click","#BTN_SAVE_TIMESHEET",timesheet_module.SaveDailyTimesheetRecords);


})