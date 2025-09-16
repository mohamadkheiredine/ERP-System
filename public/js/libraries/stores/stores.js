$(function(){
    stores_module.DisplayListStores();
    $('select[name=ps_company_id]').on('change',stores_module.DisplayListStores);


    $("#LstStoresGrid").on('click',  "a[id*=EDIT_STORE_]",stores_module.EditStoreInfo);
    $("#LstStoresGrid").on('click',"a[id*=DELETE_STORE_]",stores_module.DeleteStoreData);
})
