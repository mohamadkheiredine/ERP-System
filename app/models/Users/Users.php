<?php

namespace App\models\Users;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use \HighIdeas\UsersOnline\Traits\UsersOnlineTrait;
    
    
    protected   $table          = 'users';
    public      $timestamps     = false;
    protected   $primaryKey     = "id";



}
