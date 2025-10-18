$(function(){
    callapt_module.DisplayListCallbackLeads();
    $('.DownloadLeads').on('click',callapt_module.DownloadListCallbackLeadsReports);
    $('input[type=text]').on('keyup',callapt_module.DisplayListCallbackLeads);
    $('input#CL_DATE').on('change',callapt_module.DisplayListCallbackLeads);
    $('select').on('change',callapt_module.DisplayListCallbackLeads);

    new tempusDominus.TempusDominus(document.getElementById('CL_DATE'),{
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
})
