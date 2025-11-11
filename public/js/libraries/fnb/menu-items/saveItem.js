$(document).ready(function () {
    $(document).on("click", "#BTN_SAVE_ITEM", function (e) {
        e.preventDefault();
        fnb_items_module.SaveItemInfo();
    });

    $(document).on("click", "[name=back_form]", function (e) {
        e.preventDefault();
        fnb_items_module.backToPreviousPage();
    });

    fnb_items_module.DisplayListItemsModifiers();

    $(document).on("click", "#BTN_SAVE_MODIFIER", function (e) {
        e.preventDefault();
        fnb_items_module.SaveItemModifierInfo();
    });

    $("#LstItemsModifiers").on(
        "click",
        "a[id*=DELETE_ITEM_MODIFIER_]",
        fnb_items_module.DeleteItemModifier
    );
});
