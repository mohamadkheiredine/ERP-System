/**
 * Category Module
 * Handles CRUD operations for Menu Categories
 */

var category_module = {
    DisplayListCategories: function () {
        const base_url = $("#BASE_URL").val();
        const _token = $("input[name=_token]").val();
        const page_number = $("input[name=page_number]").val();
        const general_search = $("input[name=general_search]").val();

        $.ajax({
            url: base_url + "/request/category/displaylistcategories",
            data: {
                _token: _token,
                page_number: page_number,
                general_search: general_search,
            },
            method: "GET",
            dataType: "json",
            success : function(response){
                $('#LstCategoriesGrid').html(response.display);
                $('.group-checkable').change(function() {
                    var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                    var checked = $(this).prop("checked");
                    $(set).each(function() {
                        $(this).prop("checked", checked);
                    });
                    $.uniform.update(set);
                });
                if(response.total_pages > 0)
                {
                    $.pagination = $('#CategoriesPagination').twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $('input[name=page_number]').val(page);
                            category_module.DisplayListCategories();
                        }
                    });
                }

            }
        });
    },

    SaveCategoryInfo: function () {
        return category_module.SaveCategoryInfoSubmitHandler();
    },

    SaveCategoryInfoSubmitHandler: function () {
        const CategoryForm = $("#FORM_SAVE_CATEGORY");
        const error3 = $(".alert-danger", CategoryForm);
        const success3 = $(".alert-success", CategoryForm);

        CategoryForm.validate({
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: false,
            ignore: "",
            rules: {
                mc_category_name: {
                    required: true,
                },
                mc_category_description: {
                    required: true,
                    minlength: 5,
                },
            },

            message: {},
            errorPlacement: function (error, element) {
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function () {
                success3.hide();
                error3.show();
            },
            success: function (label) {
                label.closest(".form-group").removeClass("has-error");
            },
            highlight: function (element) {
                $(element).closest(".form-group").addClass("has-error");
            },
            unhighlight: function (element) {
                $(element).closest(".form-group").removeClass("has-error");
            },

            submitHandler: function () {
                success3.show();
                error3.hide();

                const base_url = $("#BASE_URL").val();
                const data = new FormData($("#FORM_SAVE_CATEGORY")[0]);

                if ($.editor) {
                    data.set("mc_category_description", $.editor.getData());
                }

                $.ajax({
                    url: base_url + "/request/category/saveinfo",
                    data: data,
                    method: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (response) {
                        if (response.is_error === 0) {
                            window.location.href = base_url + "/fnb/category";
                        } else {
                            error3.show();
                            success3.hide();
                        }
                    },
                    error: function () {
                        error3.show();
                        success3.hide();
                    },
                });
            },
        });
    },

    DeleteCategoryData: function () {
        const mc_id = $(this).data("mc_id");
        bootbox.confirm(
            "Are you sure you want to delete this category?",
            function (result) {
                if (result) {
                    const base_url = $("#BASE_URL").val();
                    const _token = $("input[name=_token]").val();

                    $.ajax({
                        url: base_url + "/request/category/deletecategoryinfo",
                        data: { mc_id: mc_id, _token: _token },
                        dataType: "json",
                        type: "DELETE",
                        success: function (response) {
                            if (response.is_error === 0) {
                                category_module.DisplayListCategories();
                            }
                        },
                    });
                }
            }
        );
    },

    EditCategoryInfo: function () {
        const mc_id = $(this).data("mc_id");
        const base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/category/editform/" + mc_id;
    },

    backToPreviousPage: function () {
        const base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/category";
    },
};
