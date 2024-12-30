$(function(){
    new tempusDominus.TempusDominus(document.getElementById('CA_APPOINTMENT_DATE'),{
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
    callapt_module.DisplayListTodaysAppt();
    $('#CA_APPOINTMENT_DATE').on('change',callapt_module.DisplayListTodaysAppt);
    $('.DownloadAppointment').on('click',callapt_module.DownloadlistAppointmentsReport);
})