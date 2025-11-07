$(function () {
    kitchen_module.DisplayListKitchens();
    $("select[name=ps_company_id]").on(
        "change",
        kitchen_module.DisplayListKitchens
    );

    $("input[name=general_search]").on('keyup', kitchen_module.DisplayListKitchens);

    $("#LstKitchensGrid").on(
        "click",
        "a[id*=EDIT_KITCHEN_]",
        kitchen_module.EditKitchenInfo
    );
    $("#LstKitchensGrid").on(
        "click",
        "a[id*=DELETE_KITCHEN_]",
        kitchen_module.DeleteKitchenData
    );
});
