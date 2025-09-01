$(function(){
    deals_module.GenerateContractPayment();
    $(".DownloadContract").on('click',deals_module.GenerateAndDownloadContract);
})
