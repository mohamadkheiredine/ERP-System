//== Class definition
var Dashboard = function() {

    //== Sparkline Chart helper function
    var _initSparklineChart = function(src, data, color, border) {
        if (src.length == 0) {
            return;
        }

        var config = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October"],
                datasets: [{
                    label: "",
                    borderColor: color,
                    borderWidth: border,

                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 12,
                    pointBackgroundColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                    pointBorderColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                    pointHoverBackgroundColor: mUtil.getColor('danger'),
                    pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.1).rgbString(),
                    fill: false,
                    data: data,
                }]
            },
            options: {
                title: {
                    display: false,
                },
                tooltips: {
                    enabled: false,
                    intersect: false,
                    mode: 'nearest',
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                legend: {
                    display: false,
                    labels: {
                        usePointStyle: false
                    }
                },
                responsive: true,
                maintainAspectRatio: true,
                hover: {
                    mode: 'index'
                },
                scales: {
                    xAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },

                elements: {
                    point: {
                        radius: 4,
                        borderWidth: 12
                    },
                },

                layout: {
                    padding: {
                        left: 0,
                        right: 10,
                        top: 5,
                        bottom: 0
                    }
                }
            }
        };

        return new Chart(src, config);
    }

    //== Daily Sales chart.
    //** Based on Chartjs plugin - http://www.chartjs.org/
    var dailySales = function() {
    	var _token 			= $('input[name=_token]').val();
    	var base_url = $('#BASE_URL').val();
    	var params = {_token : _token};
    	$.ajax
    	({
    		url : base_url + "/request/dashboard/services/getdailysales",
    		data : params,
    		dataType : "Json",
    		type : "POST",
    		success : function(response){
    			if(response.is_error == 0)
    			{
    				var chartContainer = $('#m_chart_daily_sales_usd');
    				
    				if (chartContainer.length == 0) {
    					return;
    				}
    				
    				var chartData = {
    						labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
    						datasets: [{
    							//label: 'Dataset 1',
    							backgroundColor: mUtil.getColor('success'),
    							data: response.daily_orders_usd_array
    						}, {
    							//label: 'Dataset 2',
    							backgroundColor: '#f3f3fb',
    							data: response.daily_orders_usd_array
    						}]
    				};
    				
    				var chart = new Chart(chartContainer, {
    					type: 'bar',
    					data: chartData,
    					options: {
    						title: {
    							display: false,
    						},
    						tooltips: {
    							intersect: false,
    							mode: 'nearest',
    							xPadding: 10,
    							yPadding: 10,
    							caretPadding: 10
    						},
    						legend: {
    							display: false
    						},
    						responsive: true,
    						maintainAspectRatio: false,
    						barRadius: 4,
    						scales: {
    							xAxes: [{
    								display: false,
    								gridLines: false,
    								stacked: true
    							}],
    							yAxes: [{
    								display: false,
    								stacked: true,
    								gridLines: false
    							}]
    						},
    						layout: {
    							padding: {
    								left: 0,
    								right: 0,
    								top: 0,
    								bottom: 0
    							}
    						}
    					}
    				});
    				
    				
    				
    				var chartContainer = $('#m_chart_daily_sales_lbp');
    				
    				if (chartContainer.length == 0) {
    					return;
    				}
    				
    				var chartData = {
    						labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
    						datasets: [{
    							//label: 'Dataset 1',
    							backgroundColor: mUtil.getColor('success'),
    							data: response.daily_orders_lbp_array
    						}, {
    							//label: 'Dataset 2',
    							backgroundColor: '#f3f3fb',
    							data: response.daily_orders_lbp_array
    						}]
    				};
    				
    				var chart = new Chart(chartContainer, {
    					type: 'bar',
    					data: chartData,
    					options: {
    						title: {
    							display: false,
    						},
    						tooltips: {
    							intersect: false,
    							mode: 'nearest',
    							xPadding: 10,
    							yPadding: 10,
    							caretPadding: 10
    						},
    						legend: {
    							display: false
    						},
    						responsive: true,
    						maintainAspectRatio: false,
    						barRadius: 4,
    						scales: {
    							xAxes: [{
    								display: false,
    								gridLines: false,
    								stacked: true
    							}],
    							yAxes: [{
    								display: false,
    								stacked: true,
    								gridLines: false
    							}]
    						},
    						layout: {
    							padding: {
    								left: 0,
    								right: 0,
    								top: 0,
    								bottom: 0
    							}
    						}
    					}
    				});
    				
    				
    				
    			}
    		}
    	});
    	
    }
    
        

    //== Trends Stats.
    //** Based on Chartjs plugin - http://www.chartjs.org/
    var trendsStats = function() {
        if ($('#m_chart_trends_stats').length == 0) {
            return;
        }

        var ctx = document.getElementById("m_chart_trends_stats").getContext("2d");

        var gradient = ctx.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, Chart.helpers.color('#00c5dc').alpha(0.7).rgbString());
        gradient.addColorStop(1, Chart.helpers.color('#f2feff').alpha(0).rgbString());

        var config = {
            type: 'line',
            data: {
                labels: [
                    "January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
                    "January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
                    "January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
                    "January", "February", "March", "April"
                ],
                datasets: [{
                    label: "Sales Stats",
                    backgroundColor: gradient, // Put the gradient here as a fill color
                    borderColor: '#0dc8de',

                    pointBackgroundColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
                    pointBorderColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
                    pointHoverBackgroundColor: mUtil.getColor('danger'),
                    pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.2).rgbString(),

                    //fill: 'start',
                    data: [
                        20, 10, 18, 15, 26, 18, 15, 22, 16, 12,
                        12, 13, 10, 18, 14, 24, 16, 12, 19, 21,
                        16, 14, 21, 21, 13, 15, 22, 24, 21, 11,
                        14, 19, 21, 17
                    ]
                }]
            },
            options: {
                title: {
                    display: false,
                },
                tooltips: {
                    intersect: false,
                    mode: 'nearest',
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                legend: {
                    display: false
                },
                responsive: true,
                maintainAspectRatio: false,
                hover: {
                    mode: 'index'
                },
                scales: {
                    xAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                elements: {
                    line: {
                        tension: 0.19
                    },
                    point: {
                        radius: 4,
                        borderWidth: 12
                    }
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 5,
                        bottom: 0
                    }
                }
            }
        };

        var chart = new Chart(ctx, config);
    }

    //== Trends Stats 2.
    //** Based on Chartjs plugin - http://www.chartjs.org/
    var trendsStats2 = function() {
        if ($('#m_chart_trends_stats_2').length == 0) {
            return;
        }

        var ctx = document.getElementById("m_chart_trends_stats_2").getContext("2d");

        var config = {
            type: 'line',
            data: {
                labels: [
                    "January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
                    "January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
                    "January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
                    "January", "February", "March", "April"
                ],
                datasets: [{
                    label: "Sales Stats",
                    backgroundColor: '#d2f5f9', // Put the gradient here as a fill color
                    borderColor: mUtil.getColor('brand'),

                    pointBackgroundColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
                    pointBorderColor: Chart.helpers.color('#ffffff').alpha(0).rgbString(),
                    pointHoverBackgroundColor: mUtil.getColor('danger'),
                    pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.2).rgbString(),

                    //fill: 'start',
                    data: [
                        20, 10, 18, 15, 32, 18, 15, 22, 8, 6,
                        12, 13, 10, 18, 14, 24, 16, 12, 19, 21,
                        16, 14, 24, 21, 13, 15, 27, 29, 21, 11,
                        14, 19, 21, 17
                    ]
                }]
            },
            options: {
                title: {
                    display: false,
                },
                tooltips: {
                    intersect: false,
                    mode: 'nearest',
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                legend: {
                    display: false
                },
                responsive: true,
                maintainAspectRatio: false,
                hover: {
                    mode: 'index'
                },
                scales: {
                    xAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                elements: {
                    line: {
                        tension: 0.19
                    },
                    point: {
                        radius: 4,
                        borderWidth: 12
                    }
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 5,
                        bottom: 0
                    }
                }
            }
        };

        var chart = new Chart(ctx, config);
    }

    //== Trends Stats.
    //** Based on Chartjs plugin - http://www.chartjs.org/
    var latestTrendsMap = function() {
        if ($('#m_chart_latest_trends_map').length == 0) {
            return;
        }

        try {
            var map = new GMaps({
                div: '#m_chart_latest_trends_map',
                lat: -12.043333,
                lng: -77.028333
            });
        } catch (e) {
            console.log(e);
        }
    }

    //== Revenue Change.
    //** Based on Morris plugin - http://morrisjs.github.io/morris.js/
    var revenueChange = function() {
        if ($('#m_chart_revenue_change').length == 0) {
            return;
        }

        Morris.Donut({
            element: 'm_chart_revenue_change',
            data: [{
                    label: "New York",
                    value: 10
                },
                {
                    label: "London",
                    value: 7
                },
                {
                    label: "Paris",
                    value: 20
                }
            ],
            colors: [
                mUtil.getColor('accent'),
                mUtil.getColor('danger'),
                mUtil.getColor('brand')
            ],
        });
    }

    //== Support Tickets Chart.
    //** Based on Morris plugin - http://morrisjs.github.io/morris.js/
    var supportTickets = function() {
        if ($('#m_chart_support_tickets').length == 0) {
            return;
        }

        Morris.Donut({
            element: 'm_chart_support_tickets',
            data: [{
                    label: "Margins",
                    value: 20
                },
                {
                    label: "Profit",
                    value: 70
                },
                {
                    label: "Lost",
                    value: 10
                }
            ],
            labelColor: '#a7a7c2',
            colors: [
                mUtil.getColor('accent'),
                mUtil.getColor('brand'),
                mUtil.getColor('danger')
            ]
            //formatter: function (x) { return x + "%"}
        });
    }

    //== Support Tickets Chart.
    //** Based on Morris plugin - http://morrisjs.github.io/morris.js/
    var servicespiechart = function() {
    	
     	var _token 			= $('input[name=_token]').val();
    	var base_url = $('#BASE_URL').val();
    	var params = {_token : _token};
    	$.ajax
    	({
    		url : base_url + "/request/dashboard/services/servicespiechart",
    		data : params,
    		dataType : "Json",
    		type : "POST",
    		success : function(response){
    			if(response.is_error == 0)
    			{
    				var chartData = {
    		                "1995": [{
    		                        "sector": "Agriculture",
    		                        "size": 6.6
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.6
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 23.2
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 2.2
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 4.5
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 14.6
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 9.3
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 22.5
    		                    }
    		                ],
    		                "1996": [{
    		                        "sector": "Agriculture",
    		                        "size": 6.4
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.5
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 22.4
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 2
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 4.2
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 14.8
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 9.7
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 22
    		                    }
    		                ],
    		                "1997": [{
    		                        "sector": "Agriculture",
    		                        "size": 6.1
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.2
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 20.9
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.8
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 4.2
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 13.7
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 9.4
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 22.1
    		                    }
    		                ],
    		                "1998": [{
    		                        "sector": "Agriculture",
    		                        "size": 6.2
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.3
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 21.4
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.9
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 4.2
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 14.5
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.6
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 23
    		                    }
    		                ],
    		                "1999": [{
    		                        "sector": "Agriculture",
    		                        "size": 5.7
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.2
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 20
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.8
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 4.4
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 15.2
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.5
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 24.7
    		                    }
    		                ],
    		                "2000": [{
    		                        "sector": "Agriculture",
    		                        "size": 5.1
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.3
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 20.4
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.7
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 4
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 16.3
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.7
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 24.6
    		                    }
    		                ],
    		                "2001": [{
    		                        "sector": "Agriculture",
    		                        "size": 5.5
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.2
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 20.3
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.6
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 3.1
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 16.3
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.7
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 25.8
    		                    }
    		                ],
    		                "2002": [{
    		                        "sector": "Agriculture",
    		                        "size": 5.7
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.2
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 20.5
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.6
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 3.6
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 16.1
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.7
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 26
    		                    }
    		                ],
    		                "2003": [{
    		                        "sector": "Agriculture",
    		                        "size": 4.9
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.2
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 19.4
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.5
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 3.3
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 16.2
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 11
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 27.5
    		                    }
    		                ],
    		                "2004": [{
    		                        "sector": "Agriculture",
    		                        "size": 4.7
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.2
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 18.4
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.4
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 3.3
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 16.9
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.6
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 28.1
    		                    }
    		                ],
    		                "2005": [{
    		                        "sector": "Agriculture",
    		                        "size": 4.3
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.2
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 18.1
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.4
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 3.9
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 15.7
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.6
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 29.1
    		                    }
    		                ],
    		                "2006": [{
    		                        "sector": "Agriculture",
    		                        "size": 4
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.2
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 16.5
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.3
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 3.7
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 14.2
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 12.1
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 29.1
    		                    }
    		                ],
    		                "2007": [{
    		                        "sector": "Agriculture",
    		                        "size": 4.7
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.2
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 16.2
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.2
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 4.1
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 15.6
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 11.2
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 30.4
    		                    }
    		                ],
    		                "2008": [{
    		                        "sector": "Agriculture",
    		                        "size": 4.9
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.3
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 17.2
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.4
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 5.1
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 15.4
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 11.1
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 28.4
    		                    }
    		                ],
    		                "2009": [{
    		                        "sector": "Agriculture",
    		                        "size": 4.7
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.3
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 16.4
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 1.9
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 4.9
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 15.5
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.9
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 27.9
    		                    }
    		                ],
    		                "2010": [{
    		                        "sector": "Agriculture",
    		                        "size": 4.2
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.3
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 16.2
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 2.2
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 4.3
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 15.7
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.2
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 28.8
    		                    }
    		                ],
    		                "2011": [{
    		                        "sector": "Agriculture",
    		                        "size": 4.1
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.3
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 14.9
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 2.3
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 5
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 17.3
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.2
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 27.2
    		                    }
    		                ],
    		                "2012": [{
    		                        "sector": "Agriculture",
    		                        "size": 3.8
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.3
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 14.9
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 2.6
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 5.1
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 15.8
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.7
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 28
    		                    }
    		                ],
    		                "2013": [{
    		                        "sector": "Agriculture",
    		                        "size": 3.7
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.2
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 14.9
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 2.7
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 5.7
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 16.5
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.5
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 26.6
    		                    }
    		                ],
    		                "2014": [{
    		                        "sector": "Agriculture",
    		                        "size": 3.9
    		                    },
    		                    {
    		                        "sector": "Mining and Quarrying",
    		                        "size": 0.2
    		                    },
    		                    {
    		                        "sector": "Manufacturing",
    		                        "size": 14.5
    		                    },
    		                    {
    		                        "sector": "Electricity and Water",
    		                        "size": 2.7
    		                    },
    		                    {
    		                        "sector": "Construction",
    		                        "size": 5.6
    		                    },
    		                    {
    		                        "sector": "Trade (Wholesale, Retail, Motor)",
    		                        "size": 16.6
    		                    },
    		                    {
    		                        "sector": "Transport and Communication",
    		                        "size": 10.5
    		                    },
    		                    {
    		                        "sector": "Finance, real estate and business services",
    		                        "size": 26.5
    		                    }
    		                ]
    		            };

    		            /**
    		             * Create the chart
    		             */
    		            var currentYear = 1995;
    		            var chart = AmCharts.makeChart("services_charts", {
    		                "type": "pie",
    		                "theme": "light",
    		                "dataProvider": [],
    		                "valueField": "size",
    		                "titleField": "sector",
    		                "startDuration": 0,
    		                "innerRadius": 80,
    		                "pullOutRadius": 20,
    		                "marginTop": 30,
    		                "titles": [{
    		                    "text": "South African Economy"
    		                }],
    		                "allLabels": [{
    		                    "y": "54%",
    		                    "align": "center",
    		                    "size": 25,
    		                    "bold": true,
    		                    "text": "1995",
    		                    "color": "#555"
    		                }, {
    		                    "y": "49%",
    		                    "align": "center",
    		                    "size": 15,
    		                    "text": "Year",
    		                    "color": "#555"
    		                }],
    		                "listeners": [{
    		                    "event": "init",
    		                    "method": function(e) {
    		                        var chart = e.chart;

    		                        function getCurrentData() {
    		                            var data = chartData[currentYear];
    		                            currentYear++;
    		                            if (currentYear > 2014)
    		                                currentYear = 1995;
    		                            return data;
    		                        }

    		                        function loop() {
    		                            chart.allLabels[0].text = currentYear;
    		                            var data = getCurrentData();
    		                            chart.animateData(data, {
    		                                duration: 1000,
    		                                complete: function() {
    		                                    setTimeout(loop, 3000);
    		                                }
    		                            });
    		                        }

    		                        loop();
    		                    }
    		                }],
    		                "export": {
    		                    "enabled": true
    		                }
    		            });
    			}
    		}
    	});
    	
    	
    	 
    }

    //== Activities Charts.
    //** Based on Chartjs plugin - http://www.chartjs.org/
    var activitiesChart = function() {
        if ($('#m_chart_activities').length == 0) {
            return;
        }

        var ctx = document.getElementById("m_chart_activities").getContext("2d");

        var gradient = ctx.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, Chart.helpers.color('#e14c86').alpha(1).rgbString());
        gradient.addColorStop(1, Chart.helpers.color('#e14c86').alpha(0.3).rgbString());

        var config = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October"],
                datasets: [{
                    label: "Sales Stats",
                    backgroundColor: gradient,
                    borderColor: '#e13a58',

                    pointBackgroundColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                    pointBorderColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                    pointHoverBackgroundColor: mUtil.getColor('light'),
                    pointHoverBorderColor: Chart.helpers.color('#ffffff').alpha(0.1).rgbString(),

                    //fill: 'start',
                    data: [
                        10, 14, 12, 16, 9, 11, 13, 9, 13, 15
                    ]
                }]
            },
            options: {
                title: {
                    display: false,
                },
                tooltips: {
                    mode: 'nearest',
                    intersect: false,
                    position: 'nearest',
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                legend: {
                    display: false
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                elements: {
                    line: {
                        tension: 0.0000001
                    },
                    point: {
                        radius: 4,
                        borderWidth: 12
                    }
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 10,
                        bottom: 0
                    }
                }
            }
        };

        var chart = new Chart(ctx, config);
    }

    //== Bandwidth Charts 1.
    //** Based on Chartjs plugin - http://www.chartjs.org/
    var SupplierStockChart = function() {
    	var base_url 			= $('input[name=base_url]').val();
		var _token	 			= $('input[name=_token]').val(); 
		var params = { _token : _token };
		$.ajax
		({
			url : base_url + "/request/dashboard/getinboundsupplier",
			data : params,
			dataType : "json",
			type : "POST",
			success : function(response){ 
				 	$("#TOTAL_STOCK_NUMBER").html(response.total_supplier_stock);
			        var ctx = document.getElementById("StockSupplierChart").getContext("2d");

			        var gradient = ctx.createLinearGradient(0, 0, 0, 240);
			        gradient.addColorStop(0, Chart.helpers.color('#d1f1ec').alpha(1).rgbString());
			        gradient.addColorStop(1, Chart.helpers.color('#d1f1ec').alpha(0.3).rgbString());

			        var config = {
			            type: 'line',
			            data: {
			                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October"],
			                datasets: [{
			                    label: "Total Amount",
			                    backgroundColor: gradient,
			                    borderColor: mUtil.getColor('success'),

			                    pointBackgroundColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
			                    pointBorderColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
			                    pointHoverBackgroundColor: mUtil.getColor('danger'),
			                    pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.1).rgbString(),

			                    //fill: 'start',
			                    data: JSON.parse(response.supplier_stock_amount)
			                }]
			            },
			            options: {
			                title: {
			                    display: true,
			                },
			                tooltips: {
			                    mode: 'nearest',
			                    intersect: false,
			                    position: 'nearest',
			                    xPadding: 10,
			                    yPadding: 10,
			                    caretPadding: 10
			                },
			                legend: {
			                    display: true
			                },
			                responsive: true,
			                maintainAspectRatio: false,
			                scales: {
			                    xAxes: [{
			                        display: true,
			                        gridLines: true,
			                        scaleLabel: {
			                            display: true,
			                            labelString: 'Month'
			                        }
			                    }],
			                    yAxes: [{
			                        display: false,
			                        gridLines: false,
			                        scaleLabel: {
			                            display: true,
			                            labelString: 'Value'
			                        },
			                        ticks: {
			                            beginAtZero: true
			                        }
			                    }]
			                },
			                elements: {
			                    line: {
			                        tension: 0.0000001
			                    },
			                    point: {
			                        radius: 4,
			                        borderWidth: 12
			                    }
			                },
			                layout: {
			                    padding: {
			                        left: 0,
			                        right: 0,
			                        top: 10,
			                        bottom: 0
			                    }
			                }
			            }
			        };

			        var chart = new Chart(ctx, config);
			}
		});
    	
    	
        
    }

    //== Bandwidth Charts 2.
    //** Based on Chartjs plugin - http://www.chartjs.org/
    var InvoiceCharts = function() {
       
    	var base_url 			= $('input[name=base_url]').val();
		var _token	 			= $('input[name=_token]').val(); 
		var params = { _token : _token };
		$.ajax
		({
			url : base_url + "/request/dashboard/getoutboundinvoices",
			data : params,
			dataType : "json",
			type : "POST",
			success : function(response){
				var total_amounts = response.total_order_amount;
				var order_stock_amount = response.order_stock_amount;  
				
				Object.entries(total_amounts).forEach(([currency_code, total_data]) => { 
					if(document.getElementById("TOTAL_ORDER_" + currency_code) != null)
						document.getElementById("TOTAL_ORDER_" + currency_code ).innerHTML  = total_data;
				});
				Object.entries(order_stock_amount).forEach(([currency_code, invoices_data]) => {   
					 if ($('#m_chart_invoices_' +currency_code).length == 0) {
				            return;
				        }
					var ctx = document.getElementById("m_chart_invoices_" +currency_code ).getContext("2d");

			        var gradient = ctx.createLinearGradient(0, 0, 0, 240);
			        gradient.addColorStop(0, Chart.helpers.color('#ffefce').alpha(1).rgbString());
			        gradient.addColorStop(1, Chart.helpers.color('#ffefce').alpha(0.3).rgbString());

			        var config = {
			            type: 'line',
			            data: {
			                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October"],
			                datasets: [{
			                    label: "Total Orders Amount",
			                    backgroundColor: gradient,
			                    borderColor: mUtil.getColor('warning'),

			                    pointBackgroundColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
			                    pointBorderColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
			                    pointHoverBackgroundColor: mUtil.getColor('danger'),
			                    pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.1).rgbString(),

			                    //fill: 'start',
			                    data: JSON.parse(invoices_data)
			                }]
			            },
			            options: {
			                title: {
			                    display: false,
			                },
			                tooltips: {
			                    mode: 'nearest',
			                    intersect: false,
			                    position: 'nearest',
			                    xPadding: 10,
			                    yPadding: 10,
			                    caretPadding: 10
			                },
			                legend: {
			                    display: true
			                },
			                responsive: true,
			                maintainAspectRatio: false,
			                scales: {
			                    xAxes: [{
			                        display: true,
			                        gridLines: true,
			                        scaleLabel: {
			                            display: true,
			                            labelString: 'Month'
			                        }
			                    }],
			                    yAxes: [{
			                        display: true,
			                        gridLines: true,
			                        scaleLabel: {
			                            display: true,
			                            labelString: 'Value'
			                        },
			                        ticks: {
			                            beginAtZero: true
			                        }
			                    }]
			                },
			                elements: {
			                    line: {
			                        tension: 0.0000001
			                    },
			                    point: {
			                        radius: 4,
			                        borderWidth: 12
			                    }
			                },
			                layout: {
			                    padding: {
			                        left: 0,
			                        right: 0,
			                        top: 10,
			                        bottom: 0
			                    }
			                }
			            }
			        };

			        var chart = new Chart(ctx, config);
					
				});
				
				 
				
				 
				
				
			}
		});
    	 
        
    }

    //== Bandwidth Charts 2.
    //** Based on Chartjs plugin - http://www.chartjs.org/
    var adWordsStat = function() {
        if ($('#m_chart_adwords_stats').length == 0) {
            return;
        }

        var ctx = document.getElementById("m_chart_adwords_stats").getContext("2d");

        var gradient = ctx.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, Chart.helpers.color('#ffefce').alpha(1).rgbString());
        gradient.addColorStop(1, Chart.helpers.color('#ffefce').alpha(0.3).rgbString());

        var config = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October"],
                datasets: [{
                    label: "AdWord Clicks",
                    backgroundColor: mUtil.getColor('brand'),
                    borderColor: mUtil.getColor('brand'),

                    pointBackgroundColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                    pointBorderColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                    pointHoverBackgroundColor: mUtil.getColor('danger'),
                    pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.1).rgbString(),
                    data: [
                        12, 16, 9, 18, 13, 12, 18, 12, 15, 17
                    ]
                }, {
                    label: "AdWords Views",

                    backgroundColor: mUtil.getColor('accent'),
                    borderColor: mUtil.getColor('accent'),

                    pointBackgroundColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                    pointBorderColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                    pointHoverBackgroundColor: mUtil.getColor('danger'),
                    pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.1).rgbString(),
                    data: [
                        10, 14, 12, 16, 9, 11, 13, 9, 13, 15
                    ]
                }]
            },
            options: {
                title: {
                    display: false,
                },
                tooltips: {
                    mode: 'nearest',
                    intersect: false,
                    position: 'nearest',
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                legend: {
                    display: false
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        stacked: true,
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                elements: {
                    line: {
                        tension: 0.0000001
                    },
                    point: {
                        radius: 4,
                        borderWidth: 12
                    }
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 10,
                        bottom: 0
                    }
                }
            }
        };

        var chart = new Chart(ctx, config);
    }

    //== Bandwidth Charts 2.
    //** Based on Chartjs plugin - http://www.chartjs.org/
    var financeSummary = function() {
        if ($('#m_chart_finance_summary').length == 0) {
            return;
        }

        var ctx = document.getElementById("m_chart_finance_summary").getContext("2d");

        var config = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October"],
                datasets: [{
                    label: "AdWords Views",

                    backgroundColor: mUtil.getColor('accent'),
                    borderColor: mUtil.getColor('accent'),

                    pointBackgroundColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                    pointBorderColor: Chart.helpers.color('#000000').alpha(0).rgbString(),
                    pointHoverBackgroundColor: mUtil.getColor('danger'),
                    pointHoverBorderColor: Chart.helpers.color('#000000').alpha(0.1).rgbString(),
                    data: [
                        10, 14, 12, 16, 9, 11, 13, 9, 13, 15
                    ]
                }]
            },
            options: {
                title: {
                    display: false,
                },
                tooltips: {
                    mode: 'nearest',
                    intersect: false,
                    position: 'nearest',
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                legend: {
                    display: false
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: false,
                        gridLines: false,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                elements: {
                    line: {
                        tension: 0.0000001
                    },
                    point: {
                        radius: 4,
                        borderWidth: 12
                    }
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 10,
                        bottom: 0
                    }
                }
            }
        };

        var chart = new Chart(ctx, config);
    }

    //== Quick Stat Charts
    var quickStats = function() {
        _initSparklineChart($('#m_chart_quick_stats_1'), [10, 14, 18, 11, 9, 12, 14, 17, 18, 14], mUtil.getColor('brand'), 3);
        _initSparklineChart($('#m_chart_quick_stats_2'), [11, 12, 18, 13, 11, 12, 15, 13, 19, 15], mUtil.getColor('danger'), 3);
        _initSparklineChart($('#m_chart_quick_stats_3'), [12, 12, 18, 11, 15, 12, 13, 16, 11, 18], mUtil.getColor('success'), 3);
        _initSparklineChart($('#m_chart_quick_stats_4'), [11, 9, 13, 18, 13, 15, 14, 13, 18, 15], mUtil.getColor('accent'), 3);
    }

    var daterangepickerInit = function() {
        if ($('#m_dashboard_daterangepicker').length == 0) {
            return;
        }

        var picker = $('#m_dashboard_daterangepicker');
        var start = moment();
        var end = moment();

        function cb(start, end, label) {
            var title = '';
            var range = '';

            if ((end - start) < 100) {
                title = 'Today:';
                range = start.format('MMM D');
            } else if (label == 'Yesterday') {
                title = 'Yesterday:';
                range = start.format('MMM D');
            } else {
                range = start.format('MMM D') + ' - ' + end.format('MMM D');
            }

            picker.find('.m-subheader__daterange-date').html(range);
            picker.find('.m-subheader__daterange-title').html(title);
        }

        picker.daterangepicker({
            startDate: start,
            endDate: end,
            opens: 'left',
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end, '');
    }

    var DisplayListTotalAccounts = function() {
    	var base_url 			= $('input[name=base_url]').val();
		var _token	 			= $('input[name=_token]').val();
		var fisical_year =  $('input[name=fisical_year]').val();
		var params = { _token : _token , fisical_year : fisical_year };
		$.ajax
		({
			url : base_url + "/request/dashboard/displaylistaccountgroup",
			data : params,
			dataType : "json",
			type : "POST",
			success : function(response){
				$(".TransactionDetails").html(response.display);
			}
		});
    }
    
    
    var datatableLatestOrders = function() {
        if ($('#m_datatable_latest_orders').length === 0) {
            return;
        }

        var datatable = $('.m_datatable').mDatatable({
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: 'https://keenthemes.com/metronic/preview/inc/api/datatables/demos/default.php'
                    }
                },
                pageSize: 10,
                saveState: {
                    cookie: false,
                    webstorage: true
                },
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true
            },

            layout: {
                theme: 'default',
                class: '',
                scroll: true,
                height: 380,
                footer: false
            },

            sortable: true,

            filterable: false,

            pagination: true,

            columns: [{
                field: "RecordID",
                title: "#",
                sortable: false,
                width: 40,
                selector: {
                    class: 'm-checkbox--solid m-checkbox--brand'
                },
                textAlign: 'center'
            }, {
                field: "OrderID",
                title: "Order ID",
                sortable: 'asc',
                filterable: false,
                width: 150,
                template: '{{OrderID}} - {{ShipCountry}}'
            }, {
                field: "ShipName",
                title: "Ship Name",
                width: 150,
                responsive: {
                    visible: 'lg'
                }
            }, {
                field: "ShipDate",
                title: "Ship Date"
            }, {
                field: "Status",
                title: "Status",
                width: 100,
                // callback function support for column rendering
                template: function(row) {
                    var status = {
                        1: {
                            'title': 'Pending',
                            'class': 'm-badge--brand'
                        },
                        2: {
                            'title': 'Delivered',
                            'class': ' m-badge--metal'
                        },
                        3: {
                            'title': 'Canceled',
                            'class': ' m-badge--primary'
                        },
                        4: {
                            'title': 'Success',
                            'class': ' m-badge--success'
                        },
                        5: {
                            'title': 'Info',
                            'class': ' m-badge--info'
                        },
                        6: {
                            'title': 'Danger',
                            'class': ' m-badge--danger'
                        },
                        7: {
                            'title': 'Warning',
                            'class': ' m-badge--warning'
                        }
                    };
                    return '<span class="m-badge ' + status[row.Status].class + ' m-badge--wide">' + status[row.Status].title + '</span>';
                }
            }, {
                field: "Type",
                title: "Type",
                width: 100,
                // callback function support for column rendering
                template: function(row) {
                    var status = {
                        1: {
                            'title': 'Online',
                            'state': 'danger'
                        },
                        2: {
                            'title': 'Retail',
                            'state': 'primary'
                        },
                        3: {
                            'title': 'Direct',
                            'state': 'accent'
                        }
                    };
                    return '<span class="m-badge m-badge--' + status[row.Type].state + ' m-badge--dot"></span>&nbsp;<span class="m--font-bold m--font-' + status[row.Type].state + '">' + status[row.Type].title + '</span>';
                }
            }, {
                field: "Actions",
                width: 110,
                title: "Actions",
                sortable: false,
                overflow: 'visible',
                template: function(row) {
                    var dropup = (row.getDatatable().getPageSize() - row.getIndex()) <= 4 ? 'dropup' : '';

                    return '\
                        <div class="dropdown ' + dropup + '">\
                            <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                                <i class="la la-ellipsis-h"></i>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-right">\
                                <a class="dropdown-item" href="#"><i class="la la-edit"></i> Edit Details</a>\
                                <a class="dropdown-item" href="#"><i class="la la-leaf"></i> Update Status</a>\
                                <a class="dropdown-item" href="#"><i class="la la-print"></i> Generate Report</a>\
                            </div>\
                        </div>\
                        <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">\
                            <i class="la la-edit"></i>\
                        </a>\
                        <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">\
                            <i class="la la-trash"></i>\
                        </a>\
                    ';
                }
            }]
        });
    }

    var calendarInit = function() {
        if ($('#m_calendar').length === 0) {
            return;
        }
        
        var todayDate = moment().startOf('day');
        var YM = todayDate.format('YYYY-MM');
        var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
        var TODAY = todayDate.format('YYYY-MM-DD');
        var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

        $('#m_calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            navLinks: true,
            defaultDate: moment('2017-09-15'),
            events: [
                {
                    title: 'Meeting',
                    start: moment('2017-08-28'),
                    description: 'Lorem ipsum dolor sit incid idunt ut',
                    className: "m-fc-event--light m-fc-event--solid-warning"
                },
                {
                    title: 'Conference',                    
                    description: 'Lorem ipsum dolor incid idunt ut labore',
                    start: moment('2017-08-29T13:30:00'),
                    end: moment('2017-08-29T17:30:00'),
                    className: "m-fc-event--accent"
                },
                {
                    title: 'Dinner',
                    start: moment('2017-08-30'),
                    description: 'Lorem ipsum dolor sit tempor incid',
                    className: "m-fc-event--light  m-fc-event--solid-danger"
                },
                {
                    title: 'All Day Event',
                    start: moment('2017-09-01'),
                    description: 'Lorem ipsum dolor sit incid idunt ut',
                    className: "m-fc-event--danger m-fc-event--solid-focus"
                },
                {
                    title: 'Reporting',                    
                    description: 'Lorem ipsum dolor incid idunt ut labore',
                    start: moment('2017-09-03T13:30:00'),
                    end: moment('2017-09-04T17:30:00'),
                    className: "m-fc-event--accent"
                },
                {
                    title: 'Company Trip',
                    start: moment('2017-09-05'),
                    end: moment('2017-09-07'),
                    description: 'Lorem ipsum dolor sit tempor incid',
                    className: "m-fc-event--primary"
                },
                {
                    title: 'ICT Expo 2017 - Product Release',
                    start: moment('2017-09-09'),
                    description: 'Lorem ipsum dolor sit tempor inci',
                    className: "m-fc-event--light m-fc-event--solid-primary"
                },
                {
                    title: 'Dinner',
                    start: moment('2017-09-12'),
                    description: 'Lorem ipsum dolor sit amet, conse ctetur'
                },
                {
                    id: 999,
                    title: 'Repeating Event',
                    start: moment('2017-09-15T16:00:00'),
                    description: 'Lorem ipsum dolor sit ncididunt ut labore',
                    className: "m-fc-event--danger"
                },
                {
                    id: 1000,
                    title: 'Repeating Event',
                    description: 'Lorem ipsum dolor sit amet, labore',
                    start: moment('2017-09-18T19:00:00'),
                },
                {
                    title: 'Conference',
                    start: moment('2017-09-20T13:00:00'),
                    end: moment('2017-09-21T19:00:00'),
                    description: 'Lorem ipsum dolor eius mod tempor labore',
                    className: "m-fc-event--accent"
                },
                {
                    title: 'Meeting',
                    start: moment('2017-09-11'),
                    description: 'Lorem ipsum dolor eiu idunt ut labore'
                },
                {
                    title: 'Lunch',
                    start: moment('2017-09-18'),
                    className: "m-fc-event--info m-fc-event--solid-accent",
                    description: 'Lorem ipsum dolor sit amet, ut labore'
                },
                {
                    title: 'Meeting',
                    start: moment('2017-09-24'),
                    className: "m-fc-event--warning",
                    description: 'Lorem ipsum conse ctetur adipi scing'
                },
                {
                    title: 'Happy Hour',
                    start: moment('2017-09-24'),
                    className: "m-fc-event--light m-fc-event--solid-focus",
                    description: 'Lorem ipsum dolor sit amet, conse ctetur'
                },
                {
                    title: 'Dinner',
                    start: moment('2017-09-24'),
                    className: "m-fc-event--solid-focus m-fc-event--light",
                    description: 'Lorem ipsum dolor sit ctetur adipi scing'
                },
                {
                    title: 'Birthday Party',
                    start: moment('2017-09-24'),
                    className: "m-fc-event--primary",
                    description: 'Lorem ipsum dolor sit amet, scing'
                },
                {
                    title: 'Company Event',
                    start: moment('2017-09-24'),
                    className: "m-fc-event--danger",
                    description: 'Lorem ipsum dolor sit amet, scing'
                },
                {
                    title: 'Click for Google',
                    url: 'http://google.com/',
                    start: moment('2017-09-26'),
                    className: "m-fc-event--solid-info m-fc-event--light",
                    description: 'Lorem ipsum dolor sit amet, labore'
                }
            ],

            eventRender: function(event, element) {
                if (element.hasClass('fc-day-grid-event')) {
                    element.data('content', event.description);
                    element.data('placement', 'top');
                    mApp.initPopover(element);
                } else if (element.hasClass('fc-time-grid-event')) {
                    element.find('.fc-title').append('<div class="fc-description">' + event.description + '</div>');
                } else if (element.find('.fc-list-item-title').lenght !== 0) {
                    element.find('.fc-list-item-title').append('<div class="fc-description">' + event.description + '</div>');
                }
            }
        });
    }

    return {
        //== Init demos
        init: function() {
            // init charts
            dailySales(); 
            InvoiceCharts();
         
            // init daterangepicker
            daterangepickerInit();

            // datatables
            datatableLatestOrders();
            DisplayListTotalAccounts();

            // calendar
            calendarInit();
            servicespiechart();
            
        }
    };
}();

//== Class initialization on page load
jQuery(document).ready(function() {
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
    Dashboard.init();
});