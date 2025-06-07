$(function(){
    alocations_module.DisplayListAlocations();
    $("#generalSearch").on('keyup', alocations_module.DisplayListAlocations);

    $('.LstCategoriesGrid').on('click',"a[id*=EDIT_CATEGORY_]",alocations_module.EditALocationInfo);
    $('.LstCategoriesGrid').on('click',"a[id*=DELETE_CATEGORY_]",alocations_module.DeleteALocationsData);
})
