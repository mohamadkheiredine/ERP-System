$(function(){
	 ClassicEditor
     .create( document.querySelector( '#RI_RECURRING_DESCRIPTION' ) )
     .then( newEditor => {
        $.rec_desc = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
        new tempusDominus.TempusDominus(document.getElementById('RI_NEXT_INVOICE_DATE'),{
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
	 new tempusDominus.TempusDominus(document.getElementById('RI_END_DATE'),{
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
         new tempusDominus.TempusDominus(document.getElementById('RI_LAST_GENERATED_DATE'),{
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
	$("#BTN_SAVE_RECINVOICE").on("click",recinvoices_module.SaveRecInvoiceInfo);
})