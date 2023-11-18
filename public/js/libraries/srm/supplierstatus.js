/**
 * 
 */

$(function(){
	supplierstatus_module.displayListSupplierStatus();
	$('#LstSupplierStatuses').on('click',"a[id*=EDIT_STATUS_]",supplierstatus_module.EditStatusInfo);
	$('#LstSupplierStatuses').on('click',"a[id*=DELETE_STATUS_]",supplierstatus_module.DeleteStatusData);
	$('#generalSearch').on('change',supplierstatus_module.displayListSupplierStatus);
})