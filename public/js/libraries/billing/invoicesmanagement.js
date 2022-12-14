/**
 * 
 */
$(function(){
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
	
	$('select').select2();
	invoices_module.DisplayListInvoices();
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
	 $('select').on("change",function(){
		 $('input[name=page_number]').val(1);
		 if( $.pagination != null)
			 $.pagination.twbsPagination('destroy');
		 invoices_module.DisplayListInvoices();
	 });
	 $('input[name=start_date]').on("change",function(){
		 $('input[name=page_number]').val(1);
		 if( $.pagination != null)
			 $.pagination.twbsPagination('destroy');
		 invoices_module.DisplayListInvoices();
	 });
	 $('input[name=end_date]').on("change",function(){ 
		 $('input[name=page_number]').val(1);
		 if( $.pagination != null)
			 $.pagination.twbsPagination('destroy');
		 invoices_module.DisplayListInvoices();
	 });
	 $('input[name=general_search]').on("keyup",function(){
		 $('input[name=page_number]').val(1);
		 if( $.pagination != null)
			 $.pagination.twbsPagination('destroy');
		 invoices_module.DisplayListInvoices();
	 });
	 
	 

})