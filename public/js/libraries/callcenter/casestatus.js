$(function(){
	casestatus_module.DisplayListCaseStatus();
	$("#generalSearch").on('keyup',casestatus_module.DisplayListCaseStatus);
	$("select").on('change',casestatus_module.DisplayListCaseStatus);

	$('#LstCaseStatus').on('click',"a[id*=EDIT_STATUS_]",casestatus_module.EditCaseStatusInfo);
	$('#LstCaseStatus').on('click',"a[id*=DELETE_STATUS_]",casestatus_module.DeleteCaseStatusData);
})