/**
 * 
 */
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
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
	receipts_module.displayListReceipts();
	$('select').on('change',function(){
		$('input[name=page_number]').val(1);
		$("#ReceiptsPagination").twbsPagination('destroy');
		receipts_module.displayListReceipts();
	});
	$('input[type=text]').on('keyup',function(){
		$('input[name=page_number]').val(1);
		$("#ReceiptsPagination").twbsPagination('destroy');
		receipts_module.displayListReceipts();
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
	$(".LstReceiptsGrid").on('click',"a[id*=EDIT_RECEIPT_]",receipts_module.EditReceiptForm)
	$(".LstReceiptsGrid").on('click',"a[id*=DELETE_RECEIPT_]",receipts_module.DeleteReceiptForm)
})