/**
 * 
 */
$(function(){
	$('select').select2();
	quotations_module.displayListQuotations();
	$("#BIDDING_ID").on('change',quotations_module.displayListQuotations)
	$('select[name=quotation_warehouse]').on('change',quotations_module.displayListQuotations);
	$('select[name=quotation_supplier]').on('change',quotations_module.displayListQuotations);
	$('input[name=general_search]').on('keyup',quotations_module.displayListQuotations);
	
	
	$("#LstQuotations").on('click',  "a[id*=EDIT_QUOTATION_]",quotations_module.EditQuotationInfo);
	$("#LstQuotations").on('click',"a[id*=DELETE_QUOTATION_]",quotations_module.DeleteQuotationData);
	$("#LstQuotations").on('click',"a[id*=VIEW_QUOTATION_]",quotations_module.ViewQuotationData);
})