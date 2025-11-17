$(function () {
    fnb_receipes_module.DisplayListReceipes();

    $("input[name=general_search]").on(
        "keyup",
        fnb_receipes_module.DisplayListReceipes
    );

    $(document).on("click", ".recipe-card", function () {
        $(".recipe-card").removeClass("selected");
        $(this).addClass("selected");
        var fi_id = $(this).data("fi_id");
        fnb_receipes_module.DisplayReceipeInfo(fi_id);
        fnb_receipes_module.DisplayListIngredients(fi_id);
    });

    $(document).on("click", ".delete-ingredient-btn", function () {
        fnb_receipes_module.DeleteIngredientInfo.call(this);
    });
});
