$(function (){
    $(".BillPaid").on('dblclick',function(){
        let ip_id = $(this).data('ip_id');
        let base_url = $('#BASE_URL').val();
        //
        let url = base_url + "/billing/bills/editform/" + ip_id;
        let win = window.open(url,true);
        win.focus();

    })
})
