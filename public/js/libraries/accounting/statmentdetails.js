$(function(){
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
	transactions_module.DisplayListStatmentDetails();
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
	 
	 
	 $("#LstAccountStatment").on('dblclick',".grouprow",function(){ 
		 $('input[name=search_query]').val("");
		 transactions_module.ShowAccounttransactionDetails($(this));
		 });
	 $("#LstAccountStatment").on('dblclick',".Transaction",transactions_module.OpenTransactionDetailsWindow);
	 $("#LstAccountStatment").on('click',"#EXPORT_CURRENCY",transactions_module.PrintAccountStatment);
	 $("#LstAccountStatment").on('click',"#EXPORT_ALL",transactions_module.PrintAllAccountStatmentDetails);
	 $("#LstAccountStatment").on('click',"button[name=btn_back]",function(){ 
		 $('input[name=search_query]').val(""); 
		 transactions_module.DisplayListStatmentDetails(); 
	 
	 });
	 
	 $('input[name=ck_include_before]').on("change",function(){
		 var account_id = $('input[name=detail_account_id]').val();

			if(account_id == undefined || account_id == 0)
				transactions_module.DisplayListStatmentDetails();
			else
				transactions_module.ShowAccounttransactionDetails();
	 });
	 $('input[name=start_date]').on("change",function(){
		 var account_id = $('input[name=detail_account_id]').val();

			if(account_id == undefined || account_id == 0)
				transactions_module.DisplayListStatmentDetails();
			else
				transactions_module.ShowAccounttransactionDetails();
		 });
	 $('input[name=end_date]').on("change",function(){
		var account_id = $('input[name=detail_account_id]').val();

		if(account_id == undefined || account_id == 0)
			transactions_module.DisplayListStatmentDetails();
		else
			transactions_module.ShowAccounttransactionDetails();
	 });
	 $('select').select2();
	$('select').on('change',function(){
		var account_id = $('input[name=detail_account_id]').val();

		if(account_id == undefined || account_id == 0)
			transactions_module.DisplayListStatmentDetails();
		else
			transactions_module.ShowAccounttransactionDetails();
	});
	$('input[name=search_query]').on('keyup',function(){ 
		var account_id = $('input[name=detail_account_id]').val();

		if(account_id == undefined || account_id == 0)
			transactions_module.DisplayListStatmentDetails();
		else
			transactions_module.ShowAccounttransactionDetails();
	});
	
	
});