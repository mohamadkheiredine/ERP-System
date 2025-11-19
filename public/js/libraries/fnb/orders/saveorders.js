$(function () {
    ClassicEditor.create(document.querySelector("#FO_NOTES"))
        .then((newEditor) => {
            $.editor = newEditor;
        })
        .catch((error) => {
            console.error(error);
        });

    $("button[id*=BTN_SAVE_ORDER]").on(
        "click",
        orders_module.SaveOrderInfo
    );
    $("button[name=back_form]").on("click", orders_module.backToPreviousPage);
});
