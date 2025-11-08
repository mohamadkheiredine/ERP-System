<?php
namespace App\models\Fnb;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    protected   $table          = 'fnb_floor';
    public      $timestamps     = false;
    protected   $primaryKey     = "fl_id";


    public function Company()
    {
        return $this->hasOne('App\models\System\Companies', 'cd_id','fl_branch_id');
    }
}
