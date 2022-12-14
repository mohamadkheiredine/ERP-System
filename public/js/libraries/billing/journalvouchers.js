
$(function(){
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
	
	$('select').select2(); 
	vouchers_module.displayListJournalVouchers();
	$('input[name=pj_start_date]').datepicker({ 
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	 $('input[name=pj_end_date]').datepicker({ 
		 todayHighlight: true,
		 orientation: "bottom left",
		 format : "yyyy-mm-dd",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $('select').on("change",function(){
		 $('input[page_number]').val(1);
		 $('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListJournalVouchers();
	 });
	 $('input[name=pj_start_date]').on("change",function(){
		 $('input[page_number]').val(1);
		 //$('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListJournalVouchers();
	 });
	 $('input[name=pj_end_date]').on("change",function(){
		 $('input[page_number]').val(1);
		 $('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListJournalVouchers();
	 });
	 $('input[name=general_search]').on("keyup",function(){
		 $('input[page_number]').val(1);
		 $('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListJournalVouchers();
	 });
	 

     
		$("#LstJournalVouchers").on('click',"a[id*=EDIT_PJ_]",vouchers_module.EditJournalVoucherInfo);
		$("#LstJournalVouchers").on('click',"a[id*=DELETE_PJ_]",vouchers_module.DeleteJournalVoucherData);
})