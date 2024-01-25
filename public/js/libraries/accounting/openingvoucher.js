/**
 * 
 */

$(function(){ 
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
	var first_day = fisical_year + "-01-01";

	 new tempusDominus.TempusDominus(document.getElementById('AT_TRANSACTION_DATE'),{
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
		 }
	});
	 
	$('select').select2();
	var at_id = $("#AT_ID").val();
	if(at_id != null)
	{
		
		
	}
	accounting_module.DisplayOpeningJournal();
	$("#BTN_NEW_MOVEMENT").on('click',accounting_module.AddNewTranMovementRow);
	$("#BTN_SAVE_TRANSACTION").on('click',accounting_module.SaveOpeningVoucherInfo);
	
})

function getCookie(cookieName) {
    const name = cookieName + '=';
    const decodedCookie = decodeURIComponent(document.cookie);
    const cookieArray = decodedCookie.split(';');

    for (let i = 0; i < cookieArray.length; i++) {
        let cookie = cookieArray[i].trim();
        if (cookie.indexOf(name) === 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }

    return null; // Return null if the cookie is not found
}
