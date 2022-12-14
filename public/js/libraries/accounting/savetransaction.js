/**
 * 
 */
$(function(){
	 $('#AT_TRANSACTION_DATE').datepicker({
		 todayHighlight: true,
		 orientation: "bottom left",
		 format : "yyyy-mm-dd",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	$("#BTN_SAVE_TRANSACTION").on('click',transactions_module.SaveTransactionInfo);
	$('select').select2();
	var at_id = $("#AT_ID").val();
	if(at_id != null)
	{
		transactions_module.DisplayListMovements();
		$("#BTN_NEW_MOVEMENT").on('click',transactions_module.AddNewMovementRow);
	}
})