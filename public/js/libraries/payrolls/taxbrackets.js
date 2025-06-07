$(function(){
    brackets_module.DisplayListBrackets();
    $("input[name=general_search]").on("keyup",brackets_module.DisplayListBrackets);
    $("select[name=tb_company_id]").on("change",brackets_module.DisplayListBrackets);

    $(".LstBracketsBody").on("click","a[id*=EDIT_BRACKET_]",brackets_module.EditBracketInfo);
    $(".LstBracketsBody").on("click","a[id*=DELETE_BRACKET_]",brackets_module.DeleteBracketData);

});
