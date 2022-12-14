$(function(){
	  var wizardEl = $('#m_wizard');
	    var formEl = $('#m_form');
	    var validator;
	    var wizard;
	    wizard = wizardEl.mWizard({
	        startStep: 1
	    });

	    //== Validation before going to next page
	    wizard.on('beforeNext', function(wizard) {
	    	warehouses_module.SaveWareHouseSettings();
	    })

	    //== Change event
	    wizard.on('change', function(wizard) {
	    	
	    });
})