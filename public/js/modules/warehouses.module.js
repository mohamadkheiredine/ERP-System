/**
 *
 */

var warehouses_module = {
			DisplayListWareHouses : function(){
				var base_url 			= $('input[name=base_url]').val();
			    var _token	 			= $('input[name=_token]').val();
			    var params = { _token : _token };
			    $.ajax
		        ({
		            url : base_url + "/request/displaywarehousemanagement",
		            data : params,
		            dataType : "json",
		            type : "POST",
		            success : function(response){
		            	$('#LstWarehouses').html(response.display);

		            	var datatable = $('.m_datatable').mDatatable({

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
		        					field: 'Name',
		        					type: 'text',
		        					sortable: true,
		        					width: 250,
		        				},
		        				{
		        					field: 'City',
		        					type: 'text',
		        					sortable: true,
		        					width: 200,
		        				},
		        				{
		        					field: 'Status',
		        					type: 'text',
		        					sortable: true,
		        					width: 100,
		        				},
		        				{
		        					field: "edit",
		        			        title: "edit", 
		        			        sortable: false,
		        			        width: 40
		        				},
		        				{
		        					field: "settings",
		        			        title: "settings", 
		        			        sortable: false,
		        			        width: 60
		        				},
		        				{
		        					field: "delete",
		        			        title: "delete", 
		        			        sortable: false,
		        			        width: 40
		        				}
		        			]
		        			// inline and bactch editing(cooming soon)
		        			// editable: false,
		        		});

		            }
		        });
			},
			DisplayWarehouseDimensionsTab : function(){
				var base_url 			= $('input[name=base_url]').val();
				var _token	 			= $('input[name=_token]').val();
				var warehouse_id	 	= $('input[name=warehouse_id]').val();
				var params = { _token : _token , warehouse_id : warehouse_id };
				$.ajax
				({
					url : base_url + "/request/warehouse/displaydimensions",
					data : params,
					dataType : "json",
					type : "POST",
					success : function(response){
						$('#WAREHOUSEDIMENSIONS').html(response.display);
						
					}
				});
			},
			AddNewWarehouseZone : function(){
				var warehouse_id	 	= $('input[name=warehouse_id]').val();
				var base_url 			= $('input[name=base_url]').val();
				window.location.href =  base_url + "/inventory/warehouse/addzone/" + warehouse_id;
			},
			DisplayWarehouseSettingsTab  : function(){
				var base_url 			= $('input[name=base_url]').val();
				var _token	 			= $('input[name=_token]').val();
				var warehouse_id	 	= $('input[name=warehouse_id]').val();
			    var tab	 				= $('input[name=tab]').val();
			    var params = { _token : _token , warehouse_id : warehouse_id , tab : tab };
			    $.ajax
		        ({
		            url : base_url + "/request/warehouse/displaysettingstabs",
		            data : params,
		            dataType : "json",
		            type : "POST",
		            success : function(response){
		            	switch(tab)
		            	{
			            	case "warehouse_dimension":
			            	{ 
			            		$('#WAREHOUSEDIMENSIONS').html(response.display);
			            		warehouses_module.DrawWarehouseImage();
			            	}
			            	break;
			            	case "warehouse_zones":
			            	{ 
			            		$('#m_wizard_warehouse_zones').html(response.display);
			            	}
			            	break;
			            	case "warehouse_employees":
			            	{ 
			            		$('#m_wizard_warehouse_employees').html(response.display);
			            	}
			            	break;
		            		case "warehouse_load":
		            		{
		            			$('#m_wizard_warehouse_load').html(response.display);
		            			$.lead_datatable = $('.m_datatable').mDatatable({

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
		    	        				//input: $('#generalSearch')
		    	        			},

		    	        			// inline and bactch editing(cooming soon)
		    	        			// editable: false,
		    	        		});
		            			
		            			 var chart = AmCharts.makeChart("m_warehouseloadzonechart", {
		            		            "type": "serial",
		            		            "theme": "light",
		            		            "dataProvider": JSON.parse(response.zonesstock),
		            		            "valueAxes": [{
		            		                "gridColor": "#FFFFFF",
		            		                "gridAlpha": 0.2,
		            		                "dashLength": 0
		            		            }],
		            		            "gridAboveGraphs": true,
		            		            "startDuration": 1,
		            		            "graphs": [{
		            		                "balloonText": "[[category]]: <b>[[value]]</b>",
		            		                "fillAlphas": 0.8,
		            		                "lineAlpha": 0.2,
		            		                "type": "column",
		            		                "valueField": "quantity"
		            		            }],
		            		            "chartCursor": {
		            		                "categoryBalloonEnabled": false,
		            		                "cursorAlpha": 0,
		            		                "zoomable": false
		            		            },
		            		            "categoryField": "zone",
		            		            "categoryAxis": {
		            		                "gridPosition": "start",
		            		                "gridAlpha": 0,
		            		                "tickPosition": "start",
		            		                "tickLength": 500
		            		            },
		            		            "export": {
		            		                "enabled": true
		            		            }

		            		        });
		            		}
		            		break;
		            			
		            	} 
		            
		            }
		        });
			},
			SaveWareHouseSettings : function(){
				var params = $("#FORM_WAREHOUSE_SETTINGS").serialize();
				var base_url 			= $('input[name=base_url]').val();
				$.ajax
		        ({
		            url : base_url + "/request/warehouse/savewarehousesettings",
		            data : params,
		            dataType : "json",
		            type : "POST",
		            success : function(response){
		            	 var tab = $("#TAB").val();
		            	switch(tab)
		            	{
			            	case "warehouse_dimension":
			            	{
			            		$('input[name=tab]').val("warehouse_zones"); 
			            	}
			            	break;
			            	case "warehouse_zones":
			            	{
			            		$('input[name=tab]').val("warehouse_employees"); 
			            	}
			            	break;
			            	case "warehouse_employees":
			            	{
			            		$('input[name=tab]').val("warehouse_load");
			            	}
			            	break;
		            		case "warehouse_load":
		            		{
		  
		            		}
		            		break;
		            	}
		            	
		            	warehouses_module.DisplayWarehouseSettingsTab();
		            }
		        });
			},
			SaveWarehouseZoneInfo : function(){
				return warehouses_module.SaveZoneSubmitHandler();
			},
			SaveZoneSubmitHandler : function(){
				var SaveZoneForm = $('#FORM_SAVE_WAREHOUSE_ZONE');
		         var error3 = $('.alert-danger', SaveZoneForm);
		         var success3 = $('.alert-success', SaveZoneForm);

		         SaveZoneForm.validate({
		             errorElement: 'span', //default input error message container
		             errorClass: 'help-block help-block-error', // default input error message class
		             focusInvalid: false, // do not focus the last invalid input
		             ignore: "", // validate all fields including form hidden input
		             rules: {
		            	 wz_zone_label: {
		            		 minlength: 4,
		            		 required: true
		            	 },
		            	 wz_zone_color: { 
		            		 required: true
		            	 },
		            	 wz_zone_length: { 
		            		 required: true
		            	 },
		            	 wz_zone_width: { 
		            		 required: true
		            	 },
		            	 wz_zone_height: { 
		            		 required: true
		            	 }
		             },

		             messages: { // custom messages for radio buttons and checkboxes

		             },
		             errorPlacement: function (error, element) { // render error placement for each input type
		                 if (element.parent(".input-group").length > 0) {
		                     error.insertAfter(element.parent(".input-group"));
		                 } else if (element.attr("data-error-container")) {
		                     error.appendTo(element.attr("data-error-container"));
		                 } else if (element.parents('.radio-list').length > 0) {
		                     error.appendTo(element.parents('.radio-list').attr("data-error-container"));
		                 } else if (element.parents('.radio-inline').length > 0) {
		                     error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
		                 } else if (element.parents('.checkbox-list').length > 0) {
		                     error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
		                 } else if (element.parents('.checkbox-inline').length > 0) {
		                     error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
		                 } else {
		                     error.insertAfter(element); // for other inputs, just perform default behavior
		                 }
		             },
		             invalidHandler: function (event, validator) { //display error alert on form submit
		                 success3.hide();
		                 error3.show();
		             },
		             success: function (label) {
		                 label
		                     .closest('.form-group').removeClass('has-error'); // set success class to the control group
		             },
		             highlight: function (element) { // hightlight error inputs
		                 $(element)
		                     .closest('.form-group').addClass('has-error'); // set error class to the control group
		             },

		             unhighlight: function (element) { // revert the change done by hightlight
		                 $(element)
		                     .closest('.form-group').removeClass('has-error'); // set error class to the control group
		             },
		             submitHandler: function (form) {
		                success3.show();
		                error3.hide();
		                var base_url = $('#BASE_URL').val();
		    	        var str_params = $("#FORM_SAVE_WAREHOUSE_ZONE").serialize();
		    	         $.ajax
		    	        ({
		    	            url : base_url + "/request/warehouse/SaveWarehouseZone",
		    	            data : str_params,
		    	            dataType : "json",
		    	            type : "POST",
		    	            success : function(response){
		    	              if(response.is_error == 0)
		    	              {
		    	            	  var w_id = $("#W_ID").val();
		    	                 window.location.href = base_url + "/inventory/WareHouseSettings/" + w_id;
		    	              }
		    	              else
	    	            	  {
		    	            	  bootbox.alert(response.error_msg);
	    	            	  }
		    	            }
		    	        });
		             }

		         });
			},
			DrawWarehouseImage : function(){
				var canvas = document.getElementById("WAREHOUSEDRAWING");
				var ctx = canvas.getContext("2d");
				var warehouse_type_id = $('input[name=warehouse_type_id]').val();
				if(warehouse_type_id == 1)
				{
					ctx.clearRect(0, 0, canvas.width, canvas.height);
					var width = $("#W_WAREHOUSE_WIDTH").val();
					var height = $("#W_WAREHOUSE_HEIGHT").val();
					var length = $("#W_WAREHOUSE_LENGTH").val();
					  drawing_module.drawCube(300,300, width , length, height ,"#b2d7f4", ctx);
				}
				else
				{
					var volume = $("#W_WAREHOUSE_VOLUME").val();
 
					var c = document.getElementById("WAREHOUSEDRAWING");
					var ctx = c.getContext("2d");
					ctx.clearRect(0, 0, canvas.width, canvas.height);
					ctx.beginPath();
					ctx.rect(20, 20, volume, 150 );
					ctx.fillStyle = '#8ED6FF';
					ctx.strokeStyle = 'blue';
					ctx.stroke();
					ctx.fill();
				}
				
				


 
				
				  
			},
			SaveWareHouse : function(){
				return warehouses_module.SaveWareHouseSubmitHandler();
			},
			SaveWareHouseSubmitHandler : function(){
				 var SaveWarehouseForm = $('#FORM_SAVE_WAREHOUSE');
		         var error3 = $('.alert-danger', SaveWarehouseForm);
		         var success3 = $('.alert-success', SaveWarehouseForm);

		         SaveWarehouseForm.validate({
		             errorElement: 'span', //default input error message container
		             errorClass: 'help-block help-block-error', // default input error message class
		             focusInvalid: false, // do not focus the last invalid input
		             ignore: "", // validate all fields including form hidden input
		             rules: {
		            	 w_warehouse_ref: {
		            		 minlength: 4,
		            		 required: true
		            	 },
		            	 w_warehouse_name: {
		            		 minlength: 6,
		            		 required: true
		            	 },
		            	 w_warehouse_size_type : {
		            		 required : true
		            	 }
		             },

		             messages: { // custom messages for radio buttons and checkboxes

		             },
		             errorPlacement: function (error, element) { // render error placement for each input type
		                 if (element.parent(".input-group").length > 0) {
		                     error.insertAfter(element.parent(".input-group"));
		                 } else if (element.attr("data-error-container")) {
		                     error.appendTo(element.attr("data-error-container"));
		                 } else if (element.parents('.radio-list').length > 0) {
		                     error.appendTo(element.parents('.radio-list').attr("data-error-container"));
		                 } else if (element.parents('.radio-inline').length > 0) {
		                     error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
		                 } else if (element.parents('.checkbox-list').length > 0) {
		                     error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
		                 } else if (element.parents('.checkbox-inline').length > 0) {
		                     error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
		                 } else {
		                     error.insertAfter(element); // for other inputs, just perform default behavior
		                 }
		             },
		             invalidHandler: function (event, validator) { //display error alert on form submit
		                 success3.hide();
		                 error3.show();
		             },
		             success: function (label) {
		                 label
		                     .closest('.form-group').removeClass('has-error'); // set success class to the control group
		             },
		             highlight: function (element) { // hightlight error inputs
		                 $(element)
		                     .closest('.form-group').addClass('has-error'); // set error class to the control group
		             },

		             unhighlight: function (element) { // revert the change done by hightlight
		                 $(element)
		                     .closest('.form-group').removeClass('has-error'); // set error class to the control group
		             },
		             submitHandler: function (form) {
		                success3.show();
		                error3.hide();
		                var base_url = $('#BASE_URL').val();
		                $("#ALLOWED_VEHICULES option").each(function(){
		                	$(this).attr("selected",true);
		                });
		                var str_params = $("#FORM_SAVE_WAREHOUSE").serialize();
		                //$.editor
		                
		    	         $.ajax
		    	        ({
		    	            url : base_url + "/request/SaveWareHouse",
		    	            data : str_params,
		    	            dataType : "json",
		    	            type : "POST",
		    	            success : function(response){
		    	              if(response.is_error == 0)
		    	              {
		    	                 window.location.href = base_url + "/inventory/warehouses";
		    	              }
		    	            }
		    	        });
		             }

		         });
			},
			EditWareHouseInformation : function(){
				var base_url = $('#BASE_URL').val();
				var w_id = $(this).data('w_id');
				window.location.href = base_url + "/inventory/editwarehouse/" + w_id;
			},
			WareHouseSettings : function(){
				 var base_url = $('#BASE_URL').val();
				 var w_id = $(this).data('w_id');
				window.location.href = base_url + "/inventory/WareHouseSettings/" + w_id;
			},
			DeleteWareHouse : function(){
				var w_id = $(this).data('w_id');
				bootbox.confirm("Are you sure you want to delete ?", function(result){
					//result
					if(result == true)
					{
						var base_url = $('#BASE_URL').val();
						var _token = $('input[name=_token]').val();
						var str_params ={w_id : w_id , _token : _token};
						$.ajax
						({
							url : base_url + "/request/DeleteWareHouse",
							data : str_params,
							dataType : "Json",
							type : "POST",
							success : function(response){
								if(response.is_error == 0)
								{
									warehouses_module.DisplayListWareHouses();
								}
							}
						});
					}
				});
			},
			DeleteWarehouseZone : function(){
				var wz_id = $(this).parents("tr").data('wz_id');
				bootbox.confirm("Are you sure you want to delete ?", function(result){
					//result
					if(result == true)
					{
						var base_url = $('#BASE_URL').val();
						var _token = $('input[name=_token]').val();
						var str_params ={wz_id : wz_id , _token : _token};
						$.ajax
						({
							url : base_url + "/request/warehouse/deletezone",
							data : str_params,
							dataType : "Json",
							type : "POST",
							success : function(response){
								if(response.is_error == 0)
								{
									warehouses_module.DisplayListWareHouses();
								}
							}
						});
					}
				});
			},
			DeleteWarehouseEmployee : function(){
				var we_id = $(this).parents("tr").data('user_id');
				var warehouse_id	 	= $('input[name=warehouse_id]').val();
					bootbox.confirm("Are you sure you want to delete ?", function(result){
						//result
						if(result == true)
						{
						      var base_url = $('#BASE_URL').val();
						      var _token = $('input[name=_token]').val();
						        var str_params ={we_id : we_id , warehouse_id : warehouse_id , _token : _token};
						         $.ajax
						        ({
						            url : base_url + "/request/warehouse/removeemployee",
						            data : str_params,
						            dataType : "Json",
						            type : "POST",
						            success : function(response){
						              if(response.is_error == 0)
						              {
						            	  warehouses_module.DisplayListWareHouses();
						              }
						            }
						        });
						}
					});
			},
			CancelForm : function(){
				window.history.back();
			},
			AddWarehouseEmployee : function(){
				var warehouse_id	 	= $('input[name=warehouse_id]').val();
				var fk_user_id	 		= $('select[name=fk_user_id]').val();
				 var base_url = $('#BASE_URL').val();
				 var _token = $('input[name=_token]').val();
					var str_params ={warehouse_id : warehouse_id , fk_user_id : fk_user_id , _token : _token};
		 
					$.ajax
					({
						url : base_url + "/request/WareHouse/addemployee",
						data : str_params,
						dataType : "Json",
						type : "POST",
						success : function(response){
							if(response.is_error == 0)
							{ 
								$('#EmployeeModel').modal('toggle');
								 warehouses_module.DisplayWarehouseSettingsTab();
							}
							else
							{
								
								bootbox.alert(response.error_msg);
							}
						}
					});
			}
};