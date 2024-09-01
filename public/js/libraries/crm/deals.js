/**
 * 
 */

$(function(){
	deals_module.displayListDeals();
	$("#AD_ACCOUNT").on("change",deals_module.FilterDeals);
          $("#LstAccountDeals").on('click',"a[id*=EDIT_DEAL_]",deals_module.EditDealInfo);
          $("#LstAccountDeals").on('click',"a[id*=DELETE_DEAL_]",deals_module.DeleteDealData);
})