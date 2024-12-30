/**
 * 
 */
$(function(){
	 $("#BTN_SAVE_CONFIGURATION").on('click',function(){
		 var base_url = $('#BASE_URL').val();
	        var _token = $('input[name=_token]').val();
	        var str_params = $("#FORM_SAVE_CONFIGURATIONS").serialize();
	        str_params +=  "&_token=" + _token;
	         $.ajax
	        ({
	            url : base_url + "/ajaxsaveConfiguration",
	            data : str_params,
	            dataType : "json",
	            type : "POST",
	            success : function(response){
	            	bootbox.alert(response.error_msg);
	            }
	        });
	    });
})