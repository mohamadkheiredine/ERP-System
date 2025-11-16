/**
 *
 */

$(function(){
	planstatus_module.DisplayListPlanStatus();

    $("#LstProdStatuses").on('click',"a[id*=EDIT_STATUS_]",planstatus_module.EditStatusInfo);
    $("#LstProdStatuses").on('click',"a[id*=DELETE_STATUS_]",planstatus_module.DeleteStatusData);
})
