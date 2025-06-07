$(function(){
    acategories_module.DisplayListACategories();
    $("#generalSearch").on('keyup', acategories_module.DisplayListACategories);

    $('.LstCategoriesGrid').on('click',"a[id*=EDIT_CATEGORY_]",acategories_module.EditACategoryInfo);
    $('.LstCategoriesGrid').on('click',"a[id*=DELETE_CATEGORY_]",acategories_module.DeleteACategoriesData);
})
