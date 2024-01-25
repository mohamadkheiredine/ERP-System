/**
 * 
 */
$(function(){
	services_module.displayListServices();
	$('#LstServices').on('click',"a[id*=EDIT_SERVICE_]",services_module.EditServiceInfo);
	$('#LstServices').on('click',"a[id*=DELETE_SERVICE_]",services_module.DeleteServiceData);
})