
$(function(){
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
	
	$('select').select2(); 
	vouchers_module.displayListJournalVouchers();
	
	 new tempusDominus.TempusDominus(document.getElementById('JV_START_DATE'),{
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
	 new tempusDominus.TempusDominus(document.getElementById('JV_END_DATE'),{
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
	 $('select').on("change",function(){
		 $('input[page_number]').val(1);
		 $('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListJournalVouchers();
	 });
	 $('input[name=jv_start_date]').on("change",function(){
		 $('input[page_number]').val(1);
		 //$('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListJournalVouchers();
	 });
	 $('input[name=jv_end_date]').on("change",function(){
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