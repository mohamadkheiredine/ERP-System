/**
 * 
 */
$(function(){ 
	clients_module.DisplayListClients();
	$("#ACCOUNT_CATEGORIES").on("click",function(){
   	  	clients_module.DisplayListClients();
	});
        $('.dropdown-item').on('click',clients_module.ActionDropdown)
        
        $("#LstClients").on("click","a[id*=EDIT_ACCOUNT_]",clients_module.EditAccountInfo);
        $("#LstClients").on("click","a[id*=DELETE_ACCOUNT_]",clients_module.DeleteAccountInfo);
})