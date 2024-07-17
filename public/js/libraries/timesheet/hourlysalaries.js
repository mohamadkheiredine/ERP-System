$(function(){ 
	timesheet_module.DisplayListHourlySalaries();
	$("#TS_MONTH").on("change",timesheet_module.DisplayListHourlySalaries);
})