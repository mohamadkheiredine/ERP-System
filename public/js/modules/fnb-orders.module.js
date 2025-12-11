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
            ignore: "",
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: false,

            rules: {
                fo_order_code: { required: true, maxlength: 255 },
                fo_branch_id: { required: true, min: 1 },
                fo_order_type: { required: true },
                fo_store_id: { required: true, min: 1 },
                "fo_table_id[]": {
                    required: true,
                },
                fo_customer_id: { required: true, min: 1 },
                fo_order_status: { required: true, min: 1 },
                fo_subtotal: { required: true, number: true, min: 0 },
                fo_discount: { required: true, number: true, min: 0 },
                fo_tax: { required: true, number: true, min: 0 },
                fo_service_charge: { required: true, number: true, min: 0 },
                fo_total_amount: { required: true, number: true, min: 0 },
                fo_paid_amount: { required: true, number: true, min: 0 },
                cc_id: { required: true, min: 1 },
            },

            messages: {
                fo_order_code: {
                    required: "Order code is required",
                    maxlength: "Order code cannot exceed 255 characters",
                },
                ps_company_id: {
                    required: "Please select a company",
                    min: "Please select a company",
                },
                fo_order_type: { required: "Please select order type" },
                fo_store_id: {
                    required: "Please select a store",
                    min: "Please select a store",
                },
                "fo_table_id[]": {
                    required: "Please select a table",
                    min: "Please select a table",
                },
                fo_customer_id: {
                    required: "Please select customer",
                    min: "Please select a customer",
                },
                fo_order_status: {
                    required: "Please select a status",
                    min: "Please select a status",
                },
                fo_subtotal: {
                    required: "Subtotal is required",
                    number: "Enter a valid number",
                },
                fo_discount: {
                    required: "Discount is required",
                    number: "Enter a valid number",
                },
                fo_tax: {
                    required: "Tax is required",
                    number: "Enter a valid number",
                },
                fo_service_charge: {
                    required: "Service charge is required",
                    number: "Enter a valid number",
                },
                fo_total_amount: {
                    required: "Total amount is required",
                    number: "Enter a valid number",
                },
                fo_paid_amount: {
                    required: "Paid amount is required",
                    number: "Enter a valid number",
                },
                cc_id: {
                    required: "Please select a currency",
                    min: "Please select a currency",
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

    DisplayListItemsOrder: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();
        var page_number = $("input[name=page_number]").val();

        var order_id = $("input[name=oi_order_id]").val();

        $.ajax({
            url: base_url + "/request/fnbitemsorders/displaylistitemsorders",
            data: {
                _token: _token,
                page_number: page_number,
                order_id,
            },
            method: "GET",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $("#LstItemsOrders").html(response.display);
                $("#ItemsOrdersTotal").html(response.total_price);
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
                $.pagination = $("#ItemsOrdersPagination").twbsPagination({
                    totalPages: response.total_pages,
                    visiblePages: 7,
                    onPageClick: function (event, page) {
                        $("input[name=page_number]").val(page);
                        orders_module.DisplayListItemsOrder();
                    },
                });
            },
        });
    },

    SaveItemOrderInfo: function () {
        return orders_module.SaveItemOrderHandlerSubmit();
    },

    SaveItemOrderHandlerSubmit: function () {
        var ItemOrderForm = $("#FORM_SAVE_ITEM_ORDERS");
        var error3 = $(".alert-danger", ItemOrderForm);
        var success3 = $(".alert-success", ItemOrderForm);

        ItemOrderForm.validate({
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: false,
            ignore: "",

            rules: {
                oi_item_id: { required: true, min: 1 },
                oi_quantity: { required: true, number: true, min: 1 },
                oi_currency_id: { required: true, min: 1 },
                oi_unit_price: { required: true, number: true, min: 0 },
                oi_item_discount: { required: true, number: true, min: 0 },
                oi_kitchen_status: { required: true, min: 1 },
                oi_station_id: { required: true, min: 1 },
                oi_notes: { required: true, minlength: 1 },
            },

            messages: {
                oi_item_id: "Please select an item",
                oi_quantity: "Please enter a valid quantity",
                oi_currency_id: "Please select a currency",
                oi_unit_price: "Please enter a unit price",
                oi_item_discount: "Please enter a discount",
                oi_kitchen_status: "Please select a kitchen status",
                oi_station_id: "Please select a station",
                oi_notes: "Please enter notes",
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

            submitHandler: function () {
                success3.show();
                error3.hide();

                var base_url = $("#BASE_URL").val();
                var str_params = ItemOrderForm.serialize();

                $.ajax({
                    url: base_url + "/request/orders/saveitemorder",
                    data: str_params,
                    method: "POST",
                    dataType: "json",
                    success: function (response) {
                        if (response.is_error === 0) {
                            orders_module.DisplayListItemsOrder();
                            success3.text("Saved successfully!").fadeIn();
                            setTimeout(function () {
                                $("#ModelPopUp").modal("hide");
                                ItemOrderForm[0].reset();

                                $("#OI_ITEM_ID").val(0).trigger("change");
                                $("#OI_CURRENCY_ID").val(0).trigger("change");
                                $("#OI_KITCHEN_STATUS")
                                    .val(0)
                                    .trigger("change");
                                $("#OI_STATION_ID").val(0).trigger("change");

                                $("#OI_NOTES").val("");

                                success3.hide();
                            }, 800);
                        } else {
                            error3
                                .text(response.error_msg || "Unexpected error")
                                .show();
                        }
                    },
                    error: function (xhr) {
                        error3.text("Request failed: " + xhr.statusText).show();
                        success3.hide();
                    },
                });

                return false;
            },
        });
    },

    DeleteItemOrder: function () {
        var oi_id = $(this).data("oi_id");
        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            //result
            if (result == true) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();
                var str_params = { oi_id: oi_id, _token: _token };
                $.ajax({
                    url: base_url + "/request/fnbitemsorder/deleteiteminfo",
                    data: str_params,
                    dataType: "Json",
                    type: "delete",
                    success: function (response) {
                        if (response.is_error == 0) {
                            orders_module.DisplayListItemsOrder();
                        }
                    },
                });
            }
        });
    },

    DisplayItemOrdersModifiers: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        $.ajax({
            url: base_url + "/request/orders/displaylistordersitemsmodifiers",
            data: {
                _token: _token,
            },
            method: "get",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $("#LstItemsOrdersModifiers").html(response.display);
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
            },
        });
    },

    SaveOrderItemModifierInfo: function () {
        return orders_module.SaveItemOrderModifierHandlerSubmit();
    },

    SaveItemOrderModifierHandlerSubmit: function () {
        var ItemOrderModifierForm = $("#FORM_SAVE_ITEM_ORDERS_MODIFIERS");
        var error4 = $(".alert-danger", ItemOrderModifierForm);
        var success4 = $(".alert-success", ItemOrderModifierForm);

        ItemOrderModifierForm.validate({
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: false,
            ignore: "",

            rules: {
                im_modifier_cost: { required: true, number: true, min: 0 },
                im_modifier_id: { required: true, min: 1 },
                im_modifier_type: { required: true },
                im_currency_id: { required: true, min: 1 },
            },

            messages: {
                im_modifier_cost: "Please enter modifier cost",
                im_modifier_id: "Please select a modifier",
                im_modifier_type: "Please select type",
                im_currency_id: "Please select currency",
            },

            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },

            invalidHandler: function () {
                success4.hide();
                error4.show();
            },

            highlight: function (element) {
                $(element).closest(".form-group").addClass("has-error");
            },
            unhighlight: function (element) {
                $(element).closest(".form-group").removeClass("has-error");
            },

            submitHandler: function () {
                success4.show();
                error4.hide();

                let base_url = $("#BASE_URL").val();
                let formData = ItemOrderModifierForm.serialize();

                $.ajax({
                    url: base_url + "/request/orders/savemodifieritemorder",
                    method: "POST",
                    data: formData,
                    dataType: "json",

                    success: function (response) {
                        if (response.is_error === 0) {
                            orders_module.DisplayItemOrdersModifiers();

                            success4.text("Saved successfully!").fadeIn();

                            setTimeout(function () {
                                ItemOrderModifierForm[0].reset();
                                $("#IM_MODIFIER_ID").val(0).trigger("change");
                                $("#IM_MODIFIER_TYPE").val(0).trigger("change");
                                $("#IM_CURRENCY_ID").val(0).trigger("change");

                                $("#IM_MODIFIER_COST").val("");
                                $("#IM_MODIFIER_NAME").val("");

                                success4.hide();
                            }, 800);
                        } else {
                            error4
                                .text(response.error_msg || "Unexpected error")
                                .show();
                        }
                    },

                    error: function (xhr) {
                        error4.text("Request failed: " + xhr.statusText).show();
                        success4.hide();
                    },
                });

                return false;
            },
        });
    },

    DeleteItemOrdersModifiers: function (el) {
        var im_id = $(el).data("im_id");
        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            //result
            if (result == true) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();
                var str_params = { im_id: im_id, _token: _token };
                $.ajax({
                    url: base_url + "/request/orders/deleteitemordermodifier",
                    data: str_params,
                    dataType: "Json",
                    type: "delete",
                    success: function (response) {
                        if (response.is_error == 0) {
                            orders_module.DisplayItemOrdersModifiers();
                        }
                    },
                });
            }
        });
    },

    SaveDeliveryInfo: function () {
        return orders_module.SaveDeliveryHandlerSubmit();
    },

    SaveDeliveryHandlerSubmit: function () {
        var DeliveryForm = $("#FORM_SAVE_DELIVERY");
        var error4 = $(".alert-danger", DeliveryForm);
        var success4 = $(".alert-success", DeliveryForm);

        DeliveryForm.validate({
            errorElement: "span",
            errorClass: "help-block help-block-error",
            focusInvalid: false,
            ignore: "",

            rules: {
                od_delivery_status: { required: true, min: 1 },
                od_delivery_address: { required: true },
                ic_customer_name: { required: true },
                ic_customer_phone: { required: true, digits: true },
                od_delivery_cost: { required: true, number: true, min: 0 },
            },

            messages: {
                od_delivery_status: "Please select a delivery status",
                od_delivery_address: "Please enter the delivery address",
                ic_customer_name: "Please enter customer name",
                ic_customer_phone: "Please enter valid phone number",
                od_delivery_cost: "Please enter valid delivery cost",
            },

            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },

            invalidHandler: function () {
                success4.hide();
                error4.show();
            },

            highlight: function (element) {
                $(element).closest(".form-group").addClass("has-error");
            },
            unhighlight: function (element) {
                $(element).closest(".form-group").removeClass("has-error");
            },

            submitHandler: function () {
                success4.hide();
                error4.hide();

                let base_url = $("#BASE_URL").val();
                let formData = DeliveryForm.serialize();

                $.ajax({
                    url: base_url + "/request/orders/savedelivery",
                    method: "POST",
                    data: formData,
                    dataType: "json",

                    success: function (response) {
                        if (response.is_error === 0) {
                            success4.text("Saved successfully!").fadeIn();
                            setTimeout(function () {
                                DeliveryForm[0].reset();

                                $("#OD_DELIVERY_STATUS")
                                    .val(0)
                                    .trigger("change");
                                $("#IC_CUSTOMER_PHONE").val("");
                                $("#OD_DELIVERY_COST").val("");

                                success4.hide();
                                $("#DeliveryPopUp").modal("hide");
                            }, 800);
                        } else {
                            error4
                                .text(response.error_msg || "Unexpected error!")
                                .show();
                        }
                    },

                    error: function (xhr) {
                        error4.text("Request failed: " + xhr.statusText).show();
                        success4.hide();
                    },
                });

                return false;
            },
        });
    },

    DisplayListDeliveries: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        $.ajax({
            url: base_url + "/request/orders/displaydeliveries",
            data: {
                _token: _token,
            },
            method: "get",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $("#LstDeliveries").html(response.display);
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
                    $.pagination = $("#DeliveryPagination").twbsPagination({
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

    DeleteDelivery: function (el) {
        var delivery_id = $(el).data("delivery_id");
        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            //result
            if (result == true) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();
                var str_params = { delivery_id: delivery_id, _token: _token };
                $.ajax({
                    url: base_url + "/request/orders/deletedelivery",
                    data: str_params,
                    dataType: "Json",
                    type: "delete",
                    success: function (response) {
                        if (response.is_error == 0) {
                            orders_module.DisplayListDeliveries();
                        }
                    },
                });
            }
        });
    },

    AutoSelectKitchen: function () {
        let item_id = $("#OI_ITEM_ID").val();
        var base_url = $("#BASE_URL").val();
        var _token = $("input[name=_token]").val();

        $.ajax({
        url: base_url + "/request/orders/getKitchen",
        method: "GET",
        data: { item_id, _token },
        success: function (response) {
            if (response.station_id) {
                $("#OI_STATION_ID").val(response.station_id).trigger("change");
            } else {
                $("#OI_STATION_ID").val(0).trigger("change");
            }
        }
    });
    },
};
