$(function(){
    sysstatus_module.DisplayListSysStatus();
    $("#generalSearch").on('keyup',sysstatus_module.DisplayListSysStatus);
    $("select").on('change',sysstatus_module.DisplayListSysStatus);

    $('#LstSystemStatus').on('click',"a[id*=EDIT_STATUS_]",sysstatus_module.EditSysStatusInfo);
    $('#LstSystemStatus').on('click',"a[id*=DELETE_STATUS_]",sysstatus_module.DeletesysStatusData);
})
