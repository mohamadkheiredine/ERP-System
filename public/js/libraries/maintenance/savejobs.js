/**
 * 
 */
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#J_JOB_DESCRIPTION' ) )
     .then( newEditor => {
        $.order_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } ); 
	 
	 new tempusDominus.TempusDominus(document.getElementById('J_DUE_DATE'),{
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
			 format : "L"
			 
		 }
	});
	$('#BTN_SAVE_JOB').on('click',jobs_module.SaveJobInfo);
	$('select').select2();
	
	jobs_module.DisplayJobItems();
	$("#BTN_ADD_ITEM").on('click',jobs_module.OpenItemsPopup)
	$("#BTN_INSERT_ITEM").on('click',jobs_module.InsertJobItem)
	$("#MJ_JOB_MAINTENANCE").on('change',jobs_module.DisplayItemsDropdown);
	$("#ADD_NEW_CUSTOMER").on('click',jobs_module.OpenNewCustomer);
	$("#ADD_NEW_VENDOR").on('click',jobs_module.OpenNewVendor);
	$("#BTN_SAVE_CUSTOMER").on('click',jobs_module.SaveCustomerInfo);
	$("#BTN_SAVE_VENDOR").on('click',jobs_module.SaveVendorInfo);
	$("#BTN_PAY_ORDER").on('click',jobs_module.PayMaintenanceOrder);
	$(".ItemsDropdown").on("click","select[name=jm_service_items]",jobs_module.GetPriceResult);
	$(".LstJobItems").on("click","a[id*=DELECT_RECORD_]",jobs_module.DeleteItemRecord);
	//$('.ItemsDropdown').on('change','',jobs_module.DisplayP)
	
	$('#MJ_ITEM_BARECODE').scannerDetection({
		timeBeforeScanTest: 200, // wait for the next character for upto 200ms
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
		preventDefault: false,
		
		endChar: [13],
		onComplete: function(barcode, qty){
			validScan = true;
			$('#MJ_ITEM_BARECODE').val('');	    	
			$('#MJ_ITEM_BARECODE').val(barcode);	    	
			
		},
		onError: function(string, qty){ 
			
		}
	});
	
	
	$('#J_PRODUCT_SERIAL_NUMBER').scannerDetection({
		timeBeforeScanTest: 200, // wait for the next character for upto 200ms
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
		preventDefault: true,

		endChar: [13],
		onComplete: function(barcode, qty){
	   		validScan = true;
	   		$('#J_PRODUCT_SERIAL_NUMBER').val(barcode);
	   		let _token = $('input[name=_token]').val();
	   		var base_url = $('#BASE_URL').val();
	   		$.ajax
		    ({
		        url : base_url + "/request/jobs/getproductlog",
		        data : { _token : _token , barcode : barcode },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('.ProductLog').html(response.display);
		        }
		    });
	   		
	   		
		
		},
		onError: function(string, qty){ 
		
		}
	});
	
	$('#IV_AVATAR_PIC').on('change', function () {
        var countFiles   = $(this)[0].files.length;
        var imgPath      = $(this)[0].value;
        var extn         = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        var image_holder = $(".ListFiles");
        image_holder.empty();

        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
            if (typeof (FileReader) != "undefined") {
                //loop for each file selected for uploaded.
                for (var i = 0; i < countFiles; i++)
                {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var base_url = $('#BASE_URL').val();

                        $("#VENDOR_LOGO_PIC").attr('src', e.target.result);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[i]);
                }

            }
        }
    });
	
	$('#IC_AVATAR_PIC').on('change', function () {
        var countFiles   = $(this)[0].files.length;
        var imgPath      = $(this)[0].value;
        var extn         = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        var image_holder = $(".ListFiles");
        image_holder.empty();

        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
            if (typeof (FileReader) != "undefined") {
                //loop for each file selected for uploaded.
                for (var i = 0; i < countFiles; i++)
                {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var base_url = $('#BASE_URL').val();

                        $("#CUSTOMER_LOGO_PIC").attr('src', e.target.result);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[i]);
                }

            }
        }
    });
	
	let barcode = $('#J_PRODUCT_SERIAL_NUMBER').val();
	if( barcode.length > 0 )
	{
		let _token = $('input[name=_token]').val();
		var base_url = $('#BASE_URL').val();
		$.ajax
	    ({
	        url : base_url + "/request/jobs/getproductlog",
	        data : { _token : _token , barcode : barcode },
	        method : 'post',
	        dataType : "json",
	        beforeSend : function(){
	        },
	        success : function(response){
	        	$('.ProductLog').html(response.display);
	        }
	    });
	}
});

window.onbeforeunload = function()
{ 

  let job_ids = $("#LST_JOB_ITEMS").val();
  if(job_ids.length > 0)
  {
	  var r = confirm("Are you sure you want to reload the page ?");
	  if(r)
	  {
	    window.location.reload();
	  }
  }

};
