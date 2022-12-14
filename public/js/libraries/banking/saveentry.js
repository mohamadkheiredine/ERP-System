/**
 * 
 */
$(function(){
	$('#BE_OPERATION_DATE').datepicker({
		startDate :'+1d',
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	 $('#BE_VALUE_DATE').datepicker({
		 startDate :'+1d',
		 todayHighlight: true,
		 orientation: "bottom left",
		 format : "yyyy-mm-dd",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	$('select').select2();
	$("#BTN_SAVE_ENTRY").on('click',entries_module.SaveBankingEntryInfo);
	var be_id = $('input[name=be_id]').val();
	if(be_id != undefined)
	{
		entries_module.GetBankCurrency();	
	}

	$("#BE_BANK_ID").on('change',entries_module.GetBankCurrency);
})