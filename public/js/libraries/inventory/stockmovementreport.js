$(function(){
    warehouses_module.DisplayWarehouseStockMovement();
    $("#SM_STOCK_WAREHOUSE").on('change',warehouses_module.DisplayWarehouseStockMovement);
    $("#SM_UPTO_DATE").on('change',warehouses_module.DisplayWarehouseStockMovement);
    $(".dropdown-item").on('click',warehouses_module.QuickMAActionMenu);


    new tempusDominus.TempusDominus(document.getElementById('SM_UPTO_DATE'),{
        display: {
            components: {
                calendar: true,
                date: true,
                month: true,
                year: true,
                decades: true,
                clock: false,
                hours: false,
                minutes: false,
                seconds: false,
                useTwentyfourHour: undefined
            }
        },
        localization: {
            format : "yyyy-MM-dd"

        }
    });
})
