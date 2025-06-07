$(function(){
    assets_module.DisplayListAssets();
    $("#generalSearch").on('keyup', assets_module.DisplayListAssets);
    $("select").on('change', assets_module.DisplayListAssets);

    $('.LstAssetsGrid').on('click',"a[id*=EDIT_ASSET_]",assets_module.EditAssetInfo);
    $('.LstAssetsGrid').on('click',"a[id*=DELETE_ASSET_]",assets_module.DeleteAssetsData);
})
