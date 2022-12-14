/**
 * 
 */
$(function(){
	
	$("#BTN_SAVE_PAY_TYPE").on('click',function(){
		 var base_url = $('#BASE_URL').val();
	       // var _token = $('input[name=_token]').val();
	        var str_params = $("#FRM_SAVE_PAYMENT_TYPES").serialize();
	         $.ajax
	        ({
	            url : base_url + "/request/billing/savepaymenttypes",
	            data : str_params,
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
	            success : function(response){
	              if(response.is_error == 0)
	              {
	                 window.location.href = base_url + "/billing/paymenttypes";
	              }
	            }
	        });
	})
	
		$("#BACK_FORM").on('click',function(){
			  window.history.back();
		})
})