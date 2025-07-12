/**
 *
 */
$.editor
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#P_PRODUCT_DESCRIPTION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('#PP_AVATAR_PIC').on('change', function () {
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

	                        $("#AVATAR_PIC").attr('src', e.target.result);
	                    }
	                    image_holder.show();
	                    reader.readAsDataURL($(this)[0].files[i]);
	                }

	            }
	        }
	    });

	 var p_id = $("#P_ID").val();

	 if(p_id > 0)
	 {
		 var unit_type = $("#P_PRODUCT_UNIT_TYPE").val();
		 products_module.DisplayStock();
		 products_module.DisplayStockMovement();
		 if(unit_type != '')
			 products_module.DisplayMetricProduct();
	 }

	 let warehouse_id = $("#FK_WWAREHOUSE_ID").val();
	 if(warehouse_id == "")
	 {
		products_module.GetZonesDropdown();
	 }

	 $("select").select2();
	 $("#P_BAR_CODE").on("blur",products_module.GenerateBarCode);
	 $("button[id*=BTN_SAVE_PRODUCT]").on("click",products_module.SaveProductInfo);
	 $("button[name=btn_save_new_product]").on("click",products_module.SaveAndNewProductInfo);
	 $("#BTN_ADD_STOCK").on("click",products_module.AddProductStock);
	 $("#BTN_TRANSFER_STOCK").on("click",products_module.ProductTransferStock);
	 $("#P_PRODUCT_UNIT_TYPE").on("change",products_module.DisplayMetricProduct);
	 $("#FK_WWAREHOUSE_ID").on("change",products_module.GetZonesDropdown);
	 $('.DefaultZone').on('change','#FK_ZONE_ID',products_module.GetFloorDropDown)
	 $("#P_PRODUCT_CURRENCY").on("change",products_module.ChangeCurrencyLabel);
	 $('.dropdown-item').on('click',products_module.QuickActions);
	 new tempusDominus.TempusDominus(document.getElementById('P_PRODUCT_PRODUCTION_DATE'),{
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
	 new tempusDominus.TempusDominus(document.getElementById('P_PRODUCT_EXPIRY_DATE'),{
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
})
