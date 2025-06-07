$(function(){
    periods_module.DisplayListPeriods();
    $("input[name=general_search]").on("keyup",periods_module.DisplayListPeriods);

    $(".LstPayrollPeriods").on("click","a[id*=EDIT_PERIOD_]",periods_module.EditDedBenInfo);
    $(".LstPayrollPeriods").on("click","a[id*=DELETE_PERIOD_]",periods_module.DeleteDedBenData);

});
