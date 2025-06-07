$(function(){
    ClassicEditor
        .create( document.querySelector( '#AA_ASSET_DESCRIPTION' ) )
        .then( newEditor => {
            $.il_desc = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );


    new tempusDominus.TempusDominus(document.getElementById('AA_PURCHASE_DATE'),{
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

    $('#BTN_SAVE_ASSET').on('click',assets_module.SaveAssetInfo);
});
