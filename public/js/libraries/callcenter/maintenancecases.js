$(function(){
	 mcases_module.DisplayListMaintenanceCases();
	$("#generalSearch").on('keyup',mcases_module.DisplayListMaintenanceCases);
	$("select").on('change',mcases_module.DisplayListMaintenanceCases);

	$('#LstMaintenanceCases').on('click',"a[id*=EDIT_CASE_]",mcases_module.EditCaseInfo);
	$('#LstMaintenanceCases').on('click',"a[id*=DELETE_CASE_]",mcases_module.DeleteCaseData);
})