$(function () {
    floors_module.DisplayListFloors();
    $("select[name=fl_branch_id]").on(
        "change",
        floors_module.DisplayListFloors
    );

    $("input[name=general_search]").on('keyup', floors_module.DisplayListFloors);

    $("#LstFloorsGrid").on(
        "click",
        "a[id*=EDIT_FLOOR_]",
        floors_module.EditFloorInfo
    );
    $("#LstFloorsGrid").on(
        "click",
        "a[id*=DELETE_FLOOR_]",
        floors_module.DeleteFloorData
    );
});
