$(function (){
    ClassicEditor
        .create( document.querySelector( '#PP_NOTES' ) )
        .then( newEditor => {
            $.notes_editor = newEditor;
        }).catch( error => {
        console.error( error );
    });

    ClassicEditor
        .create( document.querySelector( '#PP_DESCRIPTION' ) )
        .then( newEditor => {
            $.description_editor = newEditor;
        }).catch( error => {
        console.error( error );
    });

    new tempusDominus.TempusDominus(document.getElementById('PP_START_DATE'),{
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

    new tempusDominus.TempusDominus(document.getElementById('PP_END_DATE'),{
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
    new tempusDominus.TempusDominus(document.getElementById('PP_ESTIMATED_END_DATE'),{
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

    $('button[name=btn_save_project]').on('click',projects_module.SaveProjectInfo);

    if($("input[name=pp_id]").length > 0)
    {
        projects_module.DisplayProjectTeams();
        projects_module.DisplayProjectTasks();
        projects_module.DisplayProjectJobs();
        projects_module.DisplayProjectPhases();
        $("#BTN_LINK_TEAM").on('click',projects_module.SubmitProjectTeamInfo);
    }
});
