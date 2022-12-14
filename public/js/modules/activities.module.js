/**
 * 
 */
activities_module = {
		DisplayListActivities : function(){
			var base_url 		= $('#BASE_URL').val();
			var _token 			= $('input[name=_token]').val();
			var display_type 	= $('input[name=display_type]').val();
			var activities_lead = $('select[name=activities_lead]').val();
			var activities_user = $('select[name=activities_user]').val();
			var str_params ={ activities_lead : activities_lead , activities_user : activities_user , _token : _token , display_type : display_type };
			$.ajax
			({
				url : base_url + "/request/activities/displaylist",
				data : str_params,
				dataType : "Json",
				type : "POST",
				success : function(response){ 
					if(response.is_error == 0)
					{
						$("#LstActivities").html(response.display);
						$.ca_datatable = $('.m_datatable').mDatatable({
							// layout definition
							layout: {
								theme: 'default', // datatable theme
								class: '', // custom wrapper class
								scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
								// height: 450, // datatable's body's fixed height
								footer: false // display/hide footer
							},
							columns : [
		        				{
		        					field: "#",
		        			        title: "#", 
		        			        sortable: false,
		        			        width: 40,
		        			        selector: {class: 'm-checkbox--solid m-checkbox--brand'}
		        				},
		        				{
		        					field: 'ID',
		        					type: 'number',  
	        				        sortable: true,
	        				        width: 40, 
		        				},
		        				{
		        					field: 'Contact Name',
		        					type: 'text',  
	        				        sortable: true,
	        				        width: 150, 
		        				},
		        				{
		        					field: 'User Responsible',
		        					type: 'text',  
	        				        sortable: true,
	        				        width: 180, 
		        				},
		        				{
		        					field: "Edit",
		        			        title: "Edit", 
		        			        sortable: false,
		        			        width: 40
		        				},
		        				{
		        					field: "Delete",
		        			        title: "Delete", 
		        			        sortable: false,
		        			        width: 40
		        				}
		        				
		        			],
							
							// column sorting
							sortable: true,
							
							pagination: true,
							
							search: {
								input: $('#generalSearch')
							},
							
							// inline and bactch editing(cooming soon)
							// editable: false,
						});
						
						$("a[id*=EDIT_ACTIVITY_]").on('click',activities_module.EditActivityInfo);
						$("a[id*=DELETE_ACTIVITY_]").on('click',activities_module.DeleteActivityData);
						
					}
				}
			});
		},
		EditActivityInfo : function(){
			var ca_id = $(this).data("ca_id");
			var base_url = $('#BASE_URL').val();
			 window.location.href = base_url + "/crm/leads/editactivitryform/" + ca_id;
		},
		DeleteActivityData : function(){
			 var ca_id = $(this).data('ca_id');
				bootbox.confirm("Are you sure you want to delete ?", function(result){
					//result
					if(result == true)
					{
					      var base_url = $('#BASE_URL').val();
					      var _token = $('input[name=_token]').val();
					        var str_params ={ca_id : ca_id , _token : _token};
					         $.ajax
					        ({
					            url : base_url + "/request/leads/deleteleadactivity",
					            data : str_params,
					            dataType : "Json",
					            type : "POST",
					            success : function(response){
					              if(response.is_error == 0)
					              {
					            	  activities_module.DisplayListActivities();
					              }
					            }
					        });
					}
				});
		}
}