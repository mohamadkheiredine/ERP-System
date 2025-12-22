<?php

namespace App\models\FnB;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class FnbSessionFields extends Model
{
    protected $table = 'fnb_session_fields';
    public $timestamps = false;

    protected $primaryKey = "sf_id";


    public function Cashier()
    {
        return $this->belongsTo('App\models\Users\Users', 'sf_cashier_id', 'id');
    }

    public function Shift()
    {
        return $this->belongsTo('App\models\FnB\FnbPosShift', 'sf_shift_id', 'ps_id');
    }

    public function Currency()
    {
        return $this->belongsTo('App\models\System\Currency', 'sf_currency_id', 'cc_id');
    }
}
