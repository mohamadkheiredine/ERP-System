<?php

namespace App\models\Users;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class UserAllowedCompanies extends Model
{
    protected   $table          = 'usr_allowed_companies';
    public      $timestamps     = false;


    public function Company()
    {
        return $this->hasMany('App\models\System\Companies', 'ac_company_id','cd_id');
    }


    public function User()
    {
        return $this->hasMany('App\models\Users\Users', 'ac_user_id','id');
    }
}
