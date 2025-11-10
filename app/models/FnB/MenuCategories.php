<?php

namespace App\models\FnB;
use Illuminate\Database\Eloquent\Model;

class MenuCategories extends Model
{
    protected   $table          = 'fnb_menu_categories';
    public      $timestamps     = false;
    protected   $primaryKey     = "mc_id";
}

?>
