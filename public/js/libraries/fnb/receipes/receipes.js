$(function () {
    fnb_receipes_module.DisplayListReceipes();

    $("input[name=general_search]").on(
        "keyup",
        fnb_receipes_module.DisplayListReceipes
    );

    $(document).on("click", ".recipe-card", function () {
        $(".recipe-card").removeClass("selected");
        $(this).addClass("selected");

        var mi_id = $(this).data("mi_id");
        $("#SELECTED_RECEIPE_ID").val(mi_id);

        fnb_receipes_module.DisplayReceipeInfo(mi_id);
        fnb_receipes_module.DisplayListIngredients(mi_id);
    });

    $(document).on("click", ".delete-ingredient-btn", function () {
        fnb_receipes_module.DeleteIngredientInfo.call(this);
    });

    $(document).on("click", "#BTN_ADD_INGREDIENTS", function () {
        $("#INGREDIENT_MODAL").modal("show");
    });

    $(document).on("click", "#BTN_SAVE_INGREDIENT", function (e) {
        e.preventDefault();
        fnb_receipes_module.SaveReceipeInfoSubmitHandler();
    });

    $(document).on("click", "#BTN_PRINT_RECIPE", function () {
        var mi_id = $("#SELECTED_RECEIPE_ID").val();

        if (!mi_id) {
            alert("Please select a recipe first");
            return;
        }

        var base_url = $("#BASE_URL").val();
        window.open(base_url + "/fnb/receipes/print/" + mi_id, "_blank");
    });
});
