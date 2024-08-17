//== Class definition
var Dashboard = function() {

    var StockAmountCategory = function() {
       
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
                }
        });
    	 
        
    }
 
    return {
        //== Init demos
        init: function() {
          StockAmountCategory();

            
        }
    };
}();

//== Class initialization on page load
jQuery(document).ready(function() {
	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
    Dashboard.init();
    
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Sales',
                data: [12000, 15000, 10000, 18000, 20000, 17000, 22000],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Inventory Chart
    var ctx = document.getElementById('inventoryChart').getContext('2d');
    var inventoryChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Product A', 'Product B', 'Product C', 'Product D'],
            datasets: [{
                label: 'Inventory Levels',
                data: [500, 400, 300, 700],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Expenses Chart
    var ctx = document.getElementById('expensesChart').getContext('2d');
    var expensesChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Rent', 'Utilities', 'Salaries', 'Maintenance'],
            datasets: [{
                label: 'Expenses',
                data: [4000, 2000, 5000, 1000],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });

    // Revenue Chart
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Product A', 'Product B', 'Product C', 'Product D'],
            datasets: [{
                label: 'Revenue Distribution',
                data: [15000, 20000, 30000, 10000],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
    
});