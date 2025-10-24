$(function(){
	transactions_module.DisplayListAccountStatment();
	new tempusDominus.TempusDominus(
        document.querySelector('input[name=start_date]'), {
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
                }
            },
            localization: { format: "yyyy-MM-dd" }
        }
    );

    new tempusDominus.TempusDominus(
        document.querySelector('input[name=end_date]'), {
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
                }
            },
            localization: { format: "yyyy-MM-dd" }
        }
    );
	 $('input[name=start_date]').on("change",function(){
		transactions_module.DisplayListAccountStatment();
	 });
	 $('input[name=end_date]').on("change",function(){
		transactions_module.DisplayListAccountStatment();
	 });
	$('select').select2();
	$('select').on('change',function(){
		if($.statment_datatable != null)
			$.statment_datatable.destroy();
		transactions_module.DisplayListAccountStatment();
	});
	$('.printAccountStatment').on('click',transactions_module.PrintAccountStatment);

    $('input[name=search_query]').on('keyup',function(){
		var account_id = $('input[name=acc_account]').val();

		if(account_id == undefined || account_id == 0)
			transactions_module.DisplayListStatmentDetails();
		else
			transactions_module.ShowAccounttransactionDetails();
	});
});
