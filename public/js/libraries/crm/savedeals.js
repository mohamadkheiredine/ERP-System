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
     	 new tempusDominus.TempusDominus(document.getElementById('AD_WARRANTY_DATE'),{
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
     	 new tempusDominus.TempusDominus(document.getElementById('AD_DEAL_DATE'),{
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

        let ad_id = $('input[name=ad_id]').val();

        if(ad_id != null)
        {
            deals_module.GenerateContractPayment();
        }


	 $(".DownloadContract").on('click',deals_module.GenerateAndDownloadContract);
	 $("#BTN_SAVE_DEALS").on('click',deals_module.SaveDealsInfo);
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
