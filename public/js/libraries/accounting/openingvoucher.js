/**
 * 
 */

$(function(){ 
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
	var first_day = fisical_year + "-01-01";
	
	//$('input[name=at_transaction_date]').val(first_day);
	 $('#AT_TRANSACTION_DATE').datepicker({ 
		 todayHighlight: true,
		 orientation: "bottom left",
		 format : "yyyy-mm-dd",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 
	$('select').select2();
	var at_id = $("#AT_ID").val();
	if(at_id != null)
	{
		
		
	}
	accounting_module.DisplayOpeningJournal();
	$("#BTN_NEW_MOVEMENT").on('click',accounting_module.AddNewTranMovementRow);
	$("#BTN_SAVE_TRANSACTION").on('click',accounting_module.SaveOpeningVoucherInfo);
	
})