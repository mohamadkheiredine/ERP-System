$(function(){
    ClassicEditor
        .create( document.querySelector( '#AC_DESCRIPTION' ) )
        .then( newEditor => {
            $.category_desc = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );
    new tempusDominus.TempusDominus(document.getElementById('AC_EXPENSE_DATE'),{
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
    $('#BTN_SAVE_EXPENSE').on('click',expenses_module.SaveExpensesInfo);
});
