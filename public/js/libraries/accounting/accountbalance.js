/**
 * 
 */
$(function(){
	transactions_module.DisplayListAccountBalance();
	$('input[name=start_date]').datepicker({ 
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	 $('input[name=end_date]').datepicker({ 
		 todayHighlight: true,
		 orientation: "bottom left",
		 format : "yyyy-mm-dd",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $('input[name=start_date]').on("change",function(){
		 if($.balance_datatable != null)
			$.balance_datatable.destroy();
		transactions_module.DisplayListAccountBalance();
	 });
	 $('input[name=end_date]').on("change",function(){
		 if($.balance_datatable != null)
			$.balance_datatable.destroy();
		transactions_module.DisplayListAccountBalance();
	 });
	$('select').select2();
	$('select').on('change',function(){
		if($.balance_datatable != null)
			$.balance_datatable.destroy();
		transactions_module.DisplayListAccountBalance();
	})
})