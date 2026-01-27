$(function(){
    $('#BTN_SAVE_JOB').on('click',projectjobs_module.SaveProjectJobInfo);
    if($('input[name=pj_id]').length == 0)
    {
        $('#FK_PROJECT_ID').on('change',projectjobs_module.GenerateJobCode);
    }

    new tempusDominus.TempusDominus(document.getElementById('PJ_PLANNED_START'),{
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

    new tempusDominus.TempusDominus(document.getElementById('PJ_PLANNED_END'),{
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

    new tempusDominus.TempusDominus(document.getElementById('PJ_ACTUAL_START'),{
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

    new tempusDominus.TempusDominus(document.getElementById('PJ_ACTUAL_END'),{
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
        .create( document.querySelector( '#PJ_DESCRIPTION' ) )
        .then( newEditor => {
            $.description_editor = newEditor;
        }).catch( error => {
        console.error( error );
    });

})
