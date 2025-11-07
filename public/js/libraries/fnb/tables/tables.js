$(function () {
    tables_module.DisplayListTables();
    $("select[name=fl_id]").on(
        "change",
        tables_module.DisplayListTables
    );

    $("input[name=general_search]").on('keyup', tables_module.DisplayListTables);
    $("button[name=back_form]").on('click', tables_module.backToPreviousPage);

    $("#LstTablesGrid").on(
        "click",
        "a[id*=EDIT_TABLE_]",
        tables_module.EditTableInfo
    );
    $("#LstTablesGrid").on(
        "click",
        "a[id*=DELETE_TABLE_]",
        tables_module.DeleteTableData
    );
});
