/**
 *
 */

$(function(){
	orderstatus_module.DisplayListOrderStatus();

    $('#LstOrderStatuses').on('click',"a[id*=EDIT_STATUS_]",orderstatus_module.EditStatusInfo);
    $('#LstOrderStatuses').on('click',"a[id*=DELETE_STATUS_]",orderstatus_module.DeleteStatusData);
})
