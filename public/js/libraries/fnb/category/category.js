$(function () {
    category_module.DisplayListCategories();

    $("input[name=general_search]").on('keyup', category_module.DisplayListCategories);

    $("#LstCategoriesGrid").on(
        "click",
        "a[id*=EDIT_CATEGORY_]",
        kitchen_module.EditCategoryInfo
    );
    $("#LstCategoriesGrid").on(
        "click",
        "a[id*=DELETE_CATEGORY_]",
        kitchen_module.DeleteCategoryData
    );
});
