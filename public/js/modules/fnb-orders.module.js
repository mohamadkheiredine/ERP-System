orders_module = {
    DisplayListOrders: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        var page_number = $("input[name=page_number]").val();
        var general_search = $("input[name=general_search]").val();
        var ps_company_id = $("select[name=fo_branch_id]").val();
        $.ajax({
            url: base_url + "/request/orders/displaylistorders",
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
                $("#LstOrdersGrid").html(response.display);
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
                    $.pagination = $("#OrdersPagination").twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $("input[name=page_number]").val(page);
                            orders_module.DisplayListOrders();
                        },
                    });
                }
            },
        });
    },
    SaveOrderInfo: function () {
        return orders_module.SaveOrderInfoSubmitHandler();
    },
    SaveOrderInfoSubmitHandler: function () {
    var OrderForm = $("#FORM_SAVE_ORDER");
    var error3 = $(".alert-danger", OrderForm);
    var success3 = $(".alert-success", OrderForm);

    OrderForm.validate({
        errorElement: "span",
        errorClass: "help-block help-block-error",
        focusInvalid: false,
        ignore: "",

        rules: {
            fo_order_code: { required: true, maxlength: 255 },
            ps_company_id: { required: true, min: 1 },
            fo_order_type: { required: true },
            fo_store_id: { required: true, min: 1 },
            fo_table_id: { required: true, min: 1 },
            fo_customer_id: { required: true, min: 1 },
            fo_order_status: { required: true, min: 1 },
            fo_subtotal: { required: true, number: true, min: 0 },
            fo_discount: { required: true, number: true, min: 0 },
            fo_tax: { required: true, number: true, min: 0 },
            fo_service_charge: { required: true, number: true, min: 0 },
            fo_total_amount: { required: true, number: true, min: 0 },
            fo_paid_amount: { required: true, number: true, min: 0 },
            cc_id: { required: true, min: 1 },
            fo_payment_status: { required: true },
            fo_notes: { required: true }
        },

        messages: {
            fo_order_code: {
                required: "Order code is required",
                maxlength: "Order code cannot exceed 255 characters"
            },
            ps_company_id: { required: "Please select a company", min: "Please select a company" },
            fo_order_type: { required: "Please select order type" },
            fo_store_id: { required: "Please select a store", min: "Please select a store" },
            fo_table_id: { required: "Please select a table", min: "Please select a table" },
            fo_customer_id: { required: "Please select customer", min: "Please select a customer" },
            fo_order_status: { required: "Please select a status", min: "Please select a status" },
            fo_subtotal: { required: "Subtotal is required", number: "Enter a valid number" },
            fo_discount: { required: "Discount is required", number: "Enter a valid number" },
            fo_tax: { required: "Tax is required", number: "Enter a valid number" },
            fo_service_charge: { required: "Service charge is required", number: "Enter a valid number" },
            fo_total_amount: { required: "Total amount is required", number: "Enter a valid number" },
            fo_paid_amount: { required: "Paid amount is required", number: "Enter a valid number" },
            cc_id: { required: "Please select a currency", min: "Please select a currency" },
            fo_payment_status: { required: "Please select payment status" },
            fo_notes: { required: "Please enter notes" }
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
            var str_params = OrderForm.serialize();

            $.ajax({
                url: base_url + "/request/fnb-orders/saveinfo",
                method: "POST",
                data: str_params,
                dataType: "json",
                success: function (response) {
                    if (response.is_error == 0) {
                        window.location.href = base_url + "/fnb/orders";
                    }
                },
            });
        },
    });
},


    DeleteOrderData: function () {
        var fo_id = $(this).data("fo_id");
        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            //result
            if (result == true) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();
                var str_params = { fo_id: fo_id, _token: _token };
                $.ajax({
                    url: base_url + "/request/orders/deleteorderinfo",
                    data: str_params,
                    dataType: "Json",
                    type: "delete",
                    success: function (response) {
                        if (response.is_error == 0) {
                            orders_module.DisplayListOrders();
                        }
                    },
                });
            }
        });
    },
    EditOrderInfo: function () {
        var fo_id = $(this).data("fo_id");
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/orders/editform/" + fo_id;
    },
    backToPreviousPage: function () {
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/orders";
    },
};
