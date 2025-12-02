/**
 *
 */

fnb_items_module = {
    DisplayListItems: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();
        var page_number = $("input[name=page_number]").val();
        var search_query = $("input[name=general_search]").val();
        var category_id = $("select[name=mi_category_id]").val();
        $.ajax({
            url: base_url + "/request/fnbitems/displaylistitems",
            data: {
                _token: _token,
                page_number: page_number,
                search_query: search_query,
                category_id: category_id,
            },
            method: "GET",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $("#LstItems").html(response.display);
                $(".group-checkable").change(function () {
                    var set = $("item").find(
                        'tbody > tr > td:nth-child(1) input[type="checkbox"]'
                    );
                    var checked = $(this).prop("checked");
                    $(set).each(function () {
                        $(this).prop("checked", checked);
                    });
                    $.uniform.update(set);
                });
                $.pagination = $("#ItemsPagination").twbsPagination({
                    totalPages: response.total_pages,
                    visiblePages: 7,
                    onPageClick: function (event, page) {
                        $("input[name=page_number]").val(page);
                        fnb_items_module.DisplayListItems();
                    },
                });
            },
        });
    },
    SaveItemInfo: function () {
        return fnb_items_module.SaveItemInfoSubmitHandler();
    },
    SaveItemInfoSubmitHandler: function () {
        var ItemForm = $("#FORM_SAVE_ITEM");
        var error3 = $(".alert-danger", ItemForm);
        var success3 = $(".alert-success", ItemForm);

        ItemForm.validate({
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: false,
            ignore: "",

            rules: {
                mi_item_name: {
                    required: true,
                    maxlength: 150,
                },
                mi_barcode: {
                    required: true,
                    maxlength: 100,
                },
                mi_base_price: {
                    required: true,
                    number: true,
                },
                mi_currency_id: {
                    required: true,
                    min: 1,
                },
                mi_category_id: {
                    required: true,
                    min: 1,
                },
            },

            messages: {
                mi_item_name: {
                    required: "Please enter the item name",
                    maxlength: "Item name cannot exceed 150 characters",
                },
                mi_barcode: {
                    required: "Please enter product barcode",
                },
                mi_base_price: {
                    required: "Please enter item price",
                    number: "Invalid price format",
                },
                mi_currency_id: "Please select a currency",
                mi_category_id: "Please select a category",
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
                var formData = new FormData($("#FORM_SAVE_ITEM")[0]);

                $.ajax({
                    url: base_url + "/request/menuitems/saveiteminfo",
                    data: formData,
                    method: "POST",
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.is_error == 0) {
                            window.location.href = base_url + "/fnb/menuitems";
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

    backToPreviousPage: function () {
        window.history.back();
    },

    DeleteItem: function () {
        var mi_id = $(this).data("mi_id");
        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            //result
            if (result == true) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();
                var str_params = { mi_id: mi_id, _token: _token };
                $.ajax({
                    url: base_url + "/request/fnbitems/deleteiteminfo",
                    data: str_params,
                    dataType: "Json",
                    type: "delete",
                    success: function (response) {
                        if (response.is_error == 0) {
                            fnb_items_module.DisplayListItems();
                        }
                    },
                });
            }
        });
    },

    DisplayListItemsModifiers: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        var page_number = $("input[name=page_number]").val();
        var modifier_id = $("select[name=fk_modifier_id]").val();
        var product_id = $("select[name=im_product_id]").val();
        var currency_id = $("select[name=im_currency_id]").val();
        var item_id = $("input[name=fk_menu_item_id]").val();

        $.ajax({
            url:
                base_url +
                "/request/fnbitemsmodifiers/displaylistitemsmodifiers",
            data: {
                _token: _token,
                page_number: page_number,
                modifier_id: modifier_id,
                product_id: product_id,
                currency_id: currency_id,
                item_id: item_id,
            },
            method: "GET",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $("#LstItemsModifiers").html(response.display);
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
                $.pagination = $("#ItemsModifiersPagination").twbsPagination({
                    totalPages: response.total_pages,
                    visiblePages: 7,
                    onPageClick: function (event, page) {
                        $("input[name=page_number]").val(page);
                        fnb_items_module.DisplayListItemsModifiers();
                    },
                });
            },
        });
    },

    SaveItemModifierInfo: function () {
        return fnb_items_module.SaveItemModifierHandlerSubmit();
    },

    SaveItemModifierHandlerSubmit: function () {
        var ItemModifierForm = $("#FORM_SAVE_ITEM_MODIFIERS");
        var error3 = $(".alert-danger", ItemModifierForm);
        var success3 = $(".alert-success", ItemModifierForm);

        ItemModifierForm.validate({
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: false,
            ignore: "",

            rules: {
                im_override_cost: { required: true, maxlength: 160 },
                im_product_id: { required: true, min: 1 },
                im_currency_id: { required: true, min: 1 },
                im_type_id: { required: true, min: 1 },
            },

            messages: {
                im_override_cost: {
                    required: "Please enter the override cost",
                },
                im_product_id: "Please select a Product",
                im_currency_id: "Please select a Currency",
                im_type_id: "Please select a Type",
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
                var str_params = ItemModifierForm.serialize();
                console.log("strings params  ", str_params);

                $.ajax({
                    url: base_url + "/request/menuitemsmodifiers/saveiteminfo",
                    data: str_params,
                    method: "POST",
                    dataType: "json",
                    success: function (response) {
                        if (response.is_error === 0) {
                            fnb_items_module.DisplayListItemsModifiers();

                            success3.text("Saved successfully!").fadeIn();
                            setTimeout(function () {
                                $("#ModelPopUp").modal("hide");
                                success3.hide();
                                ItemModifierForm[0].reset();
                            }, 800);
                        } else {
                            error3
                                .text(response.error_msg || "Unexpected error")
                                .show();
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

    DeleteItemModifier: function () {
        var im_id = $(this).data("im_id");
        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            //result
            if (result == true) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();
                var str_params = { im_id: im_id, _token: _token };
                $.ajax({
                    url: base_url + "/request/fnbitemsmodifiers/deleteiteminfo",
                    data: str_params,
                    dataType: "Json",
                    type: "delete",
                    success: function (response) {
                        if (response.is_error == 0) {
                            fnb_items_module.DisplayListItemsModifiers();
                        }
                    },
                });
            }
        });
    },

    getValues: function () {
        var modifier_id = $(this).val();
        var base_url = $("#BASE_URL").val();
        var _token = $("input[name=_token]").val();

        if (modifier_id == 0) {
            $("#IM_OVERRIDE_COST").val("");
            $("input[name=product_name]").val("");
            $("#IM_CURRENCY_ID").val(0).change();
            return;
        }

        $.ajax({
            url: base_url + "/request/modifier/details",
            method: "GET",
            data: { modifier_id: modifier_id, _token: _token },
            success: function (response) {
                if (response.success) {
                    $("input[name=product_name]").val(response.product_name);
                    $("#IM_CURRENCY_ID").val(response.currency_id).change();
                    $("#IM_OVERRIDE_COST").val(response.cost);
                    $("input[name=im_product_id]").val(response.product_id);
                }
            },
        });
    },
};
