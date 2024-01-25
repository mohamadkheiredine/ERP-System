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
	
	
	$("#LstContacts").on("click","a[id*=EDIT_CONTACT_]",contacts_module.EditContactInfo);
	$("#LstContacts").on("click","a[id*=DELETE_CONTACT_]",function(){ 
		contacts_module.DeleteContactInfo();
	});
})