/**
 * 
 */

$(function(){
	$('select').select2();
	$('input[name=start_date]').datepicker({
		startDate :'+1d',
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	 $('input[name=end_date]').datepicker({
		 startDate :'+1d',
		 todayHighlight: true,
		 orientation: "bottom left",
		 format : "yyyy-mm-dd",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 transactions_module.DisplayListLedger();
	transactions_module.DisplayListEmptyTransactions();
	$('select').on('change',transactions_module.DisplayListLedger);
})