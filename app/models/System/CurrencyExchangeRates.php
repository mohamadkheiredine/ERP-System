<?php
/***********************************************************
CurrencyExchangeRates.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 4, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/






namespace App\models\System;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class CurrencyExchangeRates extends Model
{
    protected   $table          = 'currency_exchange_rates';
    public      $timestamps     = false;
    protected   $primaryKey     = "er_id";
   
}

