$(function(){
    $("#BTN_SAVE_FLOOR").on('click',floors_module.SaveWarehouseFloorsInfo);
    $("#FK_WAREHOUSE_ID").on('change',floors_module.GetZonesDropdown);
})
