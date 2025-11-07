/**
 *
 */

fnb_itemscategory_module = {
    DisplayListCategoryItems: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();
        var page_number = $("input[name=page_number]").val();
        var category_id = $("input[name=mc_id]").val();
        var search_query = $("input[name=general_search]").val();
        var branch_id = $("select[name=fi_company_name]").val();
        var kitchen_id = $("select[name=fi_kitchen_name]").val();
        $.ajax({
            url: base_url + "/request/fnbcategories/displaylistitems",
            data: {
                _token: _token,
                page_number: page_number,
                search_query: search_query,
                category_id: category_id,
                branch_id: branch_id,
                kitchen_id: kitchen_id,
            },
            method: "GET",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $("#LstProducts").html(response.display);
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
                $.pagination = $("#ProductsPagination").twbsPagination({
                    totalPages: response.total_pages,
                    visiblePages: 7,
                    onPageClick: function (event, page) {
                        $("input[name=page_number]").val(page);
                        itemscategory_module.DisplayListItems();
                    },
                });
            },
        });
    },
    SaveItemInfo: function () {
        return fnb_itemscategory_module.SaveItemInfoSubmitHandler();
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
                fi_item_name: { required: true, maxlength: 160 },
                fi_branch_id: { required: true, min: 1 },
                fi_category_id: { required: true, min: 1 },
                fi_kitchen_id: { required: true, min: 1 },
                fi_station_id: { required: true, min: 1 },
                fi_tax_id: { required: true, min: 1 },
            },

            messages: {
                fi_item_name: {
                    required: "Please enter the item name",
                    maxlength: "Item name cannot exceed 160 characters",
                },
                fi_branch_id: "Please select a company",
                fi_category_id: "Please select a category",
                fi_kitchen_id: "Please select a kitchen",
                fi_station_id: "Please select a station",
                fi_tax_id: "Please select a tax",
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
                var str_params = $("#FORM_SAVE_ITEM").serialize();

                // Detect add or edit
                const fi_id = $("input[name=fi_id]").val(); // empty if new item
                const fi_category_id = $("#FI_CATEGORY_ID").val();

                $.ajax({
                    url: base_url + "/request/fnbcategories/saveiteminfo",
                    data: str_params,
                    method: "POST",
                    dataType: "json",
                    success: function (response) {
                        if (response.is_error == 0) {
                            const redirectCategory = fi_id
                                ? fi_category_id
                                : -1;

                            window.location.href =
                                base_url +
                                "/fnb/categories/listitems/" +
                                redirectCategory;
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
};
