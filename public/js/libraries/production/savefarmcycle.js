/**
 *
 */
$(function(){
	ClassicEditor
    .create( document.querySelector( '#FC_NOTES' ) )
    .then( newEditor => {
       $.fc_editor = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
    new tempusDominus.TempusDominus(document.getElementById('FC_START_DATE'),{
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
    new tempusDominus.TempusDominus(document.getElementById('FC_END_DATE'),{
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
	$("#BTN_SAVE_CYCLE").on('click',farmcycles_module.SaveFarmCycleInfo);
	$("#BTN_ADD_EXPENSES").on('click',farmcycles_module.OpenCycleExpensesPopup);

    farmcycles_module.DisplayListExpenses();
})
