<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreCity extends Model
{
    protected $table        = 'core_city'; 
    protected $primaryKey   = 'city_id';
    
    protected $guarded = [
        'city_id',
    ];
}
