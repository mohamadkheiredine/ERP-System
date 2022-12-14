<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;

class UserIpAddress extends Model
{
    protected   $table          = 'user_ip_addresses';
    public      $timestamps     = false;
    protected   $primaryKey     = "ip_id";
}
