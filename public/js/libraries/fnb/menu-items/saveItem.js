$(function(){
    $("#BTN_SAVE_ITEM").on("click",fnb_items_module.SaveItemInfo);

    $("[name=back_form]").on("click",fnb_items_module.backToPreviousPage);

    fnb_items_module.DisplayListItemsModifiers();

    $("#BTN_SAVE_MODIFIER").on("click", fnb_items_module.SaveItemModifierInfo);


    $("#FK_MODIFIER_ID").on("change", fnb_items_module.getValues);

    $("#LstItemsModifiers").on(
        "click",
        "a[id*=DELETE_ITEM_MODIFIER_]",
        fnb_items_module.DeleteItemModifier
    );
})
