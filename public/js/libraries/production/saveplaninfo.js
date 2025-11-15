/**
 *
 */
$(function(){
	ClassicEditor
    .create( document.querySelector( '#PP_PLAN_DESCRIPTION' ) )
    .then( newEditor => {
       $.pp_editor = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const PrepareDate = new tempusDominus.TempusDominus(document.getElementById('PP_PREPARE_DATE'),{
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

        },
        restrictions: {
            minDate : tomorrow
        }
    });

    const EndDate = new tempusDominus.TempusDominus(document.getElementById('PP_END_DATE'),{
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

    document.getElementById('PP_PREPARE_DATE').addEventListener('change.td', (e) => {
        PrepareDate.updateOptions({
            restrictions: {
                minDate: e.detail.date
            }
        });
    });


    const DStartDate = new tempusDominus.TempusDominus(document.getElementById('PP_START_DATE'),{
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

        },
        restrictions: {
            minDate : tomorrow
        }
    });


    const DFinishDate = new tempusDominus.TempusDominus(document.getElementById('PP_FINISH_DATE'),{
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

    if($("#PP_FINISH_DATE").length > 0)
    {
        document.getElementById('PP_FINISH_DATE').addEventListener('change.td', (e) => {
            PrepareDate.updateOptions({
                restrictions: {
                    minDate: e.detail.date
                }
            });
        });
    }

    if($("#PP_ESTIMATION_TIME").length > 0)
    {
        new tempusDominus.TempusDominus(document.getElementById('PP_ESTIMATION_TIME'),{
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
                    useTwentyfourHour: true
                }
            },
            localization: {
                format : "HH:mm:ss"

            }
        });
    }



	$("#BTN_SAVE_PLAN").on('click',plans_module.SavePlanInformation);
	$('select').select2();

	//check if edit mode
	let pp_id = $('input[name=pp_id]').val();
	if(pp_id != null)
	{
		plans_module.DisplayListPlanProducts();
		plans_module.DisplayListQualityCheck();
		$("#LstPlanProducts").on('click',"a[id*=DELETE_ITEM_]",plans_module.DeleteProductPlan);
		$("#BTN_ADD_PRODUCT").on('click',plans_module.OpenAddProductPopUp);
		$("#BTN_INSERT_ITEM").on('click',plans_module.SavePlanItem);
		$("#PlanApproval").on('click',plans_module.OpenProductionPlanApproval);
		$("#AssignToUser").on('click',plans_module.OpenProductionPlanAssignTo);
		$("#BTN_ASSIGN_USER").on('click',plans_module.SaveAssignPlanTo);
		$("#BTN_APPROVAL_USER").on('click',plans_module.SavePlanApproval);
		$("#BTN_START_PRODUCTION").on('click',plans_module.StartProductionPlan);
		$("#BTN_PAUSE_PRODUCTION").on('click',plans_module.PauseProductionPlan);
		$("#BTN_BLOCK_PRODUCTION").on('click',plans_module.BlockProductionPlan);
		$("#BTN_QUALITY_CHECK").on('click',plans_module.PlanQualityCheck);
		$("#BTN_SAVE_CHECK").on('click',plans_module.SaveQualityCheck);
		$("#LstQualityCheck").on('click',"a[id*=EDIT_CHECK_]",plans_module.OpenQualityCheckProduction);
		window.onbeforeunload = function (event) {
			if($('input[name=production_run]').val() == '1')
			{
				var message = 'Important: Please click on \'Save\' button to leave this page.';
			    if (typeof event == 'undefined') {
			        event = window.event;
			    }
			    if (event) {
			        event.returnValue = message;
			    }
			    return message;
			}
		};



	}
});
