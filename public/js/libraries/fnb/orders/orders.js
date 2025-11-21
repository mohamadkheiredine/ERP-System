$(function () {
    orders_module.DisplayListOrders();
    $("select[name=ps_company_id]").on(
        "change",
        orders_module.DisplayListOrders
    );

    $("input[name=general_search]").on('keyup', orders_module.DisplayListOrders);

    $("select[name=fo_branch_id]").on('change', orders_module.DisplayListOrders);

    $("#LstOrdersGrid").on(
        "click",
        "a[id*=EDIT_ORDER_]",
        orders_module.EditOrderInfo
    );
    $("#LstOrdersGrid").on(
        "click",
        "a[id*=DELETE_ORDER_]",
    orders_module.DeleteOrderData
    );


});
