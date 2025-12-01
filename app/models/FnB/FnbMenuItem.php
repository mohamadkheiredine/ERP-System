<?php


namespace App\models\FnB;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class FnbMenuItem extends Model
{
    protected $table = 'fnb_menu_items';
    public $timestamps = false;
    protected $primaryKey = "mi_id";

    public function Category()
    {
        return $this->hasOne('App\models\FnB\MenuCategories', 'mc_id', 'mi_category_id');
    }

    public function Unit()
    {
        return $this->hasOne('App\models\System\Units', 'in_unit_of_measure', 'mi_unit_id');
    }

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id', 'mi_currency_id');
    }
}
