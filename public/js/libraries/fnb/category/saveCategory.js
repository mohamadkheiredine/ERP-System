/**
 * Category Form Handler
 * Handles image preview and sends data to category_module.SaveCategoryInfo
 */

$(function () {
    // Initialize CKEditor for description
    ClassicEditor.create(document.querySelector("#MC_CATEGORY_DESCRIPTION"))
        .then((newEditor) => {
            $.editor = newEditor;
        })
        .catch((error) => {
            console.error(error);
        });

    $("#MC_AVATAR_PIC").on("change", function () {
        const fileInput = this;
        const file = fileInput.files[0];
        const allowedExt = ["jpg", "jpeg", "png", "gif"];

        if (!file) return;

        const ext = file.name.split(".").pop().toLowerCase();

        if (allowedExt.includes(ext)) {
            const reader = new FileReader();
            reader.onload = function (e) {
                // Update the image preview
                $("#MC_PROFILE_PIC").attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            alert(
                "Invalid file type. Please upload an image (jpg, jpeg, png, gif)."
            );
            $(fileInput).val(""); // reset input
        }
    });

    $("#BTN_SAVE_CATEGORY").on("click", category_module.SaveCategoryInfo);
});
