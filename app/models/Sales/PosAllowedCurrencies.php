<?php

namespace App\models\Sales;

use Illuminate\Database\Eloquent\Model;

class PosAllowedCurrencies extends Model
{
    protected   $table          = 'pos_allowed_currencies';
    public      $timestamps     = true;
    protected   $primaryKey     = "ac_id";

    public function Currency()
    {
        return $this->belongsTo('App\models\System\Currency', 'ac_currency_id','cc_id');
    }

    public function Company()
    {
        return $this->belongsTo('App\models\System\Companies', 'ac_company_id','cd_id');
    }


    public function Store()
    {
        return $this->belongsTo('App\models\Sales\Stores', 'ac_store_id','ps_id');
    }

}
