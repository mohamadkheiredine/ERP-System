<?php

namespace App\models\FnB;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class FnbPosShift extends Model
{
    protected $table = 'fnb_pos_shifts';
    public $timestamps = false;

    protected $primaryKey = "ps_id";


    public function Cashier()
    {
        return $this->belongsTo('App\models\Users\Users', 'ps_cashier_id', 'id');
    }

    public function Terminal()
    {
        return $this->belongsTo('App\models\Sales\Terminals', 'ps_terminal_id', 'pt_id');
    }

    public function Currency()
    {
        return $this->belongsTo('App\models\System\Currency', 'ps_currency_id', 'cc_id');
    }
}
