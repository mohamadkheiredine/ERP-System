<?php

namespace App\Models\FnB;

use Illuminate\Database\Eloquent\Model;

class FnbMenuItemModifier extends Model
{
    protected $table = 'fnb_menu_item_modifiers';
    public $timestamps = false;
    protected $primaryKey = 'im_id';

    public function Item()
    {
        return $this->belongsTo('App\models\FnB\FnbMenuItem', 'fk_menu_item_id', 'mi_id');
    }

    public function Modifier()
    {
        return $this->belongsTo('App\models\FnB\Modifier', 'fk_modifier_id', 'm_id');
    }

    public function Currency()
    {
        return $this->belongsTo('App\models\Sales\Currency', 'im_currency_id', 'cc_id');
    }

    public function Product()
    {
        return $this->belongsTo('App\models\Inventory\Products', 'im_product_id', 'p_id');
    }
}
