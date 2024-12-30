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
                    format : "HH:mm:ss"

            }
   });
   
   
   
   $(function(){
         callapt_module.DisplayListAppointments();
         let sales_id = $('input[name=sales_id]').val();
         let telemarketing_id = $('input[name=telemarketing_id]').val();
         let lead_type_id = $('input[name=lead_type_id]').val();
         $("#CL_SALES_ID").val(sales_id);
         $("#CL_SALES_ID").trigger('change');
         $("#CL_TELEMARKETING_ID").val(telemarketing_id);
         $("#CL_TELEMARKETING_ID").trigger('change');
         $("#CL_LEAD_TYPE").val(lead_type_id); 
         $("#CL_LEAD_TYPE").trigger('change');
         $("#CA_APT_RESULT").val(0);
         $("#CA_APT_RESULT").trigger('change');
         $('button[name=btn_save_app]').on('click',callapt_module.SaveLeadAppointmentInfo);
         $('#LstLeadAppts').on('click','a[id*=DELETE_APP_]',callapt_module.DeleteLeadAppointment);
   });