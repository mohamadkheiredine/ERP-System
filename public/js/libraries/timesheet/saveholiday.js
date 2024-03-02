$(function(){
	 $("#BTN_SAVE_HOLIDAY").on('click',holidays_module.SaveYearlyHolidayInfo); 
	 new tempusDominus.TempusDominus(document.getElementById('TH_HOLIDAY_DATE'),{
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
})