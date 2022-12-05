<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreVillage extends Model
{
    protected $table        = 'core_village'; 
    protected $primaryKey   = 'kelurahan_id';
    
    protected $guarded = [
        'kelurahan_id',
    ];
}
