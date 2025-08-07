$(function(){
	bills_module.DisplayListBills();
	$("#generalSearch").on('keyup',bills_module.DisplayListBills);
	$("select").on('change',bills_module.DisplayListBills);
	$("input").on('change',bills_module.DisplayListBills);

	$('#LstBills').on('click',"a[id*=EDIT_IP_]",bills_module.EditBillInfo);
	$('#LstBills').on('click',"a[id*=DELETE_IP_]",bills_module.DeleteBillsData);


       new tempusDominus.TempusDominus(document.getElementById('PI_START_DATE'),{
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

    new tempusDominus.TempusDominus(document.getElementById('PI_UPTO_DATE'),{
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


            new tempusDominus.TempusDominus(document.getElementById('PI_END_DATE'),{
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
})
