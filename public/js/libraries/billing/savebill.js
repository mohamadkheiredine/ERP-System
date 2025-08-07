$(function(){
	$("#BTN_SAVE_BILLS").on("click",bills_module.SaveBillInfo);
	$("#IP_CLIENT_CODE").on("change",bills_module.getclientinfo);

    new tempusDominus.TempusDominus(document.getElementById('IP_BILLING_DATE'),{
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
    new tempusDominus.TempusDominus(document.getElementById('IP_PAY_DATE'),{
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
