
$(function() {
    fnb_items_module.DisplayListItems();
    $("input[name=general_search]").on('keyup', fnb_items_module.DisplayListItems);
    $("select[name=fi_company_name]").on('change', fnb_items_module.DisplayListItems);
    $("select[name=fi_kitchen_name]").on('change', fnb_items_module.DisplayListItems);

    $("a[id*=EDIT_ITEM_]").on("click", fnb_items_module.SaveItemInfo);

    $("#LstItems").on(
        "click",
        "a[id*=DELETE_ITEM_]",
        fnb_items_module.DeleteItem
    );
});
