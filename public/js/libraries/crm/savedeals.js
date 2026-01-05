/**
 *
 */
$(function(){
	$.desc_editor;
	$.nextstep_editor;
	 ClassicEditor
     .create( document.querySelector( '#AD_DEAL_DESCRIPTION' ) )
     .then( newEditor => {
        $.desc_editor = newEditor;
    }).catch( error => {
         console.error( error );
    });

	 ClassicEditor
     .create( document.querySelector( '#AD_NEXT_STEP' ) )
     .then( newEditor => {
        $.nextstep_editor = newEditor;
    }).catch( error => {
         console.error( error );
     });
     	 new tempusDominus.TempusDominus(document.getElementById('AD_FIRST_BILL_DATE'),{
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
    /* ================================
  WARRANTY DATE PICKER
================================ */
    const warrantyElement = document.getElementById('AD_WARRANTY_DATE');
    const warrantyPicker = new tempusDominus.TempusDominus(warrantyElement, {
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
                seconds: false
            }
        },
        localization: {
            format: "yyyy-MM-dd"
        }
    });

    /* ================================
       DEAL DATE PICKER
    ================================ */
    const dealElement = document.getElementById('AD_DEAL_DATE');
    const dealPicker = new tempusDominus.TempusDominus(dealElement, {
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
                seconds: false
            }
        },
        localization: {
            format: "yyyy-MM-dd"
        }
    });

    /* ================================
       ON DEAL DATE CHANGE
    ================================ */
    dealElement.addEventListener('change.td', function (event) {
        const dealDate = event.detail.date;
        if (!dealDate) return;

        // 👉 Clone date to avoid mutation
        const warrantyDate = new Date(dealDate);

        // 👉 Business rule (change if needed)
        warrantyDate.setFullYear(warrantyDate.getFullYear() + 1);

        // 👉 Update Warranty Picker
        warrantyPicker.dates.setValue(warrantyDate);

        console.log('Deal Date:', dealDate.toISOString().split('T')[0]);
        console.log('Warranty Date:', warrantyDate.toISOString().split('T')[0]);
    });




    let ad_id = $('input[name=ad_id]').val();

        if(ad_id != null)
        {
            deals_module.GenerateContractPayment();
            var contract_type = $("#AD_CONTRACT_TYPE").val();
            if(contract_type == 1)
            {
                $('.DownPaymentHolder').css({display : "none"});
                $('.NumberofPaymentHolder').css({display : "none"});
                $('.RemainingPaymentHolder').css({display : "none"});
                $('#AD_NBR_OF_PAYMENT').val(1);
                $('.LabelBill').html("Date of Payment <span class='required'> * </span>");
            }
            else
            {
                $('.DownPaymentHolder').css({display : ""});
                $('.NumberofPaymentHolder').css({display : ""});
                $('.RemainingPaymentHolder').css({display : ""});
                $('.LabelBill').html("First Bill Date <span class='required'> * </span>")
            }
        }





	 $(".DownloadContract").on('click',deals_module.GenerateAndDownloadContract);
	 $("#BTN_SAVE_DEALS").on('click',deals_module.SaveDealsInfo);
	 // $("#AD_DEAL_DATE").on('change',function(){
     //    $("#AD_WARRANTY_DATE").val($("#AD_DEAL_DATE").val());
     // });
	 $("#BTN_SAVE_CONTINUE_DEALS").on('click',deals_module.SaveAndContinueDealsInfo);
	 $("#AD_DEAL_AMOUNT").on('keyup',deals_module.CalculateRemainingAmount);
	 $("#AD_DOWN_PAYMENT").on('keyup',deals_module.CalculateRemainingAmount);
	 $("#AD_ACCOUNT_CODE").on('blur',deals_module.getAccountDealInfo);
	 $("#AD_CONTRACT_TYPE").on('change',deals_module.ChangeContractType);
	 $("#BTN_GENERATE_PAYMENTS").on('click',deals_module.GenerateContractPayment);
	 $("button[name=btn_assign_product_deal]").on('click',deals_module.AssignProductDeal);
	 $("#BTN_ADD_PRODUCT").on('click',function() {
             $('#ProductsDealModel').modal('toggle');
         });
})
