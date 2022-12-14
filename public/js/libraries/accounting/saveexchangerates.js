/**
 * 
 */
$(function(){
	$('select').select2();
	$('button[name=btn_exchange_rates]').on('click',exchangerates_module.SaveExchangeRateInfo);
})