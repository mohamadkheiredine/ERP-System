<?php
/**
 * Mohamad Kheiredine
 */

namespace App\Models\Fnb;

use Illuminate\Database\Eloquent\Model;

class FnbWasteStock extends Model
{
    protected $table = 'fnb_waste_stock';
    protected $primaryKey = 'ws_id';
    public $timestamps = false;

    protected $fillable = [
        'fk_product_id',
        'fk_stock_id',
        'fk_warehouse_id',
        'ws_quantity',
        'ws_date',
        'ws_created_by',
        'ws_created_at',
    ];

    public function product()
    {
        return $this->belongsTo(
            'App\Models\Inventory\Products',
            'fk_product_id',
            'p_id'
        );
    }

    public function warehouse()
    {
        return $this->belongsTo(
            'App\Models\Inventory\Warehouses',
            'fk_warehouse_id',
            'w_id'
        );
    }
}

