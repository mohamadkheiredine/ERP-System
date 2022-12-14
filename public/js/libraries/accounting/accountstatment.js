$(function(){
	transactions_module.DisplayListAccountStatment();
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
		transactions_module.DisplayListAccountStatment();
	 });
	 $('input[name=end_date]').on("change",function(){
		transactions_module.DisplayListAccountStatment();
	 });
	$('select').select2();
	$('select').on('change',function(){
		if($.statment_datatable != null)
			$.statment_datatable.destroy();
		transactions_module.DisplayListAccountStatment();
	});
	$('.printAccountStatment').on('click',transactions_module.PrintAccountStatment);
	
	
});