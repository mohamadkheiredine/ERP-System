$(function(){
    ClassicEditor
        .create( document.querySelector( '#DB_DESCRIPTION' ) )
        .then( newEditor => {
            $.editor = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );


    new tempusDominus.TempusDominus(document.getElementById('DB_EFFECTIVE_DATE'),{
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

    new tempusDominus.TempusDominus(document.getElementById('DB_END_DATE'),{
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

    $("#BTN_SAVE_DEDBEN").on("click",dedben_module.SaveDedBenInfo);

})
