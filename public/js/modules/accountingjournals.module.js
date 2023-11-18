/**
 * 
 */

journals_module = {
		displayListAccountingJournals : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displaylistjournals",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstAccountJournals').html(response.display); 
		        }
		    });
	},
	ChangeIsActivejournals : function(){ 
			var checkbox 	= $(this).prev();
			var aj_id 		= checkbox.val();
			var is_active 	= 0; 
			if(checkbox.attr("checked") == undefined)
				{
					checkbox.attr("checked", "checked");
					is_active = 1;
				} 
				else
				{
					checkbox.removeAttr("checked");
					is_active = 0;
				}
			
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/changejournalstatus",
		        data : { _token : _token , is_active : is_active , aj_id : aj_id },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	
		        }
		    });
			
			
	}
}