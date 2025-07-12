$(function(){
    appresult_module.DisplayListAppResults();
    $("#generalSearch").on('keyup',appresult_module.DisplayListAppResults);
    $("select").on('change',appresult_module.DisplayListAppResults);

    $('#LstAppResults').on('click',"a[id*=EDIT_RESULT_]",appresult_module.EditAppResultInfo);
    $('#LstAppResults').on('click',"a[id*=DELETE_RESULT_]",appresult_module.DeleteAppResultData);
})
