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
});