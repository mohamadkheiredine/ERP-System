/**
 * 
 */

$(function(){
	$("#TS_USER").select2();
          new tempusDominus.TempusDominus(document.getElementById('TS_DATE'),{
		 display: {
			  components: {
			      calendar: true,
			      date: true,
			      month: true,
			      year: true,
			      decades: true, 
			      clock: false,
			      hours: false,
			      minutes: false,
			      seconds: false,
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "L"
			 
		 }
	});
	 $("#BTN_FIND").on("click",timesheet_module.DisplayAdminTimesheet);
	 $("#TimeSheetManagement").on("click","#BTN_SAVE_TIMESHEET",timesheet_module.SaveDailyTimesheetRecords);


})