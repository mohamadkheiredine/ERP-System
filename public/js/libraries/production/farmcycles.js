/**
 *
 */

$(function(){
    farmcycles_module.DisplayListFarmCycles();

    $("#LstFarmCycles").on('click',"a[id*=EDIT_CYCLE_]",farmcycles_module.EditFarmCyclesInfo);
    $("#LstFarmCycles").on('click',"a[id*=DELETE_CYCLE_]",farmcycles_module.DeleteFarmCyclesData);
})
