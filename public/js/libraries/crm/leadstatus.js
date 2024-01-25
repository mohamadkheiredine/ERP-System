/**
 * 
 */
$(function(){
	leadstatus_module.DisplayListLeadStatus();
	$("#LstLeadStatuses").on('click',"a[id*=EDIT_STATUS_]",leadstatus_module.EditStatusInfo);
	$("#LstLeadStatuses").on('click',"a[id*=DELETE_STATUS_]",leadstatus_module.DeleteStatusData);
})