/**
 * 
 */
$(function(){
	 $('input[name=ts_date]').datepicker({ 
		 todayHighlight: true,
		 format: 'yyyy-mm',
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	payroll_module.DisplayListPayRoll();
	$("#BTN_FIND").on("click",function(){
		$.ep_datatable.destroy();
		payroll_module.DisplayListPayRoll();
	});
	$("#BTN_GENERATE_PAYROLL").on("click",payroll_module.GenerateMonthlyPayRoll);
})