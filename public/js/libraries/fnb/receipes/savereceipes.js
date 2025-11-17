$(function() {
    $("button[id*=BTN_SAVE_INGREDIENT]").on("click",fnb_receipes_module.SaveIngredientInfo);
    $("button[name=back_form]").on("click", fnb_receipes_module.backToPreviousPage);
})
