/**
 * 
 */

defaultaccounts_module = {
		displayListDefaultAccounts : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val()
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displaydefaultaccounts",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstDefaultAccounts').html(response.display);
		        	$.da_datatable = $('.m_datatable').mDatatable({
						// layout definition
						layout: {
							theme: 'default', // datatable theme
							class: '', // custom wrapper class
							scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
							// height: 450, // datatable's body's fixed height
							footer: false // display/hide footer
						},
						// column sorting
						sortable: true,
						pagination: true,
						search: {
							input: $('#generalSearch')
						},
						// inline and bactch editing(cooming soon)
						// editable: false,
					});
					$('select').select2();
		        }
		    });
		},
		SaveDefaultAccountsInfo : function(){
			var base_url = $('#BASE_URL').val();
 	        var str_params = $("#FRM_SAVE_ACCOUNTS").serialize();
 
 	         $.ajax
 	        ({
 	            url : base_url + "/request/accounting/savedefaultaccounts",
 	            data : str_params,
 	            method : 'post',
 	            dataType : "json",
 	            beforeSend : function(){
 	            },
 	            success : function(response){
 	              if(response.is_error == 0)
 	              { 
 	            	  bootbox.alert(response.error_msg);
 	            	 $.da_datatable.destroy();
 	            	 defaultaccounts_module.displayListDefaultAccounts();
 	              }
 	            }
 	        });
		}
};