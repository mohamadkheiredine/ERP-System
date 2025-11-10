$(function() {
    $("button[id*=BTN_SAVE_ITEM]").on("click",fnb_items_module.SaveItemInfo);
    $("button[name=back_form]").on("click", fnb_items_module.backToPreviousPage);
})
