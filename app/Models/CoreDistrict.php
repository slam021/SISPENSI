<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreDistrict extends Model
{
    protected $table        = 'core_district'; 
    protected $primaryKey   = 'kecamatan_id';
    
    protected $guarded = [
        'kecamatan_id',
    ];
}
