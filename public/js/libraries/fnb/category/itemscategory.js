$(function() {
    fnb_itemscategory_module.DisplayListCategoryItems();

    // Attach event listeners
    $("input[name=general_search]").on('keyup', fnb_itemscategory_module.DisplayListCategoryItems);
    $("select[name=fi_company_name]").on('change', fnb_itemscategory_module.DisplayListCategoryItems);
    $("select[name=fi_kitchen_name]").on('change', fnb_itemscategory_module.DisplayListCategoryItems);

    $("a[id*=EDIT_PRODUCT_]").on("click", fnb_itemscategory_module.SaveItemInfo);
});
