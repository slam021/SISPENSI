<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreLocation extends Model
{
    protected $table        = 'core_location'; 
    protected $primaryKey   = 'location_id';
    
    protected $guarded = [
        'location_id',
    ];
}
