$(function(){
    dedben_module.DisplayListDedBen();
    $("input[name=general_search]").on("keyup",dedben_module.DisplayListDedBen);
    $("select[name=db_company_id]").on("change",dedben_module.DisplayListDedBen);
    $("select[name=db_currency_id]").on("change",dedben_module.DisplayListDedBen);

    $(".LstDedBenBody").on("click","a[id*=EDIT_DEDBEN_]",dedben_module.EditDedBenInfo);
    $(".LstDedBenBody").on("click","a[id*=DELETE_DEDBEN_]",dedben_module.DeleteDedBenData);

});
