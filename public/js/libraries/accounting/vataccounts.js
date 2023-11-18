/**
 * 
 */

$(function(){
	 vataccounts_module.displayListVATAccounts();
		$('#LstVatAccounts').on('click',"a[id*=EDIT_VAT_]",vataccounts_module.EditVatAccountInfo);
		$('#LstVatAccounts').on('click',"a[id*=DELETE_VAT_]",vataccounts_module.DeleteVatAccountData);
		$('#generalSearch').on('keyup',vataccounts_module.displayListVATAccounts);
})