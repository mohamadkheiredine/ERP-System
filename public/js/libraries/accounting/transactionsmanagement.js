/**
 *
 */

$(function(){
	$('select').select2();
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
	 transactions_module.DisplayListLedger();
	transactions_module.DisplayListEmptyTransactions();
	$('select').on('change',transactions_module.DisplayListLedger);
})
