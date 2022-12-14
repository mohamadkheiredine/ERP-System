/**
 * 
 */
payroll_module = {
	DisplayListPayRoll : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
	    var ts_date 	= $('input[name=ts_date]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/payroll/displaylistpayroll",
	        data : { _token : _token , ts_date : ts_date },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('#LstEmployeesPayRoll').html(response.display);
				$.ep_datatable = $('.m_datatable').mDatatable({
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
					columns : [
        				{
        					field: 'ID',
        					type: 'number'
        				},
        				{
        					field: 'Full Name',
        					type: 'text'
        				},
        				{
        					field: 'Email',
        					type: 'text'
        				},
        				{
        					field: 'Phone',
        					type: 'text'
        				},
        				{
        					field: 'Mobile',
        					type: 'text'
        				},
        				{
        					field: 'Sallary',
        					type: 'number'
        				}
        			],
					
					// inline and bactch editing(cooming soon)
					// editable: false,
				});
	        }
	    });
	},
	GenerateMonthlyPayRoll : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
	    var ts_date 	= $('input[name=ts_date]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/payroll/generatemonthpayroll",
	        data : { _token : _token , ts_date : ts_date },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	if(response.is_error == 1)
        		{
	        		bootbox.alert(response.error_msg);
        		}
	        	else
        		{
	        		$.ep_datatable.destroy();
	        		payroll_module.DisplayListPayRoll();
        		}
	        
	        }
	    });
	}
};