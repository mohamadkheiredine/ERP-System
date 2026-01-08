/**
 *
 */

$(function(){
	 ClassicEditor
     .create( document.querySelector( '#SQ_QUOTATION_NOTES' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $("#BTN_SAVE_QUOTATION").on('click',quotations_module.SaveSupplierQuotationInfo);
	$("#BTN_APPROVE_QUOTATION").on('click',quotations_module.ApproveSupplierQuotation);

    $('.ExpiryDate').each(function(n){
        new tempusDominus.TempusDominus($(this),{
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




     new tempusDominus.TempusDominus(document.getElementById('SQ_DUE_DATE'),{
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


	var base_url 	= $('input[name=base_url]').val();
	var _token 		= $('input[name=_token]').val();
	$.ajax
	({
		url : base_url + "/request/cache/generatecache/products",
		data : { _token : _token},
		method : 'post',
		dataType : "json",
		beforeSend : function(){
		},
		success : function(response){
			localStorage.setItem('products_cache_url',response.cache_url);

		    $(".ProductNameField")
		      .bsSuggest("init", {
		        clearable: true,
                  ignorecase: true,
		        url:response.cache_url,
		        idField: "id",
			      keyField: "product_name",
			      effectiveFields: ["product_name"],
		            searchFields: [ "product_name"]
		      })
		      .on("onDataRequestSuccess", function(e, result) {
		      })
		      .on("onSetSelectValue", function(e, keyword, data) {
                  console.log(data);
                  console.log($(this).parents('tr').find('.PurchasePrice'));
                  console.log(data.selling_price);
		    	 let use_serial_number = data.product_use_serial;
		        let product_id = data.id;
                $(this).parents('tr').find('.ProductCode').val(data.barcode);
                $(this).parents('tr').find('.PurchasePrice').val(data.cost_price);
                $(this).parents('tr').find('.SellingPrice').val(data.selling_price);
               $(this).parents('tr').find('.WholeSalePrice').val(data.min_selling_price);
                $(this).parents('tr').find('.ProductId').val(product_id);


		        $(this).parents('tr').find('.CurrencyId').val(data.currency);
		        if(data.product_use_serial == 1)
	        	{
		        	$(this).parents('tr').find('.ListSerialNumbers').css({'display' : ''});
	        	}
		        else
		        {
		        	$(this).parents('tr').find('.ListSerialNumbers').css({'display' : 'none'});
		        }
		      })
		      .on("onUnsetSelectValue", function() {
		        console.log("onUnsetSelectValue");
		      })
		      .on("onShowDropdown", function(e, data) {
		        console.log("onShowDropdown", e.target.value, data);
		      })
		      .on("onHideDropdown", function(e, data) {
		        console.log("onHideDropdown", e.target.value, data);
		      })


		}
	});


	$('#ADD_PRODUCT').on('click',function(){
		let row_count = $("#ListProducts tr.QuotationItems").length;
		let new_count = 0;
		new_count = row_count + 1;
		let clone_row = $("#ListProducts tr.QuotationItems:last").clone();
		let product_cache = localStorage.getItem('products_cache_url');
		clone_row.find('.ProductCode').val('');
		clone_row.find('.ProductNameField').val('');
		clone_row.find('.ProductDescription').val('');
		clone_row.find('.PurchasePrice').val('0');
		clone_row.find('.ProductDiscount').val('0');
		clone_row.find('.SellingPrice').val('0');
		clone_row.find('.WholeSalePrice').val('0');
		clone_row.find('.VendorPrice').val('0');
		clone_row.find('.SerialNumbers').val('');
		clone_row.find('.ProductId').val('0');
		clone_row.find('.CurrencyId').val('0');
		clone_row.find('.StockQuantity').val('0');
		clone_row.find('.DeleteCode').css('display','');
		clone_row.data('index',new_count);
		clone_row.attr('data-index',new_count);
		$('#ListProducts').append(clone_row);
		$(".ProductNameField:last").bsSuggest("init", {
	        clearable: true,
	        url:product_cache,
	        idField: "id",
		      keyField: "product_name",
		      effectiveFields: ["product_name"],
	            searchFields: [ "product_name"],
            ignorecase: true  // Add this line
	      })
	      .on("onDataRequestSuccess", function(e, result) {
	      })
	      .on("onSetSelectValue", function(e, keyword, data) {
	        let product_id = data.id;
	        $(this).parents('tr').find('.ProductCode').val(data.barcode);
              $(this).parents('tr').find('.PurchasePrice').val(data.cost_price);
              $(this).parents('tr').find('.SellingPrice').val(data.selling_price);
              $(this).parents('tr').find('.WholeSalePrice').val(data.min_selling_price);
	        $(this).parents('tr').find('.VendorPrice').val(data.selling_price);
	        $(this).parents('tr').find('.ProductId').val(data.id);
	        $(this).parents('tr').find('.CurrencyId').val(data.currency);
	        if(data.product_use_serial == 1)
        	{
	        	$(this).parents('tr').find('.ListSerialNumbers').css({'display' : ''});
        	}
	        else
	        {
	        	$(this).parents('tr').find('.ListSerialNumbers').css({'display' : 'none'});
	        }
	      })
	      .on("onUnsetSelectValue", function() {
	        console.log("onUnsetSelectValue");
	      })
	      .on("onShowDropdown", function(e, data) {
	        console.log("onShowDropdown", e.target.value, data);
	      })
	      .on("onHideDropdown", function(e, data) {
	        console.log("onHideDropdown", e.target.value, data);
	      });


        $('select').destroy().select2();
	});


	$('#ListProducts').on('focus','.ProductCode',quotations_module.GetProductInfoByBarCode);
	$('#ListProducts').on('focus','.SerialNumbers',quotations_module.OpenAddNewProduct);

	$('.ListSerialNumbers').on('click',function(){
		let index = $(this).parents('tr').data('index');
		let serial_numbers = $(this).parents('tr').find('.SerialNumbers').val();
		let stock_quantity = $(this).parents('tr').find('.StockQuantity').val();
		var base_url 	= $('input[name=base_url]').val();
		var myWindow = window.open(base_url + "/srm/quotation/addserial?index=" + index + "&serial_numbers=" + serial_numbers + "&stock_quantity=" + stock_quantity, "Serial Numbers", "width=400,height=600");
	});
	$("#ListProducts").on('click',".ListSerialNumbers",function(){
		let index = $(this).parents('tr').data('index');
		let serial_numbers = $(this).parents('tr').find('.SerialNumbers').val();
		let stock_quantity = $(this).parents('tr').find('.StockQuantity').val();
		var base_url 	= $('input[name=base_url]').val();
		var myWindow = window.open(base_url + "/srm/quotation/addserial?index=" + index + "&serial_numbers=" + serial_numbers + "&stock_quantity=" + stock_quantity , "Serial Numbers", "width=400,height=600");
	});
	$('#ListProducts').on('click','.DeleteCode',function(){
		$(this).parents('tr').remove();
	});


    $('.ProductCode').on('keydown', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            return false;
        }
    });
    $('.ProductDescription').on('keydown', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            return false;
        }
    });
    $('.ProductNameField').on('keydown', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            return false;
        }
    });
})
