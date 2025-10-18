/**
 *
 */

var warehouses_module = {
			DisplayListWareHouses : function(){
				var base_url 			= $('input[name=base_url]').val();
			    var _token	 			= $('input[name=_token]').val();
			    var general_search	 			= $('input[name=general_search]').val();
			    var params = { _token : _token , general_search : general_search };
			    $.ajax
		        ({
		            url : base_url + "/request/displaywarehousemanagement",
		            data : params,
		            dataType : "json",
		            type : "POST",
		            success : function(response){
		            	$('#LstWarehouses').html(response.display);
		            }
		        });
			},
            DisplayWarehouseStockAvailability : function(){
                var base_url 			= $('input[name=base_url]').val();
                var _token	 			= $('input[name=_token]').val();
                var sw_stock_warehouse	 	= $('select[name=sw_stock_warehouse]').val();
                var params = { _token : _token , sw_stock_warehouse : sw_stock_warehouse };
                $.ajax
                ({
                    url : base_url + "/request/reports/displayliststockavailability",
                    data : params,
                    dataType : "json",
                    type : "get",
                    success : function(response){
                        $('#LstStockAvailability').html(response.display);

                    }
                });
            },
            DisplayWarehouseStockMovement : function(){
                var base_url 			= $('input[name=base_url]').val();
                var _token	 			= $('input[name=_token]').val();
                var sm_stock_warehouse	 	= $('select[name=sm_stock_warehouse]').val();
                var sm_upto_date	 	= $('input[name=sm_upto_date]').val();
                var params = { _token : _token , sm_stock_warehouse : sm_stock_warehouse , sm_upto_date : sm_upto_date };
                $.ajax
                ({
                    url : base_url + "/request/reports/displayliststockmovement",
                    data : params,
                    dataType : "json",
                    type : "get",
                    success : function(response){
                        $('#LstStockMovement').html(response.display);

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
            QuickSAActionMenu : function(){
                let action_type = $(this).data('action_type');
                switch(action_type)
                {
                    case "EXPORT_AS_CSV":
                    {
                        warehouses_module.DownloadReportAsCSV();
                    }
                    break;
                    case "EXPORT_AS_PDF":
                    {
                        warehouses_module.DownloadReportAsPDF();
                    }
                    break;
                }
            },
            QuickMAActionMenu : function(){
                let action_type = $(this).data('action_type');
                switch(action_type)
                {
                    case "EXPORT_AS_CSV":
                    {
                        warehouses_module.DownloadSMReportAsCSV();
                    }
                    break;
                    case "EXPORT_AS_PDF":
                    {
                        warehouses_module.DownloadSMReportAsPDF();
                    }
                    break;
                }
            },
            DownloadReportAsCSV : function(){
                let sw_stock_warehouse = $('select[name=sw_stock_warehouse]').val();
                let base_url = $('#BASE_URL').val();
                let _token = $('input[name=_token]').val();
                $.ajax({
                    url: base_url + "/request/reports/downloadstockavailability?type=csv&_token=" + _token + "&sw_stock_warehouse=" + sw_stock_warehouse,
                    method: "GET",
                    success: function(data) {

                        const blob = new Blob([data]);
                        // Create a Blob URL for the binary data
                        var blobUrl = window.URL.createObjectURL(blob);
                        // Create a temporary anchor element
                        var a = document.createElement('a');
                        a.href = blobUrl;
                        a.download = 'stock-availability.csv'; // Set the desired file name

                        // Programmatically trigger a click on the anchor to start the download
                        document.body.appendChild(a);
                        a.click();

                        // Clean up resources
                        window.URL.revokeObjectURL(blobUrl);
                        document.body.removeChild(a);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error downloading file:", error);
                    }
                });
            },
            DownloadSMReportAsCSV : function(){
                var sm_stock_warehouse	 	= $('select[name=sm_stock_warehouse]').val();
                var sm_upto_date	 	= $('input[name=sm_upto_date]').val();
                let base_url = $('#BASE_URL').val();
                let _token = $('input[name=_token]').val();
                $.ajax({
                    url: base_url + "/request/reports/downloadstockmovements?type=csv&_token=" + _token + "&sm_stock_warehouse=" + sm_stock_warehouse + "&sm_upto_date=" + sm_upto_date,
                    method: "GET",
                    success: function(data) {

                        const blob = new Blob([data]);
                        // Create a Blob URL for the binary data
                        var blobUrl = window.URL.createObjectURL(blob);
                        // Create a temporary anchor element
                        var a = document.createElement('a');
                        a.href = blobUrl;
                        a.download = 'warehouse-stock-movememnt.csv'; // Set the desired file name

                        // Programmatically trigger a click on the anchor to start the download
                        document.body.appendChild(a);
                        a.click();

                        // Clean up resources
                        window.URL.revokeObjectURL(blobUrl);
                        document.body.removeChild(a);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error downloading file:", error);
                    }
                });
            },
    DownloadSMReportAsPDF : function(){
        var sm_stock_warehouse	 	= $('select[name=sm_stock_warehouse]').val();
        var sm_upto_date	 	= $('input[name=sm_upto_date]').val();
                let base_url = $('#BASE_URL').val();
                let _token = $('input[name=_token]').val();
                 let url = base_url + "/request/reports/downloadstockmovements?type=pdf&_token=" + _token + "&sm_stock_warehouse=" + sm_stock_warehouse + "&sm_upto_date=" + sm_upto_date;

            window.open(url, '_blank');
            },
            DownloadReportAsPDF : function(){
                let sw_stock_warehouse = $('select[name=sw_stock_warehouse]').val();
                let base_url = $('#BASE_URL').val();
                let _token = $('input[name=_token]').val();
                let url = base_url + "/request/reports/downloadstockavailability?type=pdf&_token=" + _token + "&sw_stock_warehouse=" + sw_stock_warehouse;
                window.open(url, '_blank');
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
		            			// display quantity by zone
		            			am5.ready(function() {

		            				// Create root element
		            				// https://www.amcharts.com/docs/v5/getting-started/#Root_element
		            				var root = am5.Root.new("m_warehouseloadzonechart");

		            				// Set themes
		            				// https://www.amcharts.com/docs/v5/concepts/themes/
		            				root.setThemes([
		            				  am5themes_Animated.new(root)
		            				]);

		            				// Create chart
		            				// https://www.amcharts.com/docs/v5/charts/xy-chart/
		            				var chart = root.container.children.push(am5xy.XYChart.new(root, {
		            				  panX: true,
		            				  panY: true,
		            				  wheelX: "panX",
		            				  wheelY: "zoomX",
		            				  pinchZoomX: true,
		            				  paddingLeft:0,
		            				  paddingRight:1
		            				}));

		            				// Add cursor
		            				// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
		            				var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
		            				cursor.lineY.set("visible", false);


		            				// Create axes
		            				// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
		            				var xRenderer = am5xy.AxisRendererX.new(root, {
		            				  minGridDistance: 30,
		            				  minorGridEnabled: true
		            				});

		            				xRenderer.labels.template.setAll({
		            				  rotation: -90,
		            				  centerY: am5.p50,
		            				  centerX: am5.p100,
		            				  paddingRight: 15
		            				});

		            				xRenderer.grid.template.setAll({
		            				  location: 1
		            				})

		            				var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
		            				  maxDeviation: 0.3,
		            				  categoryField: "zone",
		            				  renderer: xRenderer,
		            				  tooltip: am5.Tooltip.new(root, {})
		            				}));

		            				var yRenderer = am5xy.AxisRendererY.new(root, {
		            				  strokeOpacity: 0.1
		            				})

		            				var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
		            				  maxDeviation: 0.3,
		            				  renderer: yRenderer
		            				}));

		            				// Create series
		            				// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
		            				var series = chart.series.push(am5xy.ColumnSeries.new(root, {
		            				  name: "Series 1",
		            				  xAxis: xAxis,
		            				  yAxis: yAxis,
		            				  valueYField: "quantity",
		            				  sequencedInterpolation: true,
		            				  categoryXField: "zone",
		            				  tooltip: am5.Tooltip.new(root, {
		            				    labelText: "{valueY}"
		            				  })
		            				}));

		            				series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
		            				series.columns.template.adapters.add("fill", function (fill, target) {
		            				  return chart.get("colors").getIndex(series.columns.indexOf(target));
		            				});

		            				series.columns.template.adapters.add("stroke", function (stroke, target) {
		            				  return chart.get("colors").getIndex(series.columns.indexOf(target));
		            				});


		            				// Set data
		            				var data = JSON.parse(response.zonesstock);

		            				xAxis.data.setAll(data);
		            				series.data.setAll(data);


		            				// Make stuff animate on load
		            				// https://www.amcharts.com/docs/v5/concepts/animations/
		            				series.appear(1000);
		            				chart.appear(1000, 100);

		            				}); // end am5.ready()

		            			am5.ready(function() {

		            				// Create root element
		            				// https://www.amcharts.com/docs/v5/getting-started/#Root_element
		            				var root = am5.Root.new("warehousebyproductschart");

		            				// Set themes
		            				// https://www.amcharts.com/docs/v5/concepts/themes/
		            				root.setThemes([
		            				  am5themes_Animated.new(root)
		            				]);

		            				// Create chart
		            				// https://www.amcharts.com/docs/v5/charts/xy-chart/
		            				var chart = root.container.children.push(am5xy.XYChart.new(root, {
		            				  panX: true,
		            				  panY: true,
		            				  wheelX: "panX",
		            				  wheelY: "zoomX",
		            				  pinchZoomX: true,
		            				  paddingLeft:0,
		            				  paddingRight:1
		            				}));

		            				// Add cursor
		            				// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
		            				var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
		            				cursor.lineY.set("visible", false);


		            				// Create axes
		            				// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
		            				var xRenderer = am5xy.AxisRendererX.new(root, {
		            				  minGridDistance: 30,
		            				  minorGridEnabled: true
		            				});

		            				xRenderer.labels.template.setAll({
		            				  rotation: -90,
		            				  centerY: am5.p50,
		            				  centerX: am5.p100,
		            				  paddingRight: 15
		            				});

		            				xRenderer.grid.template.setAll({
		            				  location: 1
		            				})

		            				var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
		            				  maxDeviation: 0.3,
		            				  categoryField: "product",
		            				  renderer: xRenderer,
		            				  tooltip: am5.Tooltip.new(root, {})
		            				}));

		            				var yRenderer = am5xy.AxisRendererY.new(root, {
		            				  strokeOpacity: 0.1
		            				})

		            				var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
		            				  maxDeviation: 0.3,
		            				  renderer: yRenderer
		            				}));

		            				// Create series
		            				// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
		            				var series = chart.series.push(am5xy.ColumnSeries.new(root, {
		            				  name: "Series 1",
		            				  xAxis: xAxis,
		            				  yAxis: yAxis,
		            				  valueYField: "quantity",
		            				  sequencedInterpolation: true,
		            				  categoryXField: "product",
		            				  tooltip: am5.Tooltip.new(root, {
		            				    labelText: "{valueY} Stock"
		            				  })
		            				}));

		            				series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
		            				series.columns.template.adapters.add("fill", function (fill, target) {
		            				  return chart.get("colors").getIndex(series.columns.indexOf(target));
		            				});

		            				series.columns.template.adapters.add("stroke", function (stroke, target) {
		            				  return chart.get("colors").getIndex(series.columns.indexOf(target));
		            				});


		            				// Set data
		            				var data = JSON.parse(response.productstock);

		            				xAxis.data.setAll(data);
		            				series.data.setAll(data);


		            				// Make stuff animate on load
		            				// https://www.amcharts.com/docs/v5/concepts/animations/
		            				series.appear(1000);
		            				chart.appear(1000, 100);

		            				}); // end am5.ready()



//		            			 var chart = AmCharts.makeChart("m_warehouseloadzonechart", {
//		            		            "type": "serial",
//		            		            "theme": "light",
//		            		            "dataProvider": JSON.parse(response.zonesstock),
//		            		            "valueAxes": [{
//		            		                "gridColor": "#FFFFFF",
//		            		                "gridAlpha": 0.2,
//		            		                "dashLength": 0
//		            		            }],
//		            		            "gridAboveGraphs": true,
//		            		            "startDuration": 1,
//		            		            "graphs": [{
//		            		                "balloonText": "[[category]]: <b>[[value]]</b>",
//		            		                "fillAlphas": 0.8,
//		            		                "lineAlpha": 0.2,
//		            		                "type": "column",
//		            		                "valueField": "quantity"
//		            		            }],
//		            		            "chartCursor": {
//		            		                "categoryBalloonEnabled": false,
//		            		                "cursorAlpha": 0,
//		            		                "zoomable": false
//		            		            },
//		            		            "categoryField": "zone",
//		            		            "categoryAxis": {
//		            		                "gridPosition": "start",
//		            		                "gridAlpha": 0,
//		            		                "tickPosition": "start",
//		            		                "tickLength": 500
//		            		            },
//		            		            "export": {
//		            		                "enabled": true
//		            		            }
//
//		            		        });
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
