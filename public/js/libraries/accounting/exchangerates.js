/**
 * 
 */

$(function(){
	$('select').select2();
	exchangerates_module.DisplayListExchangeRates();
	$('select').on('change',function(){
		$.er_datatable.destroy();
		exchangerates_module.DisplayListExchangeRates();
	})
})