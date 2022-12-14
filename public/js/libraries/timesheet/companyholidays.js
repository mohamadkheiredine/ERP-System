/**
 * 
 */

$(function(){
	holidays_module.displayListCompanyHolidays();
	$("#HOLIDAY_YEAR").on('change',holidays_module.displayListCompanyHolidays);
	$(".LstHolidaysGrid").on('click',"a[id*=EDIT_HOLIDAY_]",holidays_module.DisplayEditHolidayForm);
	$(".LstHolidaysGrid").on('click',"a[id*=DELETE_HOLIDAY_]",holidays_module.DeleteHolidayData);
})