	 function getCookie(cName) {
		  const name = cName + "=";
		  const cDecoded = decodeURIComponent(document.cookie); //to be careful
		  const cArr = cDecoded.split('; ');
		  let res;
		  cArr.forEach(val => {
		    if (val.indexOf(name) === 0) res = val.substring(name.length);
		  })
		  return res
		}
$(function(){
     ClassicEditor
     .create( document.querySelector( '#BR_RECEIPT_NOTE' ) )
     .then( newEditor => {
        $.account_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
	receipts_module.displayListOnePageReceipts();
	receipts_module.GenerateReceiptCode();
	$('select[name=br_receipt_client]').on('change',function(){
		$('input[name=page_number]').val(1);
		$("#ReceiptsPagination").twbsPagination('destroy');
		receipts_module.displayListOnePageReceipts();
	});

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
        	new tempusDominus.TempusDominus(document.getElementById('BR_RECEIPT_DATE'),{
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
	$(".LstReceiptsGrid").on('click',"a[id*=DELETE_RECEIPT_]",receipts_module.DeleteReceiptForm);
	$(".LstOneReceiptsGrid").on('dblclick',"td",receipts_module.GetSelectedReceiptInfo);
	$("input[name=br_payment_value],input[name=br_payment_value]").on('change', receipts_module.GenerateReceiptCode);
	$("#BTN_SAVE_RECEIPT").on("click",receipts_module.SaveReceiptInfo);
	$("#BTN_SAVE_RECEIPT_MAIN").on("click",receipts_module.SaveReceiptInfo);
	$("button[name=btn_new_receipt]").on("click",receipts_module.NewReceipt);
	$("#BR_PAYMENT_VALUE").on("keyup",receipts_module.CalculateSecondaryAmountValue);
	$("#BR_EXCHANGE_RATE").on("keyup",receipts_module.CalculateSecondaryAmountValue);
})
