kitchen_module = {
    DisplayListKitchens: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        var page_number = $("input[name=page_number]").val();
        var general_search = $("input[name=general_search]").val();
        var ps_company_id = $("select[name=ps_company_id]").val();
        $.ajax({
            url: base_url + "/request/kitchen/displaylistkitchens",
            data: {
                _token: _token,
                page_number: page_number,
                general_search: general_search,
                ps_company_id: ps_company_id,
            },
            method: "get",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $("#LstKitchensGrid").html(response.display);
                $(".group-checkable").change(function () {
                    var set = $("kitchen").find(
                        'tbody > tr > td:nth-child(1) input[type="checkbox"]'
                    );
                    var checked = $(this).prop("checked");
                    $(set).each(function () {
                        $(this).prop("checked", checked);
                    });
                    $.uniform.update(set);
                });
                if (response.total_pages > 0) {
                    $.pagination = $("#KitchensPagination").twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $("input[name=page_number]").val(page);
                            kitchen_module.DisplayListKitchens();
                        },
                    });
                }
            },
        });
    },
    SaveKitchenInfo: function () {
        return kitchen_module.SaveKitchenInfoSubmitHandler();
    },
    SaveKitchenInfoSubmitHandler: function () {
        var KitchenForm = $("#FORM_SAVE_KITCHEN");
        var error3 = $(".alert-danger", KitchenForm);
        var success3 = $(".alert-success", KitchenForm);

        KitchenForm.validate({
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: false,
            ignore: "",

            rules: {
                ks_name: { required: true, maxlength: 255 }, // Kitchen Name
            },

            messages: {
                ks_name: {
                    required: "Please enter the kitchen name",
                    maxlength: "Kitchen name cannot exceed 255 characters",
                },
                ks_description: {
                    required: "Please enter a description for the kitchen station",
                }
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
                var str_params = $("#FORM_SAVE_KITCHEN").serialize();

                $.ajax({
                    url: base_url + "/request/kitchen/saveinfo",
                    data: str_params,
                    method: "POST",
                    dataType: "json",
                    success: function (response) {
                        if (response.is_error == 0) {
                            window.location.href = base_url + "/fnb/kitchen";
                        }
                    },
                });
            },
        });
    },

    DeleteKitchenData: function () {
        var ks_id = $(this).data("ks_id");
        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            //result
            if (result == true) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();
                var str_params = { ks_id: ks_id, _token: _token };
                $.ajax({
                    url: base_url + "/request/kitchen/deletekitcheninfo",
                    data: str_params,
                    dataType: "Json",
                    type: "delete",
                    success: function (response) {
                        if (response.is_error == 0) {
                            kitchen_module.DisplayListKitchens();
                        }
                    },
                });
            }
        });
    },
    EditKitchenInfo: function () {
        var ks_id = $(this).data("ks_id");
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/kitchen/editform/" + ks_id;
    },
    backToPreviousPage: function () {
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/kitchen";
    },
};
