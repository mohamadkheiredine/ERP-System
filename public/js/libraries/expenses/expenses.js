$(function(){
    expenses_module.displayListExpenses();
    $("input[name=general_search]").on("keyup", expenses_module.displayListExpenses);
    $("select[name=ac_category_id]").on("keyup", expenses_module.displayListExpenses);
    $("select[name=ac_employee_id]").on("keyup", expenses_module.displayListExpenses);

    $(".LstExpCategoriesGrid").on("click","a[id*=EDIT_EXPENSE_]",expenses_module.EditExpensesForm);
    $(".LstExpCategoriesGrid").on("click","a[id*=DELETE_EXPENSE_]",expenses_module.DeleteExpensesInfo);

});
