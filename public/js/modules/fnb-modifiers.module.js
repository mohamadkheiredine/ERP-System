modifiers_module = {
    DisplayListModifiers: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        var page_number = $("input[name=page_number]").val();
        var general_search = $("input[name=general_search]").val();
        $.ajax({
            url: base_url + "/request/modifiers/displaylistmodifiers",
            data: {
                _token: _token,
                page_number: page_number,
                general_search: general_search,
            },
            method: "get",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $("#LstModifiersGrid").html(response.display);
                $(".group-checkable").change(function () {
                    var set = $("table").find(
                        'tbody > tr > td:nth-child(1) input[type="checkbox"]'
                    );
                    var checked = $(this).prop("checked");
                    $(set).each(function () {
                        $(this).prop("checked", checked);
                    });
                    $.uniform.update(set);
                });
                if (response.total_pages > 0) {
                    $.pagination = $("#ModifiersPagination").twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $("input[name=page_number]").val(page);
                            modifiers_module.DisplayListModifiers();
                        },
                    });
                }
            },
        });
    },
    SaveModifierInfo: function () {
        return modifiers_module.SaveModifierInfoSubmitHandler();
    },
    SaveModifierInfoSubmitHandler: function () {
        var ModifierForm = $("#FORM_SAVE_MODIFIER");
        var error3 = $(".alert-danger", ModifierForm);
        var success3 = $(".alert-success", ModifierForm);

        $.validator.addMethod(
            "floatNumber",
            function (value, _) {
                // ✅ Only positive numbers (integer or decimal)
                return /^\d+(\.\d+)?$/.test(value);
            },
            "Please enter a valid positive number (e.g. 10 or 10.5)"
        );

        ModifierForm.validate({
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: false,
            ignore: "",

            rules: {
                m_modifier_name: { required: true, maxlength: 255 },
                m_item_id: { required: true, min: 1 },
                m_modifier_description: { maxlength: 500 },
                m_unit_id: { required: true, min: 1 },
                m_currency_id: { required: true, min: 1 },
                m_quantity: { required: true, floatNumber: true },
                m_cost_modifier: { required: true, floatNumber: true },
                m_price_modifier: { required: true, floatNumber: true },
            },

            messages: {
                m_modifier_name: {
                    required: "Please enter the modifier name",
                    maxlength: "Modifier name cannot exceed 255 characters",
                },
                m_item_id: "Please select an item",
                m_modifier_description: {
                    maxlength: "Description cannot exceed 500 characters",
                },
                m_unit_id: "Please select a unit",
                m_currency_id: "Please select a currency",
                m_quantity: {
                    required: "Please enter the quantity",
                    floatNumber:
                        "Quantity must be a positive number (e.g. 1.5)",
                },
                m_cost_modifier: {
                    required: "Please enter the cost modifier",
                    floatNumber: "Cost must be a positive number (e.g. 10.5)",
                },
                m_price_modifier: {
                    required: "Please enter the price modifier",
                    floatNumber: "Price must be a positive number (e.g. 15.75)",
                },
            },

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

            submitHandler: function () {
                success3.show();
                error3.hide();

                var base_url = $("#BASE_URL").val();
                var str_params = $("#FORM_SAVE_MODIFIER").serialize();

                $.ajax({
                    url: base_url + "/request/modifiers/saveinfo",
                    data: str_params,
                    method: "POST",
                    dataType: "json",
                    success: function (response) {
                        if (response.is_error == 0) {
                            window.location.href = base_url + "/fnb/modifiers";
                        } else {
                            error3
                                .show()
                                .text(response.error_msg || "Unexpected error");
                            success3.hide();
                        }
                    },
                    error: function (xhr) {
                        error3.show().text("Request failed: " + xhr.statusText);
                        success3.hide();
                    },
                });

                return false;
            },
        });
    },

    DeleteModifierData: function () {
        var m_id = $(this).data("m_id");
        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            //result
            if (result == true) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();
                var str_params = { m_id: m_id, _token: _token };
                $.ajax({
                    url: base_url + "/request/modifiers/deletemodifierinfo",
                    data: str_params,
                    dataType: "Json",
                    type: "delete",
                    success: function (response) {
                        if (response.is_error == 0) {
                            modifiers_module.DisplayListModifiers();
                        }
                    },
                });
            }
        });
    },
    EditModifierInfo: function () {
        var m_id = $(this).data("m_id");
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/modifiers/editform/" + m_id;
    },
    backToPreviousPage: function () {
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/modifiers";
    },
};
