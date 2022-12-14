<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;

class UserIpAddressStatus extends Model
{
    protected   $table          = 'user_ip_address_status';
    public      $timestamps     = false;
    protected   $primaryKey     = "as_id";
}
