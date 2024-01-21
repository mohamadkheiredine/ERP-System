/**
 * 
 */
$(function(){
	journals_module.displayListAccountingJournals();
	$('#LstAccountJournals').on('click',".ActualSwitch",journals_module.ChangeIsActivejournals);

})