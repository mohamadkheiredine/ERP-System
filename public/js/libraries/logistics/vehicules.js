/**
 * 
 */

$(function(){
	vehicules_module.DisplayListVehicules();
	$(".LstVehiculesGrid").on('click',"a[id*=EDIT_VEHICULE_]",vehicules_module.DisplayEditVehiculeForm);
	$(".LstVehiculesGrid").on('click',"a[id*=DELETE_VEHICULE_]",vehicules_module.DeleteVehiculesData);
})