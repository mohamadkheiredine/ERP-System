$(function(){
    salarydetails_module.DisplayListSalaryDetails();
    $("input[name=general_search]").on("keyup",salarydetails_module.DisplayListSalaryDetails);
    $("select").on("change",salarydetails_module.DisplayListSalaryDetails);

    $(".LstSallaryDetails").on("click","a[id*=EDIT_SLRDETAILS_]",salarydetails_module.EditPayRollDetailsInfo);
    $(".LstSallaryDetails").on("click","a[id*=DELETE_SLRDETAILS_]",salarydetails_module.DeleteSalDetailsData);

});
