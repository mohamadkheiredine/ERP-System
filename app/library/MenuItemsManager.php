<?php

namespace App\library;

use Config;
use File;
use App\models\FnB\FnbMenuItem;

class MenuItemsManager
{
    /**
     * Upload image for menu items
     */
    public function UploadItemImage($mi_id)
    {
        $result_array = [];
        $result_array['is_error'] = 1;

        // delete old image if editing
        if ($mi_id != null) {
            $this->DeleteItemImage($mi_id);
        }

        // check file input name
        if (!isset($_FILES['mi_avatar_pic']) || strlen($_FILES['mi_avatar_pic']['name']) == 0) {
            $result_array['error_msg'] = "No image uploaded.";
            return $result_array;
        }

        $file_name = $_FILES['mi_avatar_pic']['name'];
        $file_tmp  = $_FILES['mi_avatar_pic']['tmp_name'];

        // same path as PRODUCTS_PATH (you decided this)
        $base_dir  = date("Y/m/d/");
        $directory = public_path() . "/" . Config::get("constants.PRODUCTS_PATH") . $base_dir;
        $main_url  = url("/") . "/" . Config::get("constants.PRODUCTS_PATH") . $base_dir;

        // create directory
        if (!is_dir($directory)) {
            File::makeDirectory($directory, 0777, true);
        }

        // extension
        $file_info = explode(".", $file_name);
        $extension = strtolower(end($file_info));

        // new unique filename
        $new_name = md5(time()) . "_" . rand(1000,999999);

        $file_path = $directory . $new_name . "." . $extension;

        if (move_uploaded_file($file_tmp, $file_path)) {

            $result_array['is_error'] = 0;

            $result_array['data'] = [
                "mi_image_base_src"  => $base_dir,
                "mi_image_file_name" => $new_name,
                "mi_image_extension" => $extension,
            ];
        }

        return $result_array;
    }


    /**
     * Delete old image
     */
    public function DeleteItemImage($mi_id)
    {
        $item = FnbMenuItem::find($mi_id);
        if (!$item) return;

        $base = $item->mi_image_base_src;
        $name = $item->mi_image_file_name;
        $ext  = $item->mi_image_extension;

        $path = public_path() . "/" .
                Config::get('constants.PRODUCTS_PATH') .
                $base . $name . "." . $ext;

        if (
            file_exists($path) &&
            strlen($base) > 0 &&
            strlen($name) > 0 &&
            strlen($ext) > 0
        ) {
            unlink($path);
        }
    }
}
