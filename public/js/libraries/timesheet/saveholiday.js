$(function(){
	 $("#BTN_SAVE_HOLIDAY").on('click',holidays_module.SaveYearlyHolidayInfo); 
	 $('input[name=th_holiday_date]').datepicker({ 
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
})