<?php

namespace App\models\FnB;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class FnbPrinters extends Model
{
    protected $table = 'fnb_printers';
    public $timestamps = false;

    protected $primaryKey = "fp_id";


    public function Kitchen()
    {
        return $this->belongsTo('App\models\FnB\KitchenStations', 'ks_id', 'ks_id');
    }
}
