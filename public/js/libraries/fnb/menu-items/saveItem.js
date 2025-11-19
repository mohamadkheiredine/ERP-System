$(document).ready(function () {
    $(document).on("click", "#BTN_SAVE_ITEM", function (e) {
        fnb_items_module.SaveItemInfo();
    });

    $(document).on("click", "[name=back_form]", function (e) {
        e.preventDefault();
        fnb_items_module.backToPreviousPage();
    });

    fnb_items_module.DisplayListItemsModifiers();

    $(document).on("click", "#BTN_SAVE_MODIFIER", function (e) {
        fnb_items_module.SaveItemModifierInfo();
    });


    $(document).on("change", "#FK_MODIFIER_ID", fnb_items_module.getValues);

    $("#LstItemsModifiers").on(
        "click",
        "a[id*=DELETE_ITEM_MODIFIER_]",
        fnb_items_module.DeleteItemModifier
    );
});
