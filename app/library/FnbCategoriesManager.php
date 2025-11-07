<?php

namespace App\library;

use App\models\FnB\MenuCategories;
use Config;
use File;

class FnbCategoriesManager
{
    /**
     * Uploads a category avatar image and deletes the old one if it exists.
     */
    public function UploadAvatarCategory($mc_id)
    {
        $result_array = ['is_error' => 1];

        // Check if file was uploaded
        if (!isset($_FILES['mc_avatar_pic']) || $_FILES['mc_avatar_pic']['error'] !== UPLOAD_ERR_OK) {
            $result_array['error_message'] = 'No file uploaded or upload error.';
            return $result_array;
        }

        // Delete the old avatar if editing existing record
        if ($mc_id != null) {
            $this->DeleteCategoryAvatar($mc_id);
        }

        $file_name = $_FILES['mc_avatar_pic']['name'];
        $file_tmp  = $_FILES['mc_avatar_pic']['tmp_name'];

        // Prepare directory and URL
        $base_dir  = date('Y/m/d/') . '/';
        $directory = public_path(Config::get('constants.CATEGORIES_PATH') . $base_dir);
        $main_url  = url(Config::get('constants.CATEGORIES_PATH') . $base_dir);

        // Ensure directory exists
        if (!is_dir($directory)) {
            File::makeDirectory($directory, 0777, true);
        }

        // Generate unique file name
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_name = md5(uniqid()) . "_" . time();
        $file_path = $directory . $unique_name . "." . $extension;
        $image_url = $main_url . $unique_name . "." . $extension;

        // Move uploaded file
        if (move_uploaded_file($file_tmp, $file_path)) {
            $result_array['is_error'] = 0;
            $result_array['data'] = [
                "mc_profile_base_src" => $base_dir,
                "mc_profile_file_name" => $unique_name,
                "mc_profile_extension" => $extension,
                "mc_profile_url" => $image_url,
            ];
        } else {
            $result_array['error_message'] = 'Failed to move uploaded file.';
        }

        return $result_array;
    }

    /**
     * Deletes a category avatar image if it exists.
     */
    public function DeleteCategoryAvatar($mc_id)
    {
        $category = MenuCategories::find($mc_id);

        if (!$category) {
            return;
        }

        $base_src   = $category->mc_profile_base_src;
        $file_name  = $category->mc_profile_file_name;
        $extension  = $category->mc_profile_extension;

        if (strlen($base_src) > 0 && strlen($file_name) > 0 && strlen($extension) > 0) {
            $image_path = public_path(Config::get('constants.CATEGORIES_PATH') . $base_src . $file_name . "." . $extension);

            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
    }
}
