$(function(){
    new tempusDominus.TempusDominus(document.getElementById('CA_APT_DATE'),{
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

     new tempusDominus.TempusDominus(document.getElementById('CA_APT_TIME'),{
            display: {
                     components: {
                         calendar: false,
                         date: false,
                         month: false,
                         year: false,
                         decades: false,
                         clock: true,
                         hours: true,
                         minutes: true,
                         seconds: true,
                         useTwentyfourHour: undefined
                       }
            },
            localization: {
                    format : "HH:mm"

            }
   });
     new tempusDominus.TempusDominus(document.getElementById('LD_APT_DATE'),{
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

    new tempusDominus.TempusDominus(document.getElementById('LD_FROM_APT_DATE'),{
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

    new tempusDominus.TempusDominus(document.getElementById('LD_TO_APT_DATE'),{
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

 callapt_module.DisplayListAllAppointments();
 $('button[name=btn_save_app]').on('click',callapt_module.SaveLeadAppointmentInfo);
 $('#LstLeadAppts').on('click','a[id*=DELETE_APP_]',callapt_module.DeleteLeadAppointment);
 $('#LstLeadAppts').on('click','a[id*=EDIT_APP_]',callapt_module.GetLeadAppointmentInformation);
 $('select[name=ca_salesman_id]').on('change', callapt_module.DisplayListAllAppointments);
 $('select[name=ap_apt_result]').on('change', callapt_module.DisplayListAllAppointments);
 $('select[name=lead_id]').on('change', callapt_module.GetLeadInformation);
 $('button[name=btn_search]').on('click', callapt_module.DisplayListAllAppointments);
 $('.dropdown-item').on('click', callapt_module.QuickActionAppointments);
  $('input[name=ld_apt_date]').on('change',function(){
      callapt_module.DisplayListAllAppointments();
  });
   $('input[name=ld_from_apt_date]').on('change',function(){
      callapt_module.DisplayListAllAppointments();
  });
  $('input[name=ld_to_apt_date]').on('change',function(){
      callapt_module.DisplayListAllAppointments();
  });
    $('.AptJob').css({display : "none"});
});
