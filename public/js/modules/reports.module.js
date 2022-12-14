/**
 * 
 */
reports_module = {
		DisplayLeadsReport : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val()
		    var str_frm = $('form[name=frm_search_report]').serialize();
		    $.ajax
		    ({
		        url : base_url + "/crm/reports/leads",
		        data : str_frm,
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		            $('.LstLeadReportGrid').html(response.display);
                     $('.group-checkable').change(function() {
                        var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                        var checked = $(this).prop("checked");
                        $(set).each(function() {
                            $(this).prop("checked", checked);
                        });
                        $.uniform.update(set);
                    });
		        }
		    });
		},
		DisplayAccountsReport : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val()
		    var str_frm = $('form[name=frm_search_report]').serialize();
		    $.ajax
		    ({
		        url : base_url + "/crm/reports/accounts",
		        data : str_frm,
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		            $('.LstAccountsReportGrid').html(response.display);
		        }
		    });
		}
};