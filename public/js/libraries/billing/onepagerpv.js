$(function(){
	new tempusDominus.TempusDominus(document.getElementById('START_DATE'),{
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
	new tempusDominus.TempusDominus(document.getElementById('END_DATE'),{
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

         ClassicEditor
     .create( document.querySelector( '#PV_VOUCHER_DESCRIPTION' ) )
     .then( newEditor => {
        $.account_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 new tempusDominus.TempusDominus(document.getElementById('PV_CREATION_DATE'),{
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
			 format : "yyyy-MM-dd"

		 }
	});


	 $('select#LEDGER_ACCOUNT , select#ACCOUNT_RECEIVABLE').on("change",function(){
		 $('input[page_number]').val(1);
		 $('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListOnepagerPayments();
	 });
	 $('input[name=pv_start_date]').on("change",function(){
		 $('input[page_number]').val(1);
		 //$('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListOnepagerPayments();
	 });
	 $('input[name=pv_end_date]').on("change",function(){
		 $('input[page_number]').val(1);
		 $('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListOnepagerPayments();
	 });
	 $('input[name=general_search]').on("keyup",function(){
		 $('input[page_number]').val(1);
		 $('#VouchersPagination').twbsPagination('destroy');
		 vouchers_module.displayListOnepagerPayments();
	 });

         	vouchers_module.GenerateVoucherCode();

	vouchers_module.displayListOnepagerPayments();
	$("#BTN_SAVE_VOUCHER").on("click",vouchers_module.SavePaymentVoucherInfo);
	$("#BTN_NEW_VOUCHER").on("click",vouchers_module.GenerateVoucherCode);
	$("#PV_PAYMENT_AMOUNT").on("change",vouchers_module.CalculateSecondaryAmountValue);
	$(".dropdown-item").on("click",vouchers_module.QuickAction);
	$("#PV_EXCHANGE_RATE").on("change",vouchers_module.CalculateSecondaryAmountValue);
        $("#LstPaymentVouchers").on('dblclick',"td",vouchers_module.GetSelectedVoucherInfo);
        $("#LstPaymentVouchers").on('click','tr',function(){
            $('#LstPaymentVouchers tr').each((index,item) => {
                $(item).find('input[type=checkbox]').removeAttr('checked');
                $(item).removeClass('SelectedRow');
            })
            $(this).find('input[type=checkbox]').attr('checked',true);
            $(this).addClass('SelectedRow');
            $("input[name=lr_pv_ids]").val($(this).find('input[type=checkbox]').val());
        });
})
