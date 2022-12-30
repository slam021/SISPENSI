<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreDapil extends Model
{
    protected $table        = 'core_dapil'; 
    protected $primaryKey   = 'dapil_id';
    
    protected $guarded = [
        'dapil_id',
    ];
}
