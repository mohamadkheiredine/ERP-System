$(function(){
    floors_module.DisplayListwarehouseFloors();
    $("input[name=general_search]").on("keyup",floors_module.DisplayListwarehouseFloors);
    $("select[name=fk_warehouse_id]").on("change",floors_module.DisplayListwarehouseFloors);
    $("select[name=fk_zone_id]").on("change",floors_module.DisplayListwarehouseFloors);

    $(".LstWarehouseFloors").on("click","a[id*=EDIT_FLOOR_]",floors_module.EditWarehouseFloorInfo);
    $(".LstWarehouseFloors").on("click","a[id*=DELETE_FLOOR_]",floors_module.DeleteWarehouseFloorData);

});
