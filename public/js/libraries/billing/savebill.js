$(function(){
	$("#BTN_SAVE_BILLS").on("click",bills_module.SaveBillInfo);
	$("#IP_CLIENT_CODE").on("change",bills_module.getclientinfo);
	$("#IP_PAYMENT_AMOUNT").on("keyup",bills_module.CalculateRemainingAmount);
	//$("input[ip_billing_status]").on("change",bills_module.ValidateBillPaymentToPay);
    $('#IP_BILLING_STATUS').on('change',bills_module.ValidateBillPaymentToPay);

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
