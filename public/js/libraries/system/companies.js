/**
 * 
 */

$(function(){
	company_module.DisplayListCompanies();
	$(".LstCompaniesGrid").on('click',"a[id*=EDIT_COMPANY_]",company_module.DisplayEditCompanyForm);
	$(".LstCompaniesGrid").on('click',"a[id*=DELETE_COMPANY_]",company_module.DeleteProductCategoryData);
})