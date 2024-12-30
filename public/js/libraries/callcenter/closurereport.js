$(function(){
    
    new tempusDominus.TempusDominus(document.getElementById('CA_APT_FROM_DATE'),{
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


   new tempusDominus.TempusDominus(document.getElementById('CA_APT_LAST_DATE'),{
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
    
    callapt_module.DisplayClosureSalesmanApp();
    $('.QuickAction').on('click',callapt_module.AppQuickAction);
    $('select[name=cl_sales_id]').on('change',callapt_module.DisplayClosureSalesmanApp);
    $('input[name=ca_apt_from_date]').on('change',callapt_module.DisplayClosureSalesmanApp);
    $('input[name=ca_apt_last_date]').on('change',callapt_module.DisplayClosureSalesmanApp);
})