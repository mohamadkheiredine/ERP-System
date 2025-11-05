tables_module = {
    DisplayListTables: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        var page_number = $("input[name=page_number]").val();
        var general_search = $("input[name=general_search]").val();
        var fl_id = $("select[name=fl_id]").val();
        $.ajax({
            url: base_url + "/request/tables/displaylisttables",
            data: {
                _token: _token,
                page_number: page_number,
                general_search: general_search,
                fl_id: fl_id,
            },
            method: "get",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $(".LstTablesGrid").html(response.display);
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
                    $.pagination = $("#TablesPagination").twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $("input[name=page_number]").val(page);
                            tables_module.DisplayListTables();
                        },
                    });
                }
            },
        });
    },
    SaveTableInfo: function () {
        return tables_module.SaveTableInfoSubmitHandler();
    },
    SaveTableInfoSubmitHandler: function () {
        var TableForm = $("#FORM_SAVE_TABLE");
        var error3 = $(".alert-danger", TableForm);
        var success3 = $(".alert-success", TableForm);

        $.validator.addMethod(
            "floatNumber",
            function (value, _) {
                // ✅ Only positive numbers (integer or decimal)
                return /^\d+(\.\d+)?$/.test(value);
            },
            "Please enter a valid positive number (e.g. 10 or 10.5)"
        );

        $.validator.addMethod(
            "validColor",
            function (value, element) {
                return /^#([A-Fa-f0-9]{3}){1,2}$/.test(value);
            },
            "Please select a valid color"
        );

        $("#FT_COLOR").on("change", function () {
            $(this).valid(); // re-check validity instantly after selecting a color
        });

        TableForm.validate({
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: false,
            ignore: "",

            rules: {
                ft_label: { required: true, maxlength: 255 }, // Table Name
                fl_id: { required: true, min: 1 },
                ft_capacity: {
                    required: true,
                    number: true,
                    min: 2,
                },
                ft_x_pos: { required: true, floatNumber: true },
                ft_y_pos: { required: true, floatNumber: true },
                ft_rotation: { required: true, floatNumber: true },
                ft_shape: {
                    required: true,
                    number: true,
                    min: 0,
                },
                ft_color: {
                    required: true,
                    validColor: true,
                },
            },

            messages: {
                ft_label: {
                    required: "Please enter the table name",
                    maxlength: "Table name cannot exceed 255 characters",
                },
                fl_id: "Please select a floor",
                ft_capacity: {
                    required: "Please enter the table capacity",
                    number: "Capacity must be a valid number",
                    min: "Capacity must be at least 2",
                },
                ft_x_pos: "Please enter the X position",
                ft_y_pos: "Please enter the Y position",
                ft_rotation: "Please enter the rotation value",
                ft_shape: {
                    required: "Please enter the shape value",
                    number: "Shape must be a valid number",
                    min: "Shape cannot be negative",
                },
                ft_color: "Please select a color",
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
                var str_params = $("#FORM_SAVE_TABLE").serialize();

                $.ajax({
                    url: base_url + "/request/tables/saveinfo",
                    data: str_params,
                    method: "POST",
                    dataType: "json",
                    success: function (response) {
                        if (response.is_error == 0) {
                            window.location.href = base_url + "/fnb/tables";
                        }
                    },
                });
            },
        });
    },

    DeleteTableData: function () {
        var ft_id = $(this).data("ft_id");
        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            //result
            if (result == true) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();
                var str_params = { ft_id: ft_id, _token: _token };
                $.ajax({
                    url: base_url + "/request/tables/deletetableinfo",
                    data: str_params,
                    dataType: "Json",
                    type: "delete",
                    success: function (response) {
                        if (response.is_error == 0) {
                            tables_module.DisplayListTables();
                        }
                    },
                });
            }
        });
    },
    EditTableInfo: function () {
        var ft_id = $(this).data("ft_id");
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/tables/editform/" + ft_id;
    },
    backToPreviousPage: function () {
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/tables";
    },
};
