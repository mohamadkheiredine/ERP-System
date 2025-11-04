floors_module = {
    DisplayListFloors: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        var page_number = $("input[name=page_number]").val();
        var general_search = $("input[name=general_search]").val();
        var fl_branch_id = $("select[name=fl_branch_id]").val();
        $.ajax({
            url: base_url + "/request/floors/displaylistfloors",
            data: {
                _token: _token,
                page_number: page_number,
                general_search: general_search,
                fl_branch_id: fl_branch_id,
            },
            method: "get",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $(".LstFloorsGrid").html(response.display);
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
                    $.pagination = $("#FloorsPagination").twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $("input[name=page_number]").val(page);
                            floors_module.DisplayListFloors();
                        },
                    });
                }
            },
        });
    },
    SaveFloorInfo: function () {
        return floors_module.SaveFloorInfoSubmitHandler();
    },
    SaveFloorInfoSubmitHandler: function () {
        var FloorForm = $("#FORM_SAVE_FLOOR");
        var error3 = $(".alert-danger", FloorForm);
        var success3 = $(".alert-success", FloorForm);

        FloorForm.validate({
            errorElement: "span", //default input error message container
            errorClass: "help-block help-block-error", // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                fl_company_id: { required: true },
                general_search: { required: true },
            },

            messages: {
                fl_company_id: "Please select a company",
                general_search: "Please enter a search keyword",
            },
            // This controls where the message appears:
            errorPlacement: function (error, element) {
                if (element.attr("id") === "FL_COMPANY_ID") {
                    // For dropdown: place error below it
                    error.insertAfter(element);
                } else if (element.attr("id") === "generalSearch") {
                    // For search input: place error below the entire input group
                    error.insertAfter(element);
                } else {
                    error.insertAfter(element); // default
                }
            },

            invalidHandler: function (event, validator) {
                //display error alert on form submit
                success3.hide();
                error3.show();
            },
            success: function (label) {
                label.closest(".form-group").removeClass("has-error"); // set success class to the control group
            },
            highlight: function (element) {
                // hightlight error inputs
                $(element).closest(".form-group").addClass("has-error"); // set error class to the control group
            },

            unhighlight: function (element) {
                // revert the change done by hightlight
                $(element).closest(".form-group").removeClass("has-error"); // set error class to the control group
            },

            submitHandler: function (form) {
                success3.show();
                error3.hide();
                var base_url = $("#BASE_URL").val();
                var str_params = $("#FORM_SAVE_FLOOR").serialize();
                $.ajax({
                    url: base_url + "/request/floors/saveinfo",
                    data: str_params,
                    method: "post",
                    dataType: "json",
                    success: function (response) {
                        if (response.is_error == 0) {
                            window.location.href = base_url + "/fnb/floors";
                        }
                    },
                });
            },
        });
    },
    DeleteFloorData: function () {
        var fl_id = $(this).data("fl_id");
        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            //result
            if (result == true) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();
                var str_params = { fl_id: fl_id, _token: _token };
                $.ajax({
                    url: base_url + "/request/floors/deletefloorinfo",
                    data: str_params,
                    dataType: "Json",
                    type: "delete",
                    success: function (response) {
                        if (response.is_error == 0) {
                            floors_module.DisplayListFloors();
                        }
                    },
                });
            }
        });
    },
    EditFloorInfo: function () {
        var fl_id = $(this).data("fl_id");
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/floors/editform/" + fl_id;
    },
    backToPreviousPage: function() {
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/floors";
    }
};
