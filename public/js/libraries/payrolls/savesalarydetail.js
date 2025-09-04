$(function(){
    new tempusDominus.TempusDominus(document.getElementById('PD_EFFECTIVE_DATE'),{
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

    new tempusDominus.TempusDominus(document.getElementById('PD_END_DATE'),{
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

    ClassicEditor
        .create( document.querySelector( '#PD_DESCRIPTION' ) )
        .then( newEditor => {
            $.account_editor = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );
    $('select').select2();

    $("#PD_USER_ID").on("change",salarydetails_module.getEmployeeInfo);
    $("#BTN_SAVE_SALDETAILS").on("click",salarydetails_module.SaveSalDetailsInfo);
    $("#BTN_GENERATE_TRANSACTION").on("click",salarydetails_module.GeneratePayRollTransaction);

    let pd_id = $('input[name=pd_id]').val();
    if(pd_id != undefined)
    {
        salarydetails_module.getEmployeeInfo();
    }

})
