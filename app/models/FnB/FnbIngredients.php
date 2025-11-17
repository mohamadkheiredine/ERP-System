<?php

namespace App\Models\FnB;

use Illuminate\Database\Eloquent\Model;

class FnbIngredients extends Model
{
    protected $table = 'fnb_ingredients';
    protected $primaryKey = 'in_id';
    public $timestamps = false;

    public function Product()
    {
        return $this->belongsTo('App\models\Inventory\Products', 'in_product_id', 'p_id');
    }

    public function Item()
    {
        return $this->belongsTo('App\models\FnB\FnbItem', 'in_item_id', 'fi_id');
    }

    public function Unit()
    {
        return $this->belongsTo('App\models\System\Units', 'in_unit_of_measure', 'su_id');
    }

    public function Currency()
    {
        return $this->belongsTo('App\models\System\Currency', 'in_currency_id', 'cc_id');
    }
}
