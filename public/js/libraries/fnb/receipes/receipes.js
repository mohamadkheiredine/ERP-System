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

    // Save Recipe (preparation steps)
    $(document).on("click", "#BTN_SAVE_RECIPE", function () {
        fnb_receipes_module.SaveRecipeInfo();
    });

    // Download Recipe PDF
    $(document).on("click", "#BTN_DOWNLOAD_RECIPE", function () {
        var mi_id = $("#SELECTED_RECEIPE_ID").val();

        if (!mi_id) {
            alert("Please select a recipe first");
            return;
        }

        var base_url = $("#BASE_URL").val();
        window.open(base_url + "/fnb/receipes/download/" + mi_id, "_blank");
    });

    // Scale Factor buttons
    $(document).on("click", "#SCALE_PLUS", function () {
        var current = parseFloat($("#SCALE_FACTOR").val()) || 1;
        $("#SCALE_FACTOR").val((current + 0.5).toFixed(1));
        fnb_receipes_module.RecalculateScaledPreview();
    });

    $(document).on("click", "#SCALE_MINUS", function () {
        var current = parseFloat($("#SCALE_FACTOR").val()) || 1;
        var newVal = current - 0.5;
        if (newVal < 0.5) newVal = 0.5;
        $("#SCALE_FACTOR").val(newVal.toFixed(1));
        fnb_receipes_module.RecalculateScaledPreview();
    });

    // Manual scale factor input change
    $(document).on("change", "#SCALE_FACTOR", function () {
        fnb_receipes_module.RecalculateScaledPreview();
    });

    // Recalculate when batch yield, portion size, or target margin changes
    $(document).on("change keyup", "#BATCH_YIELD, #PORTION_SIZE, #TARGET_MARGIN", function () {
        fnb_receipes_module.RecalculateSummary();
    });
});
