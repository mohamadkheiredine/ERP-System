$(function(){
    callapt_module.DisplayListCumulativeLeads();
    $('input[type=text]').on('keyup',callapt_module.DisplayListCumulativeLeads);
    $('input#CA_FROM_DATE').on('change',callapt_module.DisplayListCumulativeLeads);
    $('input#CA_LAST_DATE').on('change',callapt_module.DisplayListCumulativeLeads);

    new tempusDominus.TempusDominus(document.getElementById('CA_FROM_DATE'),{
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
    new tempusDominus.TempusDominus(document.getElementById('CA_LAST_DATE'),{
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
