<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorePeriod extends Model
{
    protected $table        = 'core_period'; 
    protected $primaryKey   = 'period_id';
    
    protected $guarded = [
        'period_id',
    ];
}
