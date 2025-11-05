$(function() {
    $("button[id*=BTN_SAVE_TABLE]").on("click",tables_module.SaveTableInfo);
    $("button[name=back_form]").on("click", tables_module.backToPreviousPage);
})
