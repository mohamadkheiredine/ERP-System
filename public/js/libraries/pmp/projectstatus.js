/**
 * 
 */

$(function(){
	projectstatus_module.DisplayListProjectStatus();
	$("#generalSearch").on('keyup',function(){
		$('input[name=page_number]').val(1);
		$.pagination.twbsPagination('destroy');
		projectstatus_module.DisplayListProjectStatus();
	});
	$(".LstProjectStatses").on('click',"a[id*=EDIT_STATUS_]",projectstatus_module.EditProjectStatusInfo);
	$(".LstProjectStatses").on('click',"a[id*=DELETE_STATUS_]",projectstatus_module.DeleteProjectStatusData);
})