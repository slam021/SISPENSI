<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorePollingStation extends Model
{
    protected $table = 'core_polling_station';
    protected $primaryKey = 'polling_station_id';

    protected $guarded = [
        'polling_station_id',
    ];
}
