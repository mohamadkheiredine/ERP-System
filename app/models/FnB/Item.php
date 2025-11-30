<?php


namespace App\models\FnB;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class FnbItem extends Model
{
    protected $table = 'fnb_item';
    public $timestamps = false;
    protected $primaryKey = "fi_id";

    public function Branch()
    {
        return $this->hasOne('App\models\System\Companies', 'cd_id', 'fi_branch_id');
    }

    public function KitchenStations()
    {
        return $this->hasOne('App\models\FnB\KitchenStations', 'ks_id', 'fi_kitchen_id');
    }

    public function TaxAccount()
    {
        return $this->hasOne('App\models\Sales\Terminals', 'pt_id', 'fi_tax_id');
    }


    public function Category()
    {
        return $this->hasOne('App\models\FnB\MenuCategories', 'mc_id', 'fi_category_id');
    }

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id', 'fi_currency_id');
    }

}
