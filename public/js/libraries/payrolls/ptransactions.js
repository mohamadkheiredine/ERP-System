$(function(){
    ptransactions_module.DisplayListPayRollTransactions();
    $('select').on('change',ptransactions_module.DisplayListPayRollTransactions);
    $('.LstPayRollTransactions').on('click','a[id*=PAY_TRANSACTION_]',ptransactions_module.PayPayRollTransaction)
})
