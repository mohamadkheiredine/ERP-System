$(function() {
    $("button[id*=BTN_SAVE_KITCHEN]").on("click",kitchen_module.SaveKitchenInfo);
    $("button[name=back_form]").on("click", kitchen_module.backToPreviousPage);
})
