$(function(){
    warehouses_module.DisplayWarehouseStockStatus();
    $("#SM_STOCK_WAREHOUSE").on('change',warehouses_module.DisplayWarehouseStockStatus);
    $(".dropdown-item").on('click',warehouses_module.QuickAsActionMenu);
})
