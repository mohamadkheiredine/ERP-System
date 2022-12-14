/**
 * 
 */

$(function(){
	deals_module.displayListDeals();
	$("#AD_ACCOUNT").on("click",deals_module.FilterDeals);
	 $('select').select2();
})