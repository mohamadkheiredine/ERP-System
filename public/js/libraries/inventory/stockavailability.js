$(function(){
    warehouses_module.DisplayWarehouseStockAvailability();
    $("#SW_STOCK_WAREHOUSE").on('change',warehouses_module.DisplayWarehouseStockAvailability);
    $(".dropdown-item").on('click',warehouses_module.QuickSAActionMenu);
})
