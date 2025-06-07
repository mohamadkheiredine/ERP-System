$(function(){
    atransfer_module.DisplayListTransfers();
    $("#generalSearch").on('keyup', atransfer_module.DisplayListTransfers);
    $("select").on('change', atransfer_module.DisplayListTransfers);

    $('.LstAssetTransferGrid').on('click',"a[id*=EDIT_TRANSFER_]",atransfer_module.EditATransferInfo);
    $('.LstAssetTransferGrid').on('click',"a[id*=DELETE_TRANSFER_]",atransfer_module.DeleteATransferData);
})
