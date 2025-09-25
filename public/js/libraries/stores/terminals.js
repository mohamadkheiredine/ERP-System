$(function(){
    terminals_module.DisplayListTerminals();
    $('select[name=pt_store_id]').on('change',terminals_module.DisplayListTerminals);


    $("#LstStoreTerminalsGrid").on('click',  "a[id*=EDIT_TERMINAL_]",terminals_module.EditTerminalInfo);
    $("#LstStoreTerminalsGrid").on('click',"a[id*=DELETE_TERMINAL_]",terminals_module.DeleteTerminalData);
})
