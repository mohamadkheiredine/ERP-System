/**
 * 
 */

$(function(){
	units_module.DisplayListPackageUnits();
	$("#ListUnitsPackage").on('click',"a[id*=EDIT_PACK_]",units_module.DisplayEditUnitsForm);
	$("#ListUnitsPackage").on('click',"a[id*=DELETE_PACK_]",units_module.DeletePhoneUnitsData);
})