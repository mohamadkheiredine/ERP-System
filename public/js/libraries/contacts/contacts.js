/**
 * 
 */

$(function(){
	contacts_module.DisplayListContacts();
	$('select').select2();
	$("#CONTACT_LEAD").on("change",function(){
		$.contacts_datatable.destroy();
		contacts_module.DisplayListContacts();
	});
})