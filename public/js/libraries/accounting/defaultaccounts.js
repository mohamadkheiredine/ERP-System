$(function(){
	defaultaccounts_module.displayListDefaultAccounts();
	$('button[name=btn_save_info]').on('click',defaultaccounts_module.SaveDefaultAccountsInfo);
})