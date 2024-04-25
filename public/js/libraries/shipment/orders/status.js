/**
 * 
 */

$(function(){
	sorderstatus_module.DisplayListOrderStatus();
	$("#generalSearch").on('keyup',function(){
		$('input[name=page_number]').val(1);
		$.pagination.twbsPagination('destroy');
		sorderstatus_module.DisplayListOrderStatus();
	});
	$("#LstOrderStatuses").on('click',"a[id*=EDIT_STATUS_]",sorderstatus_module.DisplayEdiStatusForm);
	$("#LstOrderStatuses").on('click',"a[id*=DELETE_STATUS_]",sorderstatus_module.DeleteStatusData);
})