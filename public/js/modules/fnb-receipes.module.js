fnb_receipes_module = {
    DisplayListReceipes: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        var general_search = $("input[name=general_search]").val();

        $.ajax({
            url: base_url + "/request/receipes/displaylistreceipes",
            data: {
                _token: _token,
                general_search: general_search,
            },
            method: "get",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $("#LST_RECIPES").html(response.display);
            },
        });
    },

    DisplayReceipeInfo: function (mi_id) {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        $.ajax({
            url: base_url + "/request/receipes/getreceipe",
            method: "get",
            data: {
                _token: _token,
                mi_id: mi_id,
            },
            success: function (response) {
                $("#RECIPE_CONTENT_WRAPPER").html(response.display);
            },
        });
    },
    DisplayListIngredients: function (mi_id) {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        $.ajax({
            url: base_url + "/request/receipes/listingredients",
            method: "GET",
            data: {
                _token: _token,
                mi_id: mi_id,
            },
            success: function (response) {
                $("#INGREDIENTS_BODY").html(response.display);
            },
        });
    },

    SaveIngredientInfo: function () {
        return fnb_receipes_module.SaveReceipeInfoSubmitHandler();
    },
    SaveReceipeInfoSubmitHandler: function () {
        var receipeForm = $("#FORM_SAVE_INGREDIENTS");
        var error3 = $(".alert-danger", receipeForm);
        var success3 = $(".alert-success", receipeForm);

        receipeForm.validate({
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: false,
            ignore: "",

            rules: {
                in_product_id: { required: true },
                in_stock_quantity: { required: true },
                in_unit_of_measure: { required: true },
                in_waste_percent: { required: true },
                in_cost_per_unit: { required: true },
                in_notes: { required: false },
            },

            messages: {},

            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },

            invalidHandler: function () {
                success3.hide();
                error3.show();
            },
            highlight: function (element) {
                $(element).closest(".form-group").addClass("has-error");
            },
            unhighlight: function (element) {
                $(element).closest(".form-group").removeClass("has-error");
            },
            success: function (label) {
                label.closest(".form-group").removeClass("has-error");
            },
        });

        if (!receipeForm.valid()) {
            success3.hide();
            error3.show();
            return;
        }

        success3.show();
        error3.hide();

        var base_url = $("#BASE_URL").val();
        var str_params = receipeForm.serialize();

        $.ajax({
            url: base_url + "/request/receipe/saveinfo",
            data: str_params,
            method: "POST",
            dataType: "json",
            success: function (response) {
                if (response.is_error == 0) {
                    $("#INGREDIENT_MODAL").modal("hide");

                    var mi_id = response.item_id;
                    fnb_receipes_module.DisplayListIngredients(mi_id);

                    receipeForm[0].reset();
                }
            },
        });
    },

    DeleteIngredientInfo: function () {
        var in_id = $(this).closest("td").data("in_id");
        var item_id = $(this).closest("td").data("item_id");

        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            if (result) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();

                $.ajax({
                    url: base_url + "/request/receipe/deletereceipeinfo",
                    type: "DELETE",
                    dataType: "json",
                    data: { in_id: in_id, _token: _token },

                    success: function (response) {
                        if (response.is_error == 0) {
                            fnb_receipes_module.DisplayListIngredients(item_id);
                        }
                    },
                });
            }
        });
    },
    backToPreviousPage: function () {
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/kitchen";
    },
};
