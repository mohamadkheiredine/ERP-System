$(function () {
    modifiers_module.DisplayListModifiers();

    $("input[name=general_search]").on('keyup', modifiers_module.DisplayListModifiers);

    $("#LstModifiersGrid").on(
        "click",
        "a[id*=EDIT_MODIFIER_]",
        modifiers_module.EditModifierInfo
    );
    $("#LstModifiersGrid").on(
        "click",
        "a[id*=DELETE_MODIFIER_]",
        modifiers_module.DeleteModifierData
    );
});
