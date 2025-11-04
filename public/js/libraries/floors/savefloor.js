$(function() {
    $("button[id*=BTN_SAVE_FLOOR]").on("click",floors_module.SaveFloorInfo);
    $("button[name=back_form]").on("click", floors_module.backToPreviousPage);
})
