<?php

/**
 * Mohamad Kheiredine
 */

namespace App\models\FnB;

use Illuminate\Database\Eloquent\Model;

class FnbPrintJobs extends Model
{
    protected $table = 'fnb_print_jobs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'order_id',
        'kitchen_station_id',
        'payload',
        'status',
        'error_message',
        'attempts',
        'printed_at',
        'next_retry_at'
    ];

    public $timestamps = true;
}
