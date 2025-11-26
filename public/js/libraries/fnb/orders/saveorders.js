$(function () {
    ClassicEditor.create(document.querySelector("#FO_NOTES"))
        .then((newEditor) => {
            $.editor = newEditor;
        })
        .catch((error) => {
            console.error(error);
        });

    ClassicEditor.create(document.querySelector("#OI_NOTES"))
        .then((newEditor) => {
            $.editor = newEditor;
        })
        .catch((error) => {
            console.error(error);
        });

    $("button[id*=BTN_SAVE_ORDER]").on("click", orders_module.SaveOrderInfo);
    $("button[name=back_form]").on("click", orders_module.backToPreviousPage);

    orders_module.DisplayListItemsOrder();
    orders_module.DisplayListDeliveries();

    $(document).on("click", "#BTN_SAVE_ITEM_ORDER", function () {
        orders_module.SaveItemOrderInfo();
    });

    $("#LstItemsOrders").on(
        "click",
        "a[id*=DELETE_ITEM_ORDER_]",
        orders_module.DeleteItemOrder
    );

    $(document).on("click", "a[id^='OPEN_MODIFIERS_ITEM_ORDER_']", function () {
        let im_item_id = $(this).data("oi_id");

        $("#IM_ITEM_ID").val(im_item_id);
        orders_module.DisplayItemOrdersModifiers();
    });

    $(document).on("change", "#IM_MODIFIER_ID", function () {
        let modifierName = $("#IM_MODIFIER_ID option:selected").text();
        $("#IM_MODIFIER_NAME").val(modifierName);
    });

    $(document).on("click", "#BTN_SAVE_ITEM_ORDER_MODIFIER", function () {
        orders_module.SaveOrderItemModifierInfo();
    });

    $("#ModelPopUpModifiers").on("hidden.bs.modal", function () {
        orders_module.DisplayListItemsOrder();
    });

    $("#DeliveryPopUp").on("hidden.bs.modal", function () {
        orders_module.DisplayListDeliveries();
    });

    $(document).on(
        "click",
        "a[id^='DELETE_ITEM_ORDER_MODIFIER_']",
        function () {
            orders_module.DeleteItemOrdersModifiers(this);
        }
    );

    $(document).ready(function () {
        function toggleDeliveryTab() {
            var type = $("#FO_ORDER_TYPE").val();

            if (type === "delivery") {
                $("#DELIVERY_TAB").show();
            } else {
                $("#DELIVERY_TAB").hide();
                $('a[href="#kt_tab_pane_1"]').tab("show");
            }
        }
        toggleDeliveryTab();
        $("#FO_ORDER_TYPE").on("change", function () {
            toggleDeliveryTab();
        });
    });

    $(document).on("click", "#BTN_SAVE_DELIVERY", function () {
        orders_module.SaveDeliveryInfo();
    });

    $(document).on("click", "a[id^='DELETE_DELIVERY_']", function () {
        orders_module.DeleteDelivery(this);
    });
});
