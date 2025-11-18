<?php

namespace App\models\FnB;

use Illuminate\Database\Eloquent\Model;

class Modifier extends Model {
    protected $table = 'fnb_modifiers';
    protected $primaryKey = 'm_id';
    public $timestamps = true;

    public function Item()
    {
        return $this->hasOne('App\models\FnB\FnbItem', 'fi_id', 'm_item_id');
    }

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id', 'm_currency_id');
    }

    public function Unit()
    {
        return $this->hasOne('App\models\System\Units', 'su_id', 'm_item_id');
    }

}
