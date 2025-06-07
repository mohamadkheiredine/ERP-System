$(function(){
    ClassicEditor
        .create( document.querySelector( '#AT_TRANSFER_REASON' ) )
        .then( newEditor => {
            $.reason_desc = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );

    ClassicEditor
        .create( document.querySelector( '#AT_REMARKS' ) )
        .then( newEditor => {
            $.remark_desc = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );

    new tempusDominus.TempusDominus(document.getElementById('AT_TRANSFER_DATE'),{
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

    $('#BTN_SAVE_TRANSFER').on('click',atransfer_module.SaveATransferInfo);
});
