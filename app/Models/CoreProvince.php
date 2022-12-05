<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreProvince extends Model
{
    protected $table        = 'core_province'; 
    protected $primaryKey   = 'province_id';
    
    protected $guarded = [
        'province_id',
    ];
}