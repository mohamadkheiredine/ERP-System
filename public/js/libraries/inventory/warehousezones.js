$(function(){
    zones_module.DisplayListwarehouseZones();
    $("input[name=general_search]").on("keyup",zones_module.DisplayListwarehouseZones);
    $("select[name=fk_warehouse_id]").on("change",zones_module.DisplayListwarehouseZones);

    $(".LstWarehouseZones").on("click","a[id*=EDIT_ZONE_]",zones_module.EditWarehouseZoneInfo);
    $(".LstWarehouseZones").on("click","a[id*=DELETE_ZONE_]",zones_module.DeleteWarehousezoneData);

});
