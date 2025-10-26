$(function(){
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
	transactions_module.DisplayListStatmentDetails();
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
		var account_id = $('input[name=acc_account]').val();

		if(account_id == undefined || account_id == 0)
			transactions_module.DisplayListStatmentDetails();
		else
			transactions_module.ShowAccounttransactionDetails();
	});
});
