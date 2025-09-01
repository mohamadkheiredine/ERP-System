/**
 *
 */

$(function(){


    projecttypes_module.DisplayListProjectTypes();
    $("#LstProjectTypes").on('click',"a[id*=EDIT_TYPE_]",projecttypes_module.EditProjectTypeInfo);
    $("#LstProjectTypes").on('click',"a[id*=DELETE_TYPE_]",projecttypes_module.DeleteProjectTypeData);
})
