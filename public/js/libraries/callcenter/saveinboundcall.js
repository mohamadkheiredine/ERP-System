$(function(){
	ClassicEditor
    .create( document.querySelector( '#IC_CALL_OUTCOME' ) )
    .then( newEditor => {
       $.outcome_desc = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
    ClassicEditor
    .create( document.querySelector( '#IC_NOTES' ) )
    .then( newEditor => {
       $.notes_desc = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
    ClassicEditor
    .create( document.querySelector( '#IC_ITEM_PROBLEM' ) )
    .then( newEditor => {
       $.itemprob_desc = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );

    if($('#IC_CALL_DATE').length > 0)
    {
        new tempusDominus.TempusDominus(document.getElementById('IC_CALL_DATE'),{
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
    }

    if($('#IC_WARRANTY_EXPIRY').length > 0) {
        new tempusDominus.TempusDominus(document.getElementById('IC_WARRANTY_EXPIRY'), {
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
                format: "yyyy-MM-dd"

            }
        });
    }
    if($('#IC_CALL_START_TIME').length > 0) {
        new tempusDominus.TempusDominus(document.getElementById('IC_CALL_START_TIME'),{
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
    }





	$('#BTN_SAVE_CALL').on('click',inboundcalls_module.SaveInboundCallInfo);
    $("#IC_CLIENT_ID").on('change',inboundcalls_module.getAccountInfo);
    $("#IC_CLIENT_CODE").on('keyup',inboundcalls_module.getAccountDealInfo);
    $("#IC_CLIENT_CODE").on('blur',inboundcalls_module.getAccountDealInfo);
    $("#IC_CONTRACT_CODE").on('blur',inboundcalls_module.getDealInfo);
    $("#IC_CONTRACT_CODE").on('keyup',inboundcalls_module.getDealInfo);
    inboundcalls_module.getAccountDealInfo();
    inboundcalls_module.getDealInfo();

    if($("#IC_CLIENT_ID").length > 0)
    {
        inboundcalls_module.getAccountInfo();
    }

});
