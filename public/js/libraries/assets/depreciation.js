$(function(){
    adepreciation_module.DisplayListDepreciations();
    $("#generalSearch").on('keyup', adepreciation_module.DisplayListDepreciations);
    $("select").on('change', adepreciation_module.DisplayListDepreciations);

    $('.LstDepreciationGrid').on('click',"a[id*=EDIT_DEPR_]",adepreciation_module.EditADepreciationInfo);
    $('.LstDepreciationGrid').on('click',"a[id*=DELETE_DEPR_]",adepreciation_module.DeleteADepreciationData);
})
