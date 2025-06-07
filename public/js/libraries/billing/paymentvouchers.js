$(function(){
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);

	vouchers_module.displayListPayments();
	new tempusDominus.TempusDominus(document.getElementById('PV_START_DATE'),{
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
	new tempusDominus.TempusDominus(document.getElementById('PV_END_DATE'),{
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
		 vouchers_module.displayListPayments();
	 });
	 $('input[name=pv_start_date]').on("change",function(){
		 $('input[page_number]').val(1);
		 //$('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListPayments();
	 });
	 $('input[name=pv_end_date]').on("change",function(){
		 $('input[page_number]').val(1);
		 $('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListPayments();
	 });
	 $('input[name=general_search]').on("keyup",function(){
		 $('input[page_number]').val(1);
		 $('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListPayments();
	 });

    $("#LstPaymentVouchers").on("click","tr",vouchers_module.SelectedVoucherRecord);
    $(".dropdown-item").on("click",vouchers_module.QuickActionExecution);
})
