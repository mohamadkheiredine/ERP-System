$(function(){
    var fisical_year = getCookie('fisical_year');
    $('input[name=fisical_year]').val(fisical_year);

    invoices_module.DisplayListReturnInvoices();

    $('select').on("change",function(){
        $('input[name=page_number]').val(1);
        if( $.pagination != null)
            $.pagination.twbsPagination('destroy');
        invoices_module.DisplayListReturnInvoices();
    });
    $('input[name=start_date]').on("change",function(){
        $('input[name=page_number]').val(1);
        if( $.pagination != null)
            $.pagination.twbsPagination('destroy');
        invoices_module.DisplayListReturnInvoices();
    });
    $('input[name=end_date]').on("change",function(){
        $('input[name=page_number]').val(1);
        if( $.pagination != null)
            $.pagination.twbsPagination('destroy');
        invoices_module.DisplayListReturnInvoices();
    });
    $('input[name=general_search]').on("keyup",function(){
        $('input[name=page_number]').val(1);
        if( $.pagination != null)
            $.pagination.twbsPagination('destroy');
        invoices_module.DisplayListReturnInvoices();
    });

    new tempusDominus.TempusDominus(document.getElementById('START_DATE'),{
        display: {
            components: {
                calendar: true,
                date: true,
                month: true,
                year: true,
                decades: true,
                clock: false,
                hours: false,
                minutes: false,
                seconds: false,
                useTwentyfourHour: undefined
            }
        },
        localization: {
            format : "yyyy-MM-dd"

        }
    });


    new tempusDominus.TempusDominus(document.getElementById('END_DATE'),{
        display: {
            components: {
                calendar: true,
                date: true,
                month: true,
                year: true,
                decades: true,
                clock: false,
                hours: false,
                minutes: false,
                seconds: false,
                useTwentyfourHour: undefined
            }
        },
        localization: {
            format : "yyyy-MM-dd"

        }
    });
});
