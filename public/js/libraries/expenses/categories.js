$(function(){
    expensecategories_module.displayListExpenseCategories();
    $("input[name=general_search]").on("keyup",expensecategories_module.displayListExpenseCategories);

    $(".LstExpCategoriesGrid").on("click","a[id*=EDIT_CATEGORY_]",expensecategories_module.EditExpenCategoryForm);
    $(".LstExpCategoriesGrid").on("click","a[id*=DELETE_CATEGORY_]",expensecategories_module.DeleteExpensesCategory);

});
