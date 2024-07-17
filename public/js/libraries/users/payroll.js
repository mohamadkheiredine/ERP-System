/**
 * 
 */
$(function(){
		 new tempusDominus.TempusDominus(document.getElementById('TS_DATE'),{
		 display: {
			  components: {
			      calendar: true,
			      date: false,
			      month: true,
			      year: false,
			      decades: false, 
			      clock: false,
			      hours: false,
			      minutes: false,
			      seconds: false,
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "MM/yyyy"
			 
		 }
	});
	payroll_module.DisplayListPayRoll();
	$("#BTN_FIND").on("click",function(){
		payroll_module.DisplayListPayRoll();
	});
	$("#BTN_GENERATE_PAYROLL").on("click",payroll_module.GenerateMonthlyPayRoll);
})