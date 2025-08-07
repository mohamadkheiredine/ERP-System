function ProductByStock()
{
    var base_url 	= $('input[name=base_url]').val();
    var _token 		= $('input[name=_token]').val();
    $.ajax
    ({
        url : base_url + "/request/dashboard/getstockbyproducts",
        data : { _token : _token },
        method : 'post',
        dataType : "json",
        beforeSend : function(){
        },
        success : function(response){
            let stock_products = response.stock_products;
            stock_products = stock_products.map(item => ({
                ...item,
                value: Number(item.value)
            }));
            console.log(stock_products);
            var root = am5.Root.new("chartdiv1");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: true,
                panY: true,
                wheelX: "panX",
                wheelY: "zoomX",
                pinchZoomX: true,
                paddingLeft:0,
                paddingRight:1
            }));

            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
            cursor.lineY.set("visible", false);

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

            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "Series 1",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                sequencedInterpolation: true,
                categoryXField: "product",
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

            var data = stock_products;

            xAxis.data.setAll(data);
            series.data.setAll(data);
            series.appear(1000);
            chart.appear(1000, 100);
            // am5.ready(function() {
            //
            //
            // }); // end am5.ready()
        }
    });
}


function StockByWarehouse()
{
    var base_url 	= $('input[name=base_url]').val();
    var _token 		= $('input[name=_token]').val();
    $.ajax
    ({
        url : base_url + "/request/dashboard/getstockbywarehouses",
        data : { _token : _token },
        method : 'post',
        dataType : "json",
        beforeSend : function(){
        },
        success : function(response){
            let stock_warehouse = response.stock_warehouse;
            stock_warehouse = stock_warehouse.map(item => ({
                ...item,
                value: Number(item.value)
            }));
            console.log(stock_warehouse);
            var root = am5.Root.new("chartdiv2");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: true,
                panY: true,
                wheelX: "panX",
                wheelY: "zoomX",
                pinchZoomX: true,
                paddingLeft:0,
                paddingRight:1
            }));

            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
            cursor.lineY.set("visible", false);

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
                categoryField: "warehouse",
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

            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "stock by warehouse",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                sequencedInterpolation: true,
                categoryXField: "warehouse",
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

            var data = stock_warehouse;

            xAxis.data.setAll(data);
            series.data.setAll(data);
            series.appear(1000);
            chart.appear(1000, 100);
            // am5.ready(function() {
            //
            //
            // }); // end am5.ready()
        }
    });
}

function TopSellingByProducts()
{
    var base_url 	= $('input[name=base_url]').val();
    var _token 		= $('input[name=_token]').val();
    $.ajax
    ({
        url : base_url + "/request/dashboard/getsellingproducts",
        data : { _token : _token },
        method : 'post',
        dataType : "json",
        beforeSend : function(){
        },
        success : function(response){
            let stock_products = response.stock_products;
            stock_products = stock_products.map(item => ({
                ...item,
                value: Number(item.value)
            }));
            console.log(stock_products);
            var root = am5.Root.new("chartdiv3");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: true,
                panY: true,
                wheelX: "panX",
                wheelY: "zoomX",
                pinchZoomX: true,
                paddingLeft:0,
                paddingRight:1
            }));

            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
            cursor.lineY.set("visible", false);

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

            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "Selling Products",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                sequencedInterpolation: true,
                categoryXField: "product",
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

            var data = stock_products;

            xAxis.data.setAll(data);
            series.data.setAll(data);
            series.appear(1000);
            chart.appear(1000, 100);
            // am5.ready(function() {
            //
            //
            // }); // end am5.ready()
        }
    });
}

$(function(){
    ProductByStock();
    StockByWarehouse();
    TopSellingByProducts();
})
