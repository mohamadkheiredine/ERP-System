/**
 * 
 */
$(function(){
	banking_module.displayListAccounts();
	$('#LstBankAccounts').on('click',"a[id*=EDIT_ACCOUNT_]",banking_module.EditBankingAccountInfo);
	$('#LstBankAccounts').on('click',"a[id*=DELETE_ACCOUNT_]",banking_module.DeleteBankingAccountData);
})